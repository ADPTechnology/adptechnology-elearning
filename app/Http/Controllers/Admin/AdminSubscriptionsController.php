<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\PlanService;
use App\Services\SubscriptionService;
use Exception;
use Illuminate\Http\Request;


class AdminSubscriptionsController extends Controller
{

    private $subscriptionService;

    public function __construct(SubscriptionService $service)
    {
        $this->subscriptionService = $service;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->subscriptionService->getDataTable();
        }

        $plans = Plan::all();

        return view('admin.subscriptions.index', compact('plans'));
    }


    public function getUsersList(Request $request)
    {
        if ($request->ajax()) {
            return $this->subscriptionService->getUsersList();
        }

        return redirect()->route('admin.subscriptions.index');
    }

    public function store(Request $request)
    {
        $emails = $request['users-selected'];
        $plan = (int) $request['plan'];

        try {
            $info = $this->subscriptionService->storeParticipantWithSubscription($emails, $plan);
        } catch (Exception $e) {
            $info = array("success" => true);
        }

        $message = getMessageFromSuccess($info['success'], 'stored');

        return response()->json([
            "success" => $info['success'],
            "message" => $message,
        ]);
    }


    public function destroy(Subscription $subscription)
    {

        try {
            $success = $this->subscriptionService->destroy($subscription);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'deleted');

        return response()->json([
            "success" => $success,
            "message" => $message,
        ]);
    }
}
