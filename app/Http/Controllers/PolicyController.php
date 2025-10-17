<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PolicyController extends Controller
{
    /**
     * Display the privacy policy page
     */
    public function privacy()
    {
        return view('policies.privacy');
    }

    /**
     * Display the terms of service page
     */
    public function terms()
    {
        return view('policies.terms');
    }

    /**
     * Display the data safety information page
     */
    public function dataSafety()
    {
        return view('policies.data-safety');
    }

    /**
     * Display the app information page
     */
    public function appInfo()
    {
        return view('policies.app-info');
    }
}
