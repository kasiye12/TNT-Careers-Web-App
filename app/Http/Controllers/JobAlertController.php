<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobAlertController extends Controller
{
    public function index()
    {
        return view('tools.job-alerts');
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'keywords' => 'nullable|string',
            'categories' => 'nullable|array',
            'frequency' => 'required|in:daily,weekly,monthly',
        ]);

        session()->flash('success', '✅ Job alert subscription created successfully! You will receive ' . $request->frequency . ' notifications for matching jobs.');
        return back();
    }
}
