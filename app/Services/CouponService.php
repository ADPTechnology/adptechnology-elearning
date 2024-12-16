<?php

namespace App\Services;

use App\Models\{Coupon};
use Exception;
use Auth;
use Carbon\Carbon;
use Datatables;
use Illuminate\Support\Str;

class CouponService
{
    public function getDataTable()
    {
        $query = Coupon::withCount('orders');

        $allCoupons = DataTables::of($query)
            ->editColumn('code', function ($coupon) {
                return $coupon->code;
            })
            ->editColumn('type', function ($coupon) {

                $color = $coupon->type == 'MULTIPLE' ? 'info' : 'secondary';

                $label = '<span class="badge badge-pill badge-' . $color . '">' .
                    config('parameters.coupon_types')[$coupon->type]
                    . '</span>';

                return $label;
            })
            ->editColumn('amount', function ($coupon) {

                if ($coupon->amount_type == 'PERCENTAGE') {
                    $amount = $coupon->amount . ' %';
                } else {
                    $amount = '$' . $coupon->amount;
                }
                return $amount;
            })
            ->editColumn('type_use', function ($coupon) {
                return config('parameters.type_coupon')[$coupon->type_use];
            })
            ->editColumn('active', function ($coupon) {
                return getStatusButton($coupon->active);
            })
            ->addColumn('action', function ($coupon) {
                $btn = '<button data-toggle="modal" data-id="' .
                    $coupon->id . '"
                        data-url="' . route('admin.coupons.update', $coupon) . '"
                        data-send="' . route('admin.coupons.edit', $coupon) . '"
                        data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                        editCoupon"><i class="fa-solid fa-pen-to-square"></i></button>';

                if ($coupon->orders_count === 0) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $coupon->id . '" data-original-title="delete"
                        data-url="' . route('admin.coupons.destroy', $coupon) . '" class="ms-3 edit btn btn-danger btn-sm
                        deleteCoupon"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['type', 'active', 'action'])
            ->make(true);

        return $allCoupons;
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
        $storage = env('FILESYSTEM_DRIVER');

        $coupon->coursesCoupons()->detach();
        $coupon->usersCoupons()->detach();

        if ($coupon->type_use === 'ADVERTISING') {

            $coupon->load([
                'advertisements',
                'advertisements.file' => fn($q) => $q->where('category', 'advertisements')
            ]);

            $ad = $coupon->advertisements;

            app(FileService::class)->destroy($ad->file, $storage);

            $ad->delete();
        }

        return $coupon->delete();
    }
}
