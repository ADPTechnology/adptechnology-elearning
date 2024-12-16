<?php

namespace App\Services;

use App\Models\{Coupon, Order};
use Exception;
use Auth;
use Carbon\Carbon;
use Datatables;
use Illuminate\Support\Str;

class HistoryService
{
    public function getDataTable()
    {
        $user = Auth::user();
        $query = $user->orders()->with('products');

        $orders = DataTables::of($query)
            ->editColumn('order_date', function ($order) {
                return $order->order_date;
            })
            ->editColumn('payment_type', function ($order) {

                return $order->payment_type;
            })
            ->addColumn('amount', function ($order) {
                return '$' .  getAmountToOrder($order->products->sum('unit_price'), $order);
            })
            ->editColumn('status', function ($order) {
                return $order->status;
            })
            ->addColumn('action', function ($order) {
                $btn = '<a href="' . route('aula.purchaseHistory.show', $order) . '" data-id="' .
                    $order->id . '"
                        data-original-title="edit" class="me-3 edit btn btn-primary btn-sm
                        editCoupon"><i class="fa-solid fa-eye"></i></a>';

                return $btn;
            })
            ->rawColumns(['amount', 'action'])
            ->make(true);

        return $orders;
    }

    public function getDataTableAdmin()
    {
        $query = Order::with([
            'products',
            'user'
        ]);

        $orders = DataTables::of($query)
            ->editColumn('user.name', function ($order) {
                return $order->user->full_name_complete;
            })
            ->editColumn('order_date', function ($order) {
                return $order->order_date;
            })
            ->editColumn('payment_type', function ($order) {

                return $order->payment_type;
            })
            ->addColumn('amount', function ($order) {
                return '$' .  getAmountToOrder($order->products->sum('unit_price'), $order);
            })
            ->editColumn('uuid_transaction', function ($order) {
                return $order->uuid_transaction ?? '-';
            })
            ->editColumn('status', function ($order) {
                return $order->status;
            })
            ->addColumn('action', function ($order) {
                $btn = '<a href="' . route('admin.purchaseHistory.show', $order) . '" data-id="' .
                    $order->id . '"
                        data-original-title="edit" class="me-3 edit btn btn-primary btn-sm
                        editCoupon"><i class="fa-solid fa-eye"></i></a>';

                return $btn;
            })
            ->rawColumns(['amount', 'action'])
            ->make(true);

        return $orders;
    }

    public function store($request)
    {
        $data = normalizeInputStatus($request->validated());

        if ($data['generation_type'] == 'AUTOMATIC') {
            $data['code'] = Str::random();
        }

        if ($coupon = Coupon::create($data)) {

            if ($data['especify_courses'] == 'S') {
                $courses_ids = $data['courses'];
                $coupon->coursesCoupons()->sync($courses_ids);
            }
            if ($data['especify_users'] == 'S') {
                $users_ids = $data['users'];
                $coupon->usersCoupons()->sync($users_ids);
            }

            return true;
        };

        throw new Exception('Ocurrio un error al realizar el registro');
    }

    public function update($request, Coupon $coupon)
    {
        $data = normalizeInputStatus($request->validated());

        if ($data['generation_type'] == 'AUTOMATIC') {
            $data['code'] = Str::random();
        }

        if ($coupon->update($data)) {

            if ($data['especify_courses'] == 'S') {
                $courses_ids = $data['courses'];
                $coupon->coursesCoupons()->sync($courses_ids);
            } else {
                $coupon->coursesCoupons()->detach();
            }

            if ($data['especify_users'] == 'S') {
                $users_ids = $data['users'];
                $coupon->usersCoupons()->sync($users_ids);
            } else {
                $coupon->usersCoupons()->detach();
            }

            return true;
        }

        throw new Exception('Ocurrio un error al realizar el registro');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->coursesCoupons()->detach();
        $coupon->usersCoupons()->detach();

        return $coupon->delete();
    }
}
