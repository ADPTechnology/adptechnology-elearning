<?php

namespace App\Services;

use App\Models\{Coupon, Course};
use App\Models\Plan;
use Exception;
use Auth;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanService
{
    public function getDataTable(Course $course)
    {
        $query = $course->plans()->withCount('item');

        return DataTables::of($query)
            ->editColumn('title', function ($plan) {
                return $plan->title;
            })
            ->editColumn('price', function ($plan) {
                return "\${$plan->price}";
            })
            ->editColumn('duration_type', function ($plan) {
                return getTypeDuration($plan->duration_type);
            })
            ->editColumn('duration', function ($plan) {
                return $plan->duration;
            })
            ->addcolumn('recom', function ($plan) {
                return getStatusRecomended($plan->flg_recom);
            })
            ->addColumn('action', function ($plan) {

                $btn = '<button data-toggle="modal" data-id="' .
                    $plan->id . '"
                        data-url="' . route('admin.plans.update', $plan) . '"
                        data-send="' . route('admin.plans.edit', $plan) . '"
                        data-original-title="edit" class="me-3 edit btn btn-warning btn-sm
                        editPlan"><i class="fa-solid fa-pen-to-square"></i></button>';

                if ($plan->item_count === 0) {
                    $btn .= '<a href="javascript:void(0)" data-id="' .
                        $plan->id . '" data-original-title="delete"
                                    data-url="' . route('admin.plans.destroy', $plan) . '" class="ms-3 edit btn btn-danger btn-sm
                                    deletePlan"><i class="fa-solid fa-trash-can"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['recom', 'action'])
            ->make(true);
    }

    public function store(Request $request, Course $course, $storage)
    {
        $data = normalizeInputStatus($request->all());

        $data['course_id'] = $course->id;

        $plan = Plan::create($data);

        if ($plan) {
            if ($request->hasFile('image')) {

                $file_type = 'imagenes';
                $category = 'plans';
                $file = $request->file('image');
                $belongsTo = 'plans';
                $relation = 'one_one';

                app(FileService::class)->store(
                    $plan,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }

            return $plan;
        }

        throw new Exception('Ocurrio un error al realizar el registro');
    }

    public function update($request, Plan $plan, $storage)
    {
        $data = normalizeInputStatus($request->all());

        $planUpdated = $plan->update($data);

        if ($planUpdated) {
            if ($request->hasFile('image')) {

                app(FileService::class)->destroy($plan->file, $storage);

                $file_type = 'imagenes';
                $category = 'plans';
                $file = $request->file('image');
                $belongsTo = 'plans';
                $relation = 'one_one';

                app(FileService::class)->store(
                    $plan,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }

            return $planUpdated;
        }


        throw new Exception('Ocurrio un error al realizar el registro');
    }

    public function destroy(Plan $plan, $storage)
    {

        if (app(FileService::class)->destroy($plan->file, $storage)) {

            $plan->cart()->delete();
            $isDeleted = $plan->delete();

            if ($isDeleted) {
                return true;
            }
        };

        throw new Exception(config('parameters.exception_message'));
    }
}
