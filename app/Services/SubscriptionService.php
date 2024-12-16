<?php

namespace App\Services;

use App\Models\{Coupon, Subscription, User};
use App\Models\Plan;
use Exception;
use Auth;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionService
{
    public function getDataTable()
    {
        $query = Subscription::with([
            'user:id,name,paternal,maternal',
            'subscriptable',
            'order',
            'order.products'
        ]);

        return DataTables::of($query)
            ->addColumn('subscriptable', function ($subscription) {
                return $subscription->subscriptable->title;
            })
            ->addColumn('total_amount', function ($subscription) {
                return "$" . getAmountToOrder($subscription->order->products->sum('unit_price'), $subscription->order);
            })
            ->addColumn('date_buy', function ($subscription) {
                return $subscription->order->created_at;
            })
            ->editColumn('user.name', function ($subscription) {
                return $subscription->user->full_name_complete;
            })
            ->addColumn('start_time', function ($subscription) {
                return $subscription->start_date;
            })
            ->addColumn('end_time', function ($subscription) {
                return $subscription->end_date;
            })
            ->addColumn('action', function ($subscription) {

                $btn = '<a href="javascript:void(0)" data-id="' .
                    $subscription->id . '" data-original-title="delete"
                                    data-url="' . route('admin.subscriptions.destroy', $subscription) . '" class="ms-3 edit btn btn-danger btn-sm
                                    deletePlan"><i class="fa-solid fa-trash-can"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function getUsersList()
    {
        $query = User::select('id', 'role', 'name', 'paternal', 'maternal', 'email')->where('role', 'participants');

        return DataTables::of($query)
            ->addColumn('choose', function ($user) {
                $checkbox = '<div class="custom-checkbox custom-control">
                                    <input type="checkbox" name="users-selected[]"
                                        class="custom-control-input checkbox-user-input" id="checkbox-' . $user->id . '" value="' . $user->email . '">
                                    <label for="checkbox-' . $user->id . '" class="custom-control-label checkbox-user-label">&nbsp;</label>
                            </div>';
                return $checkbox;
            })
            ->editColumn('user.name', function ($user) {
                return $user->full_name;
            })
            ->rawColumns(['choose'])
            ->make(true);
    }



    public function storeParticipantWithSubscription($emails, $plan)
    {
        $users = $this->getFilteredUsers($emails);

        $plan = Plan::find($plan);
        $this->getCertificationsArray($users, $plan);

        return array("success" => true);
    }

    public function getFilteredUsers($emails)
    {
        return User::whereIn('email', $emails)->get();
    }

    public function getCertificationsArray($users, $plan)
    {
        $now = Carbon::now('America/Lima');

        foreach ($users as $i => $user) {

            $order = $user->orders()->create([
                'status' => 'Cancelado',
                'order_date' => $now,
                'payment_type' => 'Izypay',
                'coupon_id' => NULL
            ]);

            $order->products()->create([
                'orderable_id'  => $plan->id,
                'orderable_type' => Plan::class,
                'quantity'      => 1,
                'unit_price'    => $plan->price,
            ]);

            if ($plan->duration_type == 'months') {
                $endDate = $now->copy()->addMonths($plan->duration);
            } elseif ($plan->duration_type == 'days') {
                $endDate = $now->copy()->addDays($plan->duration);
            }

            $user->subscriptions()->create([
                'order_id' => $order->id,
                'subscriptable_id'  => $plan->id,
                'subscriptable_type' => Plan::class,
                'status' => 'active',
                'start_date' => $now,
                'end_date' => $endDate,
            ]);
        }
    }

    public function destroy(Subscription $subscription)
    {
        if ($subscription) {
            return $subscription->delete();
        };

        throw new Exception(config('parameters.exception_message'));
    }
}
