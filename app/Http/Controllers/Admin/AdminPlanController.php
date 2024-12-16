<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Plan;
use App\Services\PlanService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminPlanController extends Controller
{
    private $planService;

    public function __construct(PlanService $service)
    {
        $this->planService = $service;
    }

    public function index(Course $course, Request $request)
    {
        if ($request->ajax()) {
            return $this->planService->getDataTable($course);
        }
    }

    public function store(Course $course, Request $request)
    {
        $storage = env('FILESYSTEM_DRIVER');
        try {
            $success = $this->planService->store($request, $course, $storage);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        return response()->json([
            "success" => $success,
            "message" => $message,
        ]);
    }

    public function edit(Plan $plan)
    {
        $plan->load([
            'file' => fn($q) => $q->where('category', 'plans'),
        ]);

        $urlImage = verifyImage($plan->file);

        return response()->json([
            "plan" => $plan,
            'url_img' => $urlImage,
        ]);
    }

    public function update(Request $request, Plan $plan)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $success = $this->planService->update($request, $plan, $storage);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        return response()->json([
            "success" => $success,
            "message" => $message
        ]);
    }

    public function destroy(Plan $plan)
    {

        $success = true;
        $message = null;

        $storage = env('FILESYSTEM_DRIVER');

        try {
            $this->planService->destroy($plan, $storage);
            $message = config('parameters.deleted_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return response()->json([
            "success" => $success,
            "message" => $message
        ]);
    }
}
