<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class CVGeneratorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $applicant = $user?->applicant;
        return view('tools.cv-generator', compact('applicant'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'template' => 'required|in:modern,professional,classic',
        ]);

        $data = $request->all();
        
        $pdf = PDF::loadView('pdfs.cv-template-' . $request->template, compact('data'));
        $pdf->setPaper('a4');
        
        return $pdf->download('CV_' . str_replace(' ', '_', $request->full_name) . '.pdf');
    }
}
