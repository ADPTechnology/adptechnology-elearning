<?php

namespace App\Services;

use App\Models\{Coupon};
use App\Models\Advertising;
use Exception;
use Auth;
use Carbon\Carbon;
use Datatables;
use Illuminate\Support\Str;

class AdvertisingService
{
    public function getDataTable()
    {
        $query = Advertising::with([
            'coupon' => fn($q) => $q->withCount('orders'),
            'plan'
        ]);

        $allCoupons = DataTables::of($query)
            ->editColumn('text', function ($advertising) {
                return $advertising->text;
            })
            ->editColumn('plan', function ($advertising) {
                return $advertising->plan->title;
            })
            ->editColumn('type', function ($advertising) {

                $color = $advertising->coupon->type === 'MULTIPLE' ? 'info' : 'secondary';

                $label = '<span class="badge badge-pill badge-' . $color . '">' .
                    config('parameters.coupon_types')[$advertising->coupon->type]
                    . '</span>';

                return $label;
            })
            ->editColumn('amount', function ($advertising) {

                if ($advertising->coupon->amount_type == 'PERCENTAGE') {
                    $amount = $advertising->coupon->amount . ' %';
                } else {
                    $amount = '$' . $advertising->coupon->amount;
                }
                return $amount;
            })
            ->editColumn('active', function ($advertising) {
                return getStatusButton($advertising->active);
            })
            ->addColumn('action', function ($advertising) {
                $btn = '<button data-toggle="modal" data-id="' .
                    $advertising->id . '"
                        data-url="' . route('admin.advertisements.update', $advertising) . '"
                        data-send="' . route('admin.advertisements.edit', $advertising) . '"
                        data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                        editAdvertising"><i class="fa-solid fa-pen-to-square"></i></button>';

                if ($advertising->coupon->orders_count == 0) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $advertising->id . '" data-original-title="delete"
                        data-url="' . route('admin.advertisements.destroy', $advertising) . '" class="ms-3 edit btn btn-danger btn-sm
                        deleteAdvertising"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['type', 'active', 'action'])
            ->make(true);

        return $allCoupons;
    }

    public function store($request)
    {
        $storage = env('FILESYSTEM_DRIVER');

        $data = normalizeInputStatus($request->validated());
        $data['type_use'] = 'ADVERTISING';

        if ($data['generation_type'] == 'AUTOMATIC') {
            $data['code'] = Str::random();
        }

        $coupon = Coupon::create($data);

        if ($coupon) {

            if ($data['especify_users'] == 'S') {
                $users_ids = $data['users'];
                $coupon->usersCoupons()->sync($users_ids);
            }

            $advertising = $coupon->advertisements()->create([
                'text' => $data['text'],
                'plan_id' => $data['plan'],
                'active' => $data['active'],
            ]);

            if ($advertising && $request->hasFile('image')) {

                $file_type = 'imagenes';
                $category = 'advertisements';
                $file = $request->file('image');
                $belongsTo = 'advertisements';
                $relation = 'one_one';

                app(FileService::class)->store(
                    $advertising,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );

                return true;
            }
        };

        throw new Exception('Ocurrio un error al realizar el registro');
    }

    public function update($request, Advertising $advertising)
    {
        $storage = env('FILESYSTEM_DRIVER');
        $data = normalizeInputStatus($request->validated());

        if ($data['generation_type'] == 'AUTOMATIC') {
            $data['code'] = Str::random();
        }

        if ($advertising->update($data)) {

            $advertising->load('coupon');
            $coupon = $advertising->coupon;

            $coupon->update([
                'generation_type' => $data['generation_type'],
                'code' => $data['code'],
                'amount_type' => $data['amount_type'],
                'amount' => $data['amount'],
                'expired_date' => $data['expired_date'],
            ]);

            if ($data['especify_users'] == 'S') {
                $users_ids = $data['users'];
                $coupon->usersCoupons()->sync($users_ids);
            } else {
                $coupon->usersCoupons()->detach();
            }

            if ($request->hasFile('image')) {

                app(FileService::class)->destroy($advertising->file, $storage);

                $file_type = 'imagenes';
                $category = 'advertisements';
                $file = $request->file('image');
                $belongsTo = 'advertisements';
                $relation = 'one_one';

                app(FileService::class)->store(
                    $advertising,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }

            return true;
        }

        throw new Exception('Ocurrio un error al realizar el registro');
    }

    public function destroy(Advertising $advertising, $storage)
    {

        if (app(FileService::class)->destroy($advertising->file, $storage)) {

            $advertising->coupon()->delete();
            $isDeleted = $advertising->delete();

            if ($isDeleted) {
                return true;
            }
        };

        throw new Exception(config('parameters.exception_message'));
    }
}
