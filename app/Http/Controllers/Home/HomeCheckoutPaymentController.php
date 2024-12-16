<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyUserRequest;

use App\Models\Advertising;
use App\Models\Config;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Services\EmailService;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Str;

class HomeCheckoutPaymentController extends Controller
{

    public function getFormTokenIziPay($totalPrice)
    {
        $orderId = Str::random(8);
        $email = Auth::user()->email ?? collect(Session::get("stepEmail"))->get('email');

        $keysClient = base64_encode(config('services.izipay.client_id') . ':' . config('services.izipay.client_secret'));

        $totalPrice = $totalPrice - getDiscountCoupon($totalPrice) < 0 ? 0.0 : $totalPrice - getDiscountCoupon($totalPrice);

        $response = Http::withHeaders([
            'Authorization' => "Basic {$keysClient}",
            'Content-Type' => 'application/json'
        ])
            ->post(config('services.izipay.url'), [
                "amount" => $totalPrice * 100,
                "currency" => "USD",
                "paymentMethods" => [
                    "CARDS"
                ],
                "customer" => [
                    "email" => $email
                ],
                "orderId" => $orderId
            ])->json();

        return $response['answer']['formToken'];
    }

    public function deleteCoupon()
    {
        session()->forget('coupon');

        return redirect()->route('home.payment.checkout');
    }

    public function verifyUser(Request $request)
    {

        if ($request->input('type') === 'pass') {
            $request->validate([
                'email' => 'required|email',
                'type' => 'required'
            ]);
        }

        $data = $request->all();

        if ($data['type'] === 'pass') {
            session()->put('stepEmail', collect([
                'email' => $data['email'],
                'step' => 2
            ]));
        } elseif ($data['type'] === 'return') {
            $email = collect(Session::get("stepEmail"))->get('email') ?? 'example@gmail.com';
            session()->put('stepEmail', collect([
                'email' => $email,
                'step' => 1
            ]));
        }

        return redirect()->route('home.payment.checkout');
    }

    public function checkout($couponCode = NULL)
    {

        if ($couponCode) {

            $coupon = Coupon::where('code', $couponCode)->first();

            if (!$coupon) {
                session()->forget('coupon');
                return redirect()->route('home.payment.checkout');
            } else {

                if ($coupon->type_use === 'ADVERTISING') {

                    if (Auth::check()) {

                        $user = Auth::user();

                        $plan = $user->cart()->first();

                        if (!$plan) {
                            return  redirect(route('home.index'));
                        }

                        $plan = $plan->buyable;

                        $plan->load('advertisements');

                        $hasCoupon = $plan->advertisements->contains(function ($advertisement) use ($coupon) {
                            return $advertisement->coupon_id === $coupon->id;
                        });

                        if (!$hasCoupon) {
                            session()->forget('coupon');
                            return redirect()->route('home.payment.checkout');
                        }

                        $this->verifyCoupon($coupon);
                    } else {

                        $cart = collect(Session::get('cart', []));
                        $planIds = $cart->pluck('buyable_id');

                        $plan = Plan::whereIn('id', $planIds)->with([
                            'advertisements'
                        ])->first();

                        if (!$plan) {
                            return  redirect(route('home.index'));
                        }

                        $hasCoupon = $plan->advertisements->contains(function ($advertisement) use ($coupon) {
                            return $advertisement->coupon_id === $coupon->id;
                        });

                        if (!$hasCoupon) {
                            session()->forget('coupon');
                            return redirect()->route('home.payment.checkout');
                        }

                        $this->verifyCoupon($coupon);
                    }
                } else {
                    $this->verifyCoupon($coupon);
                }
            }
        }

        $totalPrice = 0;
        $empty = true;

        if (Auth::check()) {
            $user = Auth::user();
            $items = $user->cart()->with([
                'buyable',
                'buyable.file' => fn($q) => $q->where('category', 'plans')
            ])
                ->get();

            if ($items->count() >= 1) {
                $empty = false;
            }

            $totalPrice = $items->sum(fn($item) => $item->buyable->price);
        } else {
            $cart = collect(Session::get('cart', []));

            if ($cart->isNotEmpty()) {
                $planIds = $cart->pluck('buyable_id');
                $items = Plan::whereIn('id', $planIds)->with([
                    'file' => fn($q) => $q->where('category', 'plans')
                ])->get();
                $totalPrice = $items->sum(fn($item) => $item->price);
            } else {
                $items = collect([]);
            }

            if ($items->count() >= 1) {
                $empty = false;
            }
        }

        if ($empty) {
            return  redirect(route('home.index'));
        }

        $formToken =  $this->getFormTokenIziPay($totalPrice);


        return view('home.payment.index', compact('items', 'totalPrice', 'formToken'));
    }

