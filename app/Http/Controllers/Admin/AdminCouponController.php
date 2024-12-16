<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\{Coupon, Course, User};
use Illuminate\Http\Request;

use App\Services\{CouponService};
use Exception;

class AdminCouponController extends Controller
{
    private $couponService;

    public function __construct(CouponService $service)
    {
        $this->couponService = $service;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->couponService->getDataTable();
        }

        return view('admin.coupons.index');
    }

    public function create(Request $request)
    {
        if ($request['part'] == "courses") {

            $courses = Course::where('course_type', 'FREE')
                                ->where('active', 'S')
                                ->select('id', 'description')->get();

            $html = view('admin.coupons.partials.components._select_courses', compact('courses'))->render();

        }
        else if ($request['part'] == "users") {

            $users = User::where('role', 'participants')
                        ->where('active', 'S')
                        ->select('id', 'name', 'paternal', 'email')->get();

            $html = view('admin.coupons.partials.components._select_users', compact('users'))->render();
        }

        return response()->json([
            "html" => $html ?? null
        ]);
    }

    public function store(CouponRequest $request)
    {
        try {
            $success = $this->couponService->store($request);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        return response()->json([
            "success" => $success,
            "message" => $message,
        ]);
    }

    public function edit(Request $request, Coupon $coupon)
    {
        if ($request['part'] == 'all') {

            $coupon->load([
                'coursesCoupons' => fn ($q) =>
                    $q->select('courses.id'),
                'usersCoupons'=> fn ($q) =>
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
                "route" => $route ?? null,
                "htmlSelectCourse" => $htmlSelectCourse ?? null,
                "htmlSelectUsers" => $htmlSelectUsers ?? null,
                "coursesIds" => $coupon->coursesCoupons->pluck('id')->toArray() ?? null,
                "usersIds" => $coupon->usersCoupons->pluck('id')->toArray() ?? null
            ]);

        }
        else if ($request['part'] == 'code') {

            return response()->json([
                "code" => $coupon->code
            ]);
        }
        else if ($request['part'] == 'courses') {

            $coupon->load([
                'coursesCoupons' => fn ($q) =>
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
        else if ($request['part'] == 'users') {

            $coupon->load([
                'usersCoupons' => fn ($q) =>
                    $q->select('users.id')
            ]);

            $users = User::where('role', 'participants')
                        ->where('active', 'S')
                        ->select('id', 'name', 'paternal', 'email')->get();

            $htmlSelectUsers = view('admin.coupons.partials.components._select_users', compact('users'))->render();

            return response()->json([
                "html" => $htmlSelectUsers ?? null,
                "usersIds" => $coupon->usersCoupons->pluck('id')->toArray() ?? null,
            ]);
        }

    }

    public function update(CouponRequest $request, Coupon $coupon)
    {
        try {
            $success = $this->couponService->update($request, $coupon);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        return response()->json([
            "success" => $success,
            "message" => $message,
        ]);
    }

    public function destroy(Coupon $coupon)
    {
        try {
            $success = $this->couponService->destroy($coupon);
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
