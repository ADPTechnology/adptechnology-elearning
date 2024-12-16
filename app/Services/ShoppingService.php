<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Course;
use App\Models\Plan;
use App\Models\ShoppingCart;
use App\Models\User;
use Exception;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class ShoppingService
{

    public function getItems(Course $course)
    {
        $html = NULL;

        if (Auth::check()) {
            $user = Auth::user();
            $items = $user->cart()->with([
                'buyable',
                'buyable.file' => fn($q) => $q->where('category', 'plans')->where('file_type', 'imagenes')
            ])
                ->get();

            $totalPrice = $items->sum(fn($item) => $item->buyable->price);

            $html = view('home.shopping.partials._items', compact('items', 'course', 'totalPrice'))->render();
        } else {

            $cart = collect(Session::get('cart', []));
            $totalPrice = 0;

            if ($cart->isNotEmpty()) {
                $planIds = $cart->pluck('buyable_id');
                $items = Plan::whereIn('id', $planIds)->with([
                    'file' => fn($q) => $q->where('category', 'plans')->where('file_type', 'imagenes')
                ])->get();
                $totalPrice = $items->sum(fn($item) => $item->price);
            } else {
                $items = collect([]);
            }

            $html = view('home.shopping.partials._items_session', compact('items', 'totalPrice'))->render();
        }

        return $html;
    }

    public function getItemsFree()
    {

        $html = NULL;

        if (Auth::check()) {
            $user = Auth::user();
            $items = $user->cart()->with([
                'buyable',
                'buyable.file' => fn($q) => $q->where('category', 'plans')->where('file_type', 'imagenes')
            ])
                ->get();

            $totalPrice = $items->sum(fn($item) => $item->buyable->price);

            $html = view('home.shopping.partials._items_free', compact('items', 'totalPrice'))->render();
        } else {
            $cart = collect(Session::get('cart', []));
            $totalPrice = 0;

            if ($cart->isNotEmpty()) {
                $planIds = $cart->pluck('buyable_id');
                $items = Plan::whereIn('id', $planIds)->with([
                    'file' => fn($q) => $q->where('category', 'plans')->where('file_type', 'imagenes')
                ])->get();
                $totalPrice = $items->sum(fn($item) => $item->price);
            } else {
                $items = collect([]);
            }

            $html = view('home.shopping.partials._items_session', compact('items', 'totalPrice'))->render();
        }

        return $html;
    }

    public function store(Plan $plan)
    {

        $html = NULL;

        if (Auth::check()) {

            $user = Auth::user();

            $course = $plan->course;

            $existingPlanInCart = $user->cart()
                ->whereHas('buyable', function ($query) use ($course) {
                    $query->where('course_id', $course->id);
                })
                ->first();

            if ($existingPlanInCart) {
                $existingPlanInCart->delete();
            }

            $success = $plan->cart()->create([
                'user_id' => $user->id,
                'quantity' => 1,
            ]);

            $course->load([
                'plans' => fn($q) => $q->withCount([
                    'cart' => function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    }
                ]),
                'plans.cart',
                'plans.subscription' => fn($q) => $q->where('user_id', $user->id)

            ]);

            $plans = $course->plans;
            $html = view('home.freecourses.partials.boxes._plans', compact('plans', 'course'))->render();
        } else {

            $course = $plan->course;

            $cart = collect(session()->get('cart', []));

            $cart = $cart->filter(function ($cartItem) use ($course) {
                return Plan::find($cartItem['buyable_id'])->course_id !== $course->id;
            });

            $cart[$plan->id] = [
                'buyable_id' => $plan->id,
                'buyable_type' => Plan::class,
                'quantity' => 1,
            ];

            session()->put('cart', $cart);

            $plans = $course->plans;
            $html = view('home.freecourses.partials.boxes._plans_session', compact('plans', 'course'))->render();
        }

        return $html;
    }

    public function destroy(ShoppingCart $shoppingCart, Course $course)
    {
        $user = Auth::user();

        $success = $shoppingCart->delete();

        $items = $user->cart()->with([
            'buyable',
            'buyable.file' => fn($q) => $q->where('category', 'plans')
        ])
            ->get();

        $totalPrice = $items->sum(fn($item) => $item->buyable->price);

        $course->load([
            'plans' => fn($q) => $q->withCount([
                'cart' => function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                }
            ]),
            'plans.cart',
            'plans.subscription' => fn($q) => $q->where('user_id', $user->id)
        ]);

        $plans = $course->plans;

        $htmlItems = view('home.shopping.partials._items', compact('items', 'course', 'totalPrice'))->render();
        $htmlPlans = view('home.freecourses.partials.boxes._plans', compact('plans', 'course'))->render();

        return [$htmlItems, $htmlPlans];
    }

    public function destroySession(Plan $plan)
    {
        $html = NULL;
        $htmlItems = NULL;

        if (!Auth::check()) {

            $cart = collect(Session::get('cart', []));
            $totalPrice = 0;

            $updatedCart = $cart->reject(function ($item) use ($plan) {
                return $item['buyable_id'] == $plan->id;
            });

            Session::put('cart', $updatedCart);

            $cart = collect(Session::get('cart', []));

            if ($cart->isNotEmpty()) {
                $planIds = $cart->pluck('buyable_id');
                $items = Plan::whereIn('id', $planIds)->with([
                    'file' => fn($q) => $q->where('category', 'plans')->where('file_type', 'imagenes')
                ])->get();
            } else {
                $items = collect([]);
            }

            $course = $plan->course;
            $plans = $course->plans;
            $totalPrice = $items->sum(fn($item) => $item->price);

            $htmlItems = view('home.shopping.partials._items_session', compact('items', 'totalPrice'))->render();
            $html = view('home.freecourses.partials.boxes._plans_session', compact('plans', 'course'))->render();
        }

        return [$html, $htmlItems];
    }

    public function destroyFree(ShoppingCart $shoppingCart)
    {
        $user = Auth::user();

        $success = $shoppingCart->delete();

        $items = $user->cart()->with([
            'buyable',
            'buyable.file' => fn($q) => $q->where('category', 'plans')->where('file_type', 'imagenes')
        ])
            ->get();

        $totalPrice = $items->sum(fn($item) => $item->buyable->price);

        $htmlItems = view('home.shopping.partials._items_free', compact('items', 'totalPrice'))->render();

        return $htmlItems;
    }

    public function applyCoupon(Request $request)
    {
        $html = NULL;
        $totalPrice = 0;

        $result = $this->verifyCoupon($request);

        if (Auth::check()) {
            $user = Auth::user();
            $items = $user->cart()->with([
                'buyable',
                'buyable.file' => fn($q) => $q->where('category', 'plans')
            ])
                ->get();

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
        }

        $html = view('home.shopping.partials._buy', compact('totalPrice'))->render();

        return $html;
    }

    public function verifyCoupon(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();
        $couponCode = $data['coupon'];

        $coupon = Coupon::where('code', $couponCode)->first();

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

        session()->put('coupon', $couponCode);
    }

    public function deleteCoupon()
    {

        $totalPrice = 0;
        session()->forget('coupon');

        if (Auth::check()) {
            $user = Auth::user();
            $items = $user->cart()->with([
                'buyable',
                'buyable.file' => fn($q) => $q->where('category', 'plans')
            ])
                ->get();

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
        }

        $html = view('home.shopping.partials._buy', compact('totalPrice'))->render();

        return $html;
    }
}