    public function getPlan(Plan $plan)
    {
        if (Auth::check()) {

            $user = Auth::user();

            $user->cart()->delete();

            $cart = $plan->cart()->create([
                'user_id' => $user->id,
                'quantity' => 1,
            ]);
        } else {

            $cart = [];

            session()->forget('cart');

            $cart[$plan->id] = [
                'buyable_id' => $plan->id,
                'buyable_type' => Plan::class,
                'quantity' => 1,
            ];

            session()->put('cart', $cart);
        }

        session()->forget('coupon');

        return redirect()->route('home.payment.checkout');
    }

    public function getAdvertising(Advertising $advertising, $couponCode)
    {
        try {
            $coupon = Coupon::where('code', $couponCode)->first();

            if (!$coupon) {
                return redirect()->route('home.index');
            }

            $advertising->load('coupon', 'plan');

            if ($advertising->coupon->code !== $couponCode) {
                return redirect()->route('home.index');
            }

            $plan = $advertising->plan;

            if (Auth::check()) {

                $user = Auth::user();

                $user->cart()->delete();

                $cart = $plan->cart()->create([
                    'user_id' => $user->id,
                    'quantity' => 1,
                ]);
            } else {

                $cart = [];

                session()->forget('cart');

                $cart[$plan->id] = [
                    'buyable_id' => $plan->id,
                    'buyable_type' => Plan::class,
                    'quantity' => 1,
                ];

                session()->put('cart', $cart);
            }

            $this->verifyCoupon($coupon);

            return redirect()->route('home.payment.checkout', ['couponCode' => $coupon->code]);
        } catch (\Throwable $th) {
            return redirect()->route('home.index');
        }
    }

    public function verifyCoupon(Coupon $coupon)
    {
        $user = Auth::user();

        if (!$coupon || $coupon->active === 'N') {
            session()->forget('coupon');
            return;
        }

        if (Carbon::today()->greaterThan($coupon->expired_date)) {
            session()->forget('coupon');
            return;
        }

        if ($coupon->especify_users == 'S') {

            $isValidForUser = $coupon->usersCoupons()
                ->where('couponable_type', User::class)
                ->where('couponable_id', $user->id)
                ->exists();

            if (!$isValidForUser) {
                session()->forget('coupon');
                return;
            }
        }

        if ($coupon->type === 'SINGULAR') {
            if ($coupon->flg_used == 1) {
                session()->forget('coupon');
                return 'singular';
            }
        }

        session()->put('coupon', $coupon->code);
    }

    // public function createOrderForSession($krAnswer)
    // {

    //     $user = Auth::user();
    //     $user->load('cart');
    //     $now = Carbon::now('America/Lima');

    //     $couponCode = Session::get('coupon', false);

    //     if ($couponCode) {
    //         $couponCode = Coupon::where('code', $couponCode)->first() ?? null;

    //         if ($couponCode && $couponCode->type === 'SINGULAR') {
    //             $couponCode->update([
    //                 'flg_used' => 1
    //             ]);
    //         }
    //     }

    //     $order = $user->orders()->create([
    //         'status' => 'Cancelado',
    //         'order_date' => $now,
    //         'payment_type' => 'Izypay',
    //         'coupon_id' => $couponCode ? $couponCode->id : null,
    //         'uuid_transaction' => $krAnswer['transactions'][0]['uuid']
    //     ]);

    //     session()->forget('coupon');

    //     $cartItems = $user->cart;

    //     foreach ($cartItems as $cartItem) {

    //         $order->products()->create([
    //             'orderable_id'  => $cartItem->buyable_id,
    //             'orderable_type' => get_class($cartItem->buyable),
    //             'quantity'      => $cartItem->quantity,
    //             'unit_price'    => $cartItem->buyable->price,
    //         ]);


    //         // $course = $cartItem->buyable->course;

    //         // $certification = $course->productCertifications()->where('user_id', $user->id)->first();

    //         // if (!$certification) {
    //         //     $certification = $course->productCertifications()->create([
    //         //         'user_id' => $user->id,
    //         //         'status' => 'approved',
    //         //     ]);
    //         // }


    //         if ($cartItem->buyable->duration_type == 'months') {
    //             $endDate = $now->copy()->addMonths($cartItem->buyable->duration);
    //         } elseif ($cartItem->buyable->duration_type == 'days') {
    //             $endDate = $now->copy()->addDays($cartItem->buyable->duration);
    //         }

    //         $user->subscriptions()->create([
    //             'order_id' => $order->id,
    //             'subscriptable_id'  => $cartItem->buyable_id,
    //             'subscriptable_type' => get_class($cartItem->buyable),
    //             'status' => 'active',
    //             'start_date' => $now,
    //             'end_date' => $endDate,
    //             // 'certification_id' => $certification->id
    //         ]);
    //     };

