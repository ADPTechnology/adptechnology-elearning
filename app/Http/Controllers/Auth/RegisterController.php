<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserExternalRequest;
use App\Http\Requests\UserSelfRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserService;
use Exception;

class RegisterController extends Controller
{
    private $userService;

    public function __construct(UserService $service)
    {
        $this->userService = $service;
    }

    public function showRegistrationForm(Request $request, $redirect = NULL)
    {
        return view('auth.register',
            compact(
                'redirect'
            )
        );
    }

    public function validateEmail(Request $request)
    {
        $valid = User::where('email', $request['email'])->first() == null ? "true" : "false";

        return $valid;
    }

    public function register(UserSelfRequest $request, $location = NULL, $redirect = NULL)
    {
        $html = NULL;
        $success = false;
        $message = config('parameters.exception_message');

        $data = $request->validated();

        $email = $data['email'];

        try {
            $this->userService->selfRegister($request);
            $success = true;
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        if ($success) {

            if ($request['location'] == 'modal') {
                $html = view('home.common.partials.boxes._register_success_message', compact('email'))->render();
            } else {
                $html = view('auth.partials.boxes._register_success_message', compact('email'))->render();
            }
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html
        ]);
    }


    public function registerExternal(UserExternalRequest $request, $location = NULL, $redirect = NULL)
    {
        $html = NULL;
        $success = false;
        $message = config('parameters.exception_message');

        $data = $request->validated();

        $email = $data['email'];

        try {
            $this->userService->externalSelfRegister($request);
            $success = true;
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        if ($success) {

            if ($request['location'] == 'modal') {
                $html = view('home.common.partials.boxes._register_success_message', compact('email'))->render();
            } else {
                $html = view('auth.partials.boxes._register_success_message', compact('email'))->render();
            }
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html
        ]);


    }

}
