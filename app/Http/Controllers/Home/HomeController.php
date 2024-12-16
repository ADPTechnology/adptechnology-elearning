<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\{Advertising, Company, Course, MiningUnit, Publishing, User};
use App\Models\Plan;
use App\Services\Home\{HomeCourseService};
use App\Services\{CourseCategoryService, FreeCourseService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    protected $courseCategoryService;
    protected $homeCourseService;

    public function __construct(CourseCategoryService $courseCategoryService, HomeCourseService $homeCourseService)
    {
        $this->courseCategoryService = $courseCategoryService;
        $this->homeCourseService = $homeCourseService;
    }

    public function index(Request $request)
    {
        // $courses = $this->homeCourseService->getAvailableCourses();
        // $instructors = User::where('role', 'instructor')
        //     ->where('active', 'S')
        //     ->with([
        //         'file' => fn($q) =>
        //             $q->where('category', 'avatars')
        //     ])->get();

        $freeCourses = app(FreeCourseService::class)->withFreeCourseRelationshipsQuery()
            ->where('active', 'S')
            ->having('course_chapters_count', '>', 0)
            ->get();

        $categories = $this->courseCategoryService->withCategoryRelationshipsQuery()
            ->where('status', 'S')
            ->get();

        $banners = Publishing::where('type', 'PRINCIPALBANNER')
            ->with('file')
            ->orderBy('publishing_order', 'ASC')
            ->get();

        $numberUsers = User::where('role', 'participants')->count();
        $numberCourses = Course::count();
        // $numberCompanys = Company::count();

        $testimonials = User::where('role', 'participants')->where('testimony', '!=', null)->with([
            'file' => fn($q) => $q->where('category', 'avatars')
        ])->get();

        return view(
            'home.home',
            compact(
                'freeCourses',
                'categories',
                'numberUsers',
                'numberCourses',
                'banners',
                'testimonials'
            )
        );
    }

    public function getRegisterModalContent($place)
    {
        // $miningUnits = MiningUnit::get(['id', 'description']);
        // $companies = Company::where('active', 'S')->get(['id', 'description']);

        if ($place == 'external') {

            return response()->json([
                "html" => view('home.common.partials.boxes._login_register_external')->render()
            ]);
        } elseif ($place == 'shopping') {
            return response()->json([
                "html" => view('home.common.partials.boxes._login_register_shopping')->render()
            ]);
        }

        return response()->json([
            "html" => view('home.common.partials.boxes._login_register')->render()
        ]);
    }


    public function information(Course $course)
    {

        $course->load([
            'freecourseDetail',
            'file' => fn($query) =>
            $query->where('file_type', 'imagenes')
            ->where('category', 'cursoslibres'),
            // 'plans' => fn ($q) => $q->withCount([
            //     'cart' => function ($q) use ($user) {
            //         $q->where('user_id', $user->id);
            //     }
            // ]),
            // 'plans.cart'
        ]);

        // $plans = $course->plans;

        if (Auth::check()) {
            $user = Auth::user();
            $course->load([
                'plans' => fn($q) => $q->withCount([
                    'cart' => function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    }
                ]),
                'plans.cart',
                'plans.subscription' => fn($q) => $q->where('user_id', $user->id)
            ]);
        } else {
            $course->load([
                'plans',
                'plans.cart'
            ]);
        }

        $plans = $course->plans;

        return view('home.freecourses.information', compact('course', 'plans'));
    }
}