    //     session()->forget('cart');
    //     session()->forget('stepEmail');
    //     $user->cart()->delete();

    //     return $order;
    // }

    // 

    // public function createOrderForNoSession($user = NULL, $type, $krAnswer)
    // {
    //     $now = Carbon::now('America/Lima');

    //     $couponCode = Session::get('coupon', false);

    //     if ($couponCode) {
    //         $couponCode = Coupon::where('code', $couponCode)->first() ?? null;

    //         if ($couponCode && $couponCode->type === 'SINGULAR') {
    //             $couponCode->update([
    //                 'flg_used' => 1
    //             ]);
    //         }
    //     }

    //     if ($type === 'new') {
    //         $random = Str::random(8);
    //         $user = User::create([
    //             'name' => '-',
    //             'maternal' => '-',
    //             'paternal' => '-',
    //             'email' => collect(Session::get('stepEmail'))->get('email'),
    //             'password' => Hash::make($random),
    //             'role' => 'participants'
    //         ]);
    //         app(EmailService::class)->sendUserCredentialsMail($user, $random);
    //     }

    //     $order = $user->orders()->create([
    //         'status' => 'Cancelado',
    //         'order_date' => $now,
    //         'payment_type' => 'Izypay',
    //         'coupon_id' => $couponCode ? $couponCode->id : null,
    //         'uuid_transaction' => $krAnswer['transactions'][0]['uuid']
    //     ]);

    //     if ($type === 'new') {
    //         $order->update([
    //             'new' => 1
    //         ]);
    //     }

    //     session()->forget('coupon');
    //     $cart = collect(Session::get('cart', []));

    //     $planIds = $cart->pluck('buyable_id');
    //     $items = Plan::whereIn('id', $planIds)->get();


    //     foreach ($items as $cartItem) {

    //         $order->products()->create([
    //             'orderable_id'  => $cartItem->id,
    //             'orderable_type' => Plan::class,
    //             'quantity'      => 1,
    //             'unit_price'    => $cartItem->price,
    //         ]);

    //         // ---------------------------------------------------------------------------------------------
    //         // $course = $cartItem->course;
    //         // $certification = $course->productCertifications()->where('user_id', $user->id)->first();

    //         // if (!$certification) {
    //         //     $certification = $course->productCertifications()->create([
    //         //         'user_id' => $user->id,
    //         //         'status' => 'approved',
    //         //     ]);
    //         // }
    //         // ---------------------------------------------------------------------------------------------

    //         if ($cartItem->duration_type == 'months') {
    //             $endDate = $now->copy()->addMonths($cartItem->duration);
    //         } elseif ($cartItem->duration_type == 'days') {
    //             $endDate = $now->copy()->addDays($cartItem->duration);
    //         }

    //         $user->subscriptions()->create([
    //             'order_id' => $order->id,
    //             'subscriptable_id'  => $cartItem->id,
    //             'subscriptable_type' => Plan::class,
    //             'status' => 'active',
    //             'start_date' => $now,
    //             'end_date' => $endDate,
    //         ]);
    //     };

    //     session()->forget('cart');
    //     session()->forget('stepEmail');
    //     $user->cart()->delete();

    //     return $order;
    // }


    public function createOrderForNoSession($user = NULL, $type, $krAnswer)
    {
        $now = Carbon::now('America/Lima');

        $couponCode = Session::get('coupon', false);

        if ($couponCode) {
            $couponCode = Coupon::where('code', $couponCode)->first() ?? null;

            if ($couponCode && $couponCode->type === 'SINGULAR') {
                $couponCode->update([
                    'flg_used' => 1
                ]);
            }
        }

        if ($type === 'new') {
            $random = Str::random(8);
            $user = User::create([
                'name' => '-',
                'maternal' => '-',
                'paternal' => '-',
                'email' => collect(Session::get('stepEmail'))->get('email'),
                'password' => Hash::make($random),
                'role' => 'participants'
            ]);
            app(EmailService::class)->sendUserCredentialsMail($user, $random);
        }

        $order = $user->orders()->create([
            'status' => 'Cancelado',
            'order_date' => $now,
            'payment_type' => 'Izypay',
            'coupon_id' => $couponCode ? $couponCode->id : null,
            'uuid_transaction' => $krAnswer['transactions'][0]['uuid']
        ]);

        if ($type === 'new') {
            $order->update([
                'new' => 1
            ]);
        }

        session()->forget('coupon');
        $cart = collect(Session::get('cart', []));

        $planIds = $cart->pluck('buyable_id');
        $items = Plan::whereIn('id', $planIds)->with([
            'file' => fn($q) => $q->where('category', 'plans')->where('file_type', 'imagenes')
        ])->get();


        foreach ($items as $cartItem) {

            $order->products()->create([
                'orderable_id'  => $cartItem->id,
                'orderable_type' => Plan::class,
                'quantity'      => 1,
                'unit_price'    => $cartItem->price,
            ]);

            $course = $cartItem->course;

            $certification = $course->productCertifications()->where('user_id', $user->id)->first();

            if (!$certification) {
                $certification = $course->productCertifications()->create([
                    'user_id' => $user->id,
                    'status' => 'approved',
                ]);
            }

            if ($cartItem->duration_type == 'months') {
                $endDate = $now->copy()->addMonths($cartItem->duration);
            } elseif ($cartItem->duration_type == 'days') {
                $endDate = $now->copy()->addDays($cartItem->duration);
            }

            $user->subscriptions()->create([
                'order_id' => $order->id,
                'subscriptable_id'  => $cartItem->id,
                'subscriptable_type' => Plan::class,
                'status' => 'active',
                'start_date' => $now,
                'end_date' => $endDate,
                'certification_id' => $certification->id
            ]);
        };

        session()->forget('cart');
        session()->forget('stepEmail');
        $user->cart()->delete();

        return $order;
    }

