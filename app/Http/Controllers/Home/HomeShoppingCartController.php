<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Plan;
use App\Models\ShoppingCart;
use App\Models\User;
use App\Services\ShoppingService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeShoppingCartController extends Controller
{

    private $shoppingService;

    public function __construct(ShoppingService $service)
    {
        $this->shoppingService = $service;
    }

    public function index(Course $course)
    {
        $html = NULL;
        $success = false;

        try {
            $html = $this->shoppingService->getItems($course);
            $success = true;
        } catch (\Throwable $e) {
            $success = false;
        }

        return response()->json([
            'success' => $success,
            'html' => $html,
        ]);
    }

    public function free()
    {
        $html = NULL;
        $success = false;

        try {
            $html = $this->shoppingService->getItemsFree();
            $success = true;
        } catch (\Throwable $e) {
            $success = false;
        }

        return response()->json([
            'success' => $success,
            'html' => $html,
        ]);
    }

    public function store(Plan $plan)
    {
        $html = NULL;
        $success = false;

        try {
            $html = $this->shoppingService->store($plan);
            $success = true;
        } catch (\Throwable $e) {
            $success = false;
        }

        return response()->json([
            'success' => $success,
            'html' => $html
        ]);
    }

    public function destroy(ShoppingCart $shoppingCart, Course $course)
    {
        $htmlItems = NULL;
        $htmlPlans = NULL;
        $success = false;

        try {
            [$htmlItems, $htmlPlans] = $this->shoppingService->destroy($shoppingCart, $course);
            $success = true;
        } catch (\Throwable $e) {
            $success = false;
        }

        return response()->json([
            'html' => $htmlPlans,
            'htmlItems' => $htmlItems,
            'success' => $success
        ]);
    }

    public function destroyFree(ShoppingCart $shoppingCart)
    {
        $htmlItems = NULL;
        $success = false;

        try {
            $htmlItems = $this->shoppingService->destroyFree($shoppingCart);
            $success = true;
        } catch (\Throwable $e) {
            $success = false;
        }

        return response()->json([
            'html' => null,
            'htmlItems' => $htmlItems,
            'success' => $success
        ]);
    }

    public function destroySession(Plan $plan)
    {
        $htmlPlans = NULL;
        $htmlItems = NULL;
        $success = false;

        try {
            [$htmlPlans, $htmlItems] = $this->shoppingService->destroySession($plan);
            $success = true;
        } catch (\Throwable $e) {
            $success = false;
        }

        return response()->json([
            'html' => $htmlPlans,
            'htmlItems' => $htmlItems,
            'success' => $success
        ]);
    }    

    public function applyCoupon(Request $request)
    {

        $html = NULL;
        $success = false;

        try {
            $html = $this->shoppingService->applyCoupon($request);
            $success = true;
        } catch (\Throwable $e) {
            $success = false;
        }

        return response()->json([
            'html' => $html,
            'success' => $success
        ]);
    }

    public function deleteCoupon()
    {

        $html = NULL;
        $success = false;

        try {
            $html = $this->shoppingService->deleteCoupon();
            $success = true;
        } catch (\Throwable $e) {
            $success = false;
        }

        return response()->json([
            'success' => $success,
            'html' => $html
        ]);
    }

    public function orderStore()
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
            'coupon_id' => $couponCode ? $couponCode->id : null
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

        $success = $user->cart()->delete();
        $html = view('home.shopping.partials._empty')->render();

        return response()->json([
            'success' => $success,
            'html' => $html,
            'href' => route('aula.purchaseHistory.show', $order)
        ]);
    }
}
