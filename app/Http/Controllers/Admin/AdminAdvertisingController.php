<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdvertisingRequest;
use App\Http\Requests\AdvertisingUpdateRequest;
use App\Models\Advertising;
use App\Models\Course;
use App\Models\Plan;
use App\Models\User;
use App\Services\AdvertisingService;
use Exception;
use Illuminate\Http\Request;

class AdminAdvertisingController extends Controller
{

    private $advertisingService;

    public function __construct(AdvertisingService $service)
    {
        $this->advertisingService = $service;
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            return $this->advertisingService->getDataTable();
        }

        $plans = Plan::all();

        return view('admin.advertising.index', compact('plans'));
    }

    public function store(AdvertisingRequest $request)
    {
        $success = false;

        try {
            $success = $this->advertisingService->store($request);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        return response()->json([
            "success" => $success,
            "message" => $message,
        ]);
    }

    public function edit(Request $request, Advertising $advertising)
    {
        $advertising->load([
            'coupon',
            'file' => fn($q) => $q->where('category', 'advertisements')
        ]);

        $coupon = $advertising->coupon;

        if ($request['part'] == 'all') {

            $coupon->load([
                'coursesCoupons' => fn($q) =>
                $q->select('courses.id'),
                'usersCoupons' => fn($q) =>
                $q->select('users.id'),
            ]);

            if ($coupon->especify_courses == 'S') {

                $courses = Course::where('course_type', 'FREE')
                    ->where('active', 'S')
                    ->select('id', 'description')->get();

                $htmlSelectCourse = view('admin.coupons.partials.components._select_courses', compact('courses'))->render();
            }
            if ($coupon->especify_users == 'S') {

                $users = User::where('role', 'participants')
                    ->where('active', 'S')
                    ->select('id', 'name', 'paternal', 'email')->get();

                $htmlSelectUsers = view('admin.coupons.partials.components._select_users', compact('users'))->render();
            }

            $route = route('admin.coupons.edit', $coupon);

            return response()->json([
                "coupon" => $coupon,
                "advertising" => $advertising,
                "url_img" => verifyFile($advertising->file),
                "route" => $route ?? null,
                "htmlSelectCourse" => $htmlSelectCourse ?? null,
                "htmlSelectUsers" => $htmlSelectUsers ?? null,
                "coursesIds" => $coupon->coursesCoupons->pluck('id')->toArray() ?? null,
                "usersIds" => $coupon->usersCoupons->pluck('id')->toArray() ?? null
            ]);
        } else if ($request['part'] == 'code') {

            return response()->json([
                "code" => $coupon->code
            ]);
        } else if ($request['part'] == 'courses') {

            $coupon->load([
                'coursesCoupons' => fn($q) =>
                $q->select('courses.id')
            ]);

            $courses = Course::where('course_type', 'FREE')
                ->where('active', 'S')
                ->select('id', 'description')->get();

            $htmlSelectCourse = view('admin.coupons.partials.components._select_courses', compact('courses'))->render();

            return response()->json([
                "html" => $htmlSelectCourse ?? null,
                "coursesIds" => $coupon->coursesCoupons->pluck('id')->toArray() ?? null,
            ]);
        }
    }

    public function update(AdvertisingUpdateRequest $request, Advertising $advertising)
    {
        try {
            $success = $this->advertisingService->update($request, $advertising);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        return response()->json([
            "success" => $success,
            "message" => $message,
        ]);
    }

    public function destroy(Advertising $advertising)
    {

        $success = true;
        $message = null;

        $storage = env('FILESYSTEM_DRIVER');

        try {
            $this->advertisingService->destroy($advertising, $storage); 
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
