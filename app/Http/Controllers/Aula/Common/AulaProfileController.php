<?php

namespace App\Http\Controllers\Aula\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class AulaProfileController extends Controller
{
    public function index()
    {
        return view('aula2.common.profile.index');
    }
}