<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Mail\ContactNotification;
use App\Models\Config;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeAboutController extends Controller
{
    public function index()
    {
        $instructors = User::where('role', 'instructor')
            ->where('active', 'S')
            ->with([
                'file' => fn ($q) =>
                $q->where('category', 'avatars')
            ])->get();

        return view('home.about.index', compact('instructors'));
    }

    public function getInformationInstructor(User $instructor)
    {
        $instructor->userDetail()->firstOrCreate();

        $instructor->load([
            'userDetail',
            'file' => fn ($q) => $q->where('category', 'avatars')
        ]);

        $html = view('home.common.partials.boxes._information_box', compact('instructor'))->render();

        return response()->json([
            'instructor' => $instructor,
            'html' => $html
        ]);
    }

    public function contactUs()
    {
        return view('home.contact.index');
    }

    public function sendEmailContact(Request $request)
    {
        $data = $request->all();
        $config = Config::select('id', 'email')->first();
        Mail::to($config->email)->send(new ContactNotification($data['names'], $data['email'], $data['message']));

        return response()->json([
            'success' => true,
        ]);
    }
}