    public function createOrderForSession($krAnswer)
    {
        $user = Auth::user();
        $user->load('cart');
        $now = Carbon::now('America/Lima');

        $couponCode = Session::get('coupon', false);

        if ($couponCode) {
            $couponCode = Coupon::where('code', $couponCode)->first() ?? null;

            if ($couponCode && $couponCode->type === 'SINGULAR') {
                $couponCode->update([
                    'flg_used' => 1
                ]);
            }
        }

        $order = $user->orders()->create([
            'status' => 'Cancelado',
            'order_date' => $now,
            'payment_type' => 'Izypay',
            'coupon_id' => $couponCode ? $couponCode->id : null,
            'uuid_transaction' => $krAnswer['transactions'][0]['uuid']
        ]);

        session()->forget('coupon');

        $cartItems = $user->cart;

        foreach ($cartItems as $cartItem) {

            $order->products()->create([
                'orderable_id'  => $cartItem->buyable_id,
                'orderable_type' => get_class($cartItem->buyable),
                'quantity'      => $cartItem->quantity,
                'unit_price'    => $cartItem->buyable->price,
            ]);

            $course = $cartItem->buyable->course;

            $certification = $course->productCertifications()->where('user_id', $user->id)->first();

            if (!$certification) {
                $certification = $course->productCertifications()->create([
                    'user_id' => $user->id,
                    'status' => 'approved',
                ]);
            }

            if ($cartItem->buyable->duration_type == 'months') {
                $endDate = $now->copy()->addMonths($cartItem->buyable->duration);
            } elseif ($cartItem->buyable->duration_type == 'days') {
                $endDate = $now->copy()->addDays($cartItem->buyable->duration);
            }

            $user->subscriptions()->create([
                'order_id' => $order->id,
                'subscriptable_id'  => $cartItem->buyable_id,
                'subscriptable_type' => get_class($cartItem->buyable),
                'status' => 'active',
                'start_date' => $now,
                'end_date' => $endDate,
                'certification_id' => $certification->id
            ]);
        };

        session()->forget('cart');
        session()->forget('stepEmail');
        $user->cart()->delete();

        return $order;
    }

    public function paid(Request $request)
    {

        if ($request->get('kr-hash-algorithm') !== 'sha256_hmac') {
            throw new \Exception('Invalid hash algorithm');
        }

        $krAnswer = str_replace('\/', '/', $request->get('kr-answer'));
        $calculateHash = hash_hmac('sha256', $krAnswer, config('services.izipay.hash_key'));

        if ($calculateHash !== $request->get('kr-hash')) {
            throw new \Exception('Invalid hash');
        }

        $krAnswer = collect(json_decode($krAnswer, true));

        if (Auth::check()) {
            $order = $this->createOrderForSession($krAnswer);
        } else {

            $email = collect(Session::get("stepEmail"))->get('email') ?? 'example@gmail.com';
            $user = User::where('email', $email)->first();

            if ($user) {
                $order = $this->createOrderForNoSession($user, 'old', $krAnswer);
            } else {
                $order = $this->createOrderForNoSession(NULL, 'new', $krAnswer);
            }
        }

        return redirect()->route('home.payment.view', ['order' => $order]);
    }

    public function viewPayment(Order $order)
    {
        $totalAmount = 0;

        $order->load('products');
        $totalAmount = $order->products->sum(fn($product) => $product->unit_price);
        $totalAmount = getAmountToOrder($totalAmount, $order);

        $config = Config::select('whatsapp_number')->first();

        return view('home.payment.paid-completed', compact('order', 'totalAmount', 'config'));
    }
}
