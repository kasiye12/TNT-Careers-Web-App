<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ResumeBuilderController extends Controller
{
    public function index()
    {
        return view('tools.resume-builder');
    }

    public function generate(Request $request)
    {
        $data = [
            'full_name' => $request->full_name ?? 'Your Name',
            'job_title' => $request->job_title ?? '',
            'email' => $request->email ?? '',
            'phone' => $request->phone ?? '',
            'location' => $request->location ?? '',
            'website' => $request->website ?? '',
            'summary' => $request->summary ?? '',
            'skills' => $request->skills ?? '',
            'languages' => $request->languages ?? '',
            'certifications' => $request->certifications ?? '',
            'experience' => $request->experience ?? [],
            'education' => $request->education ?? [],
            'featured_skills' => $request->featured_skills ?? [],
        ];
        
        $themeColor = $request->theme_color ?? '#2563eb';
        $fontFamily = $request->font_family ?? 'Inter';
        $fontSize = $request->font_size ?? 'standard';
        
        $pdf = PDF::loadView('pdfs.resume-template', compact('data', 'themeColor', 'fontFamily', 'fontSize'));
        $pdf->setPaper('a4');
        
        $filename = 'Resume_' . str_replace(' ', '_', $data['full_name']) . '.pdf';
        
        return $pdf->download($filename);
    }
}
