<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Mail\ComplaintsBookNotification;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TermsAndConditionsController extends Controller
{

    public function  privacyPolicies()
    {

        $config = Config::select('id', 'privacy_policies', 'email')->first();

        return view('home.documentation.privacy', compact('config'));
    }
    public function  termsConditions()
    {

        $config = Config::select('id', 'terms_conditions')->first();

        return view('home.documentation.terms', compact('config'));
    }

    public function dataDeletion()
    {
        $config = Config::select('id', 'data_deletion', 'email')->first();

        return view('home.documentation.data-deletion', compact('config'));
    }

    public function complaintsBook()
    {
        return view('home.documentation.complaints-book');
    }

    public function sendBookEmail(Request $request)
    {
        $success = false;
        $data = $request->all();
        $email = Config::select('id', 'email')->first()->email ?? 'inversionesfinancierasrqf@gmail.com';

        try {
            Mail::to($email)->send(new ComplaintsBookNotification($data));
            $success = true;
        } catch (\Throwable $th) {
            $success = false;
        }

        return response()->json([
            'success' => $success
        ]);
    }
}
