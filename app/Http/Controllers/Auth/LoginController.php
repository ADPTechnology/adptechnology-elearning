<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SliderImage;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Http\JsonResponse;
use App\Providers\RouteServiceProvider;
use App\Services\Auth\{AuthService};
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo;

    // Facebook
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            return $this->loginOrCreateUser($user, 'facebook');
        } catch (\Throwable $th) {
            return redirect()->route('login');
        }
    }

    // Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            return $this->loginOrCreateUser($user, 'google');
        } catch (\Throwable $th) {
            return redirect()->route('login');
        }
    }

    private function loginOrCreateUser($socialUser, $provider)
    {

        $user = User::where('email', $socialUser->getEmail())->first();
        $redirect = route('login');

        if (!$user) {

            $password = Str::random(8);

            $user = User::create([
                'email' => $socialUser->getEmail(),
                'name' => $socialUser->getName(),
                'role' => 'participants',
                'password' => Hash::make($password)
            ]);

            app(EmailService::class)->sendUserCredentialsMail($user, $password);
        }

        auth()->login($user);

        if ($user->role === 'admin' || $user->role === 'super_admin') {
            $redirect = route('admin.home.index');
        } elseif ($user->role === 'participants') {
            $redirect = route('aula.index');
        }

        return redirect($redirect);
    }

    public function showLoginForm()
    {
        $sliderImages = SliderImage::with('file')
            ->select('id', 'order', 'status', 'content')
            ->where('status', 1)
            ->orderBy('order', 'ASC')
            ->get();

        return view('auth.login', compact('sliderImages'));
    }

    public function redirectTo(Request $request)
    {
        // $redirect_route = $this->getRedirectRoute($request);

        switch (Auth::user()->role) {
            case 'admin':
            case 'super_admin':
            case 'technical_support':
                $this->redirectTo = route('admin.home.index');
                return $this->redirectTo;
                break;
            case 'participants':
            case 'instructor':
            case 'security_manager':
            case 'security_manager_admin':
            case 'supervisor':
                $this->redirectTo = route('aula.index');
                return $this->redirectTo;
                break;
            default:
                $this->redirectTo = '/login';
                return $this->redirectTo;
        }
    }
    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath(Request $request)
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo($request);
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended($this->redirectPath($request));
    }

    public function validateAttempt(AuthService $authService, Request $request)
    {
        parse_str($request['form'], $form);
        $formRequest = new Request($form);

        $message = NULL;

        try {
            $success = $authService->validateAttempt($formRequest);
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
        ]);
    }

    // public function getRedirectRoute(Request $request)
    // {
    //     if ($request->has('redirect_location')) {
    //         switch ($request['redirect_location'])
    //         {
    //             case 'classroom':
    //                 $redirect_route = route('aula.index');
    //                 break;
    //             case 'home' :
    //                 $redirect_route = route('home.principal');
    //                 break;
    //             default:
    //                 $redirect_route = route('home.principal');
    //         }
    //     }
    //     else {
    //         $redirect_route = route('home.index');
    //     }

    //     return $redirect_route;
    // }

    public function username()
    {
        return 'email';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
