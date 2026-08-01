<?php

namespace App\Services;

use App\Models\Application;
use Barryvdh\DomPDF\Facade\Pdf;

class ApplicationPDFService
{
    public function generateHRMasterPDF(Application $application): string
    {
        $path = storage_path("app/private/applications/{$application->id}/");
        if (!is_dir($path)) mkdir($path, 0755, true);
        
        $pdf = Pdf::loadView('pdfs.hr-master-application', [
            'application' => $application,
            'applicant' => $application->applicant,
            'vacancy' => $application->vacancy,
            'ethiopianDate' => ['formatted_en' => now()->format('Y-m-d')],
            'workExperiences' => $application->applicant->workExperiences,
            'educationHistories' => $application->applicant->educationHistories,
        ]);
        
        $filepath = $path . "HR_Application_{$application->id}.pdf";
        $pdf->save($filepath);
        return $filepath;
    }

    public function generateOfferLetter(Application $application, array $data): string
    {
        $path = storage_path("app/private/offer-letters/");
        if (!is_dir($path)) mkdir($path, 0755, true);
        
        $pdf = Pdf::loadView('pdfs.offer-letter', array_merge($data, [
            'application' => $application,
            'ethiopianDate' => ['formatted_am' => now()->format('Y-m-d')],
        ]));
        
        $filepath = $path . "Offer_Letter_{$data['offer_reference_number']}.pdf";
        $pdf->save($filepath);
        return $filepath;
    }
}
