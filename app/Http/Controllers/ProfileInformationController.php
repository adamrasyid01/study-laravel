<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileInformationController extends Controller
{
    //
    public function __invoke($identifier){
        return view('profile',compact('identifier'));
    }
}
