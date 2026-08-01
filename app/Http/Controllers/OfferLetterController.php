<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\OfferLetter;
use App\Services\ApplicationPDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class OfferLetterController extends Controller implements HasMiddleware
{
    protected $pdfService;

    public function __construct(ApplicationPDFService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
            new Middleware('role:admin,hr_manager', except: ['view', 'respond']),
        ];
    }

    public function generate(Application $application)
    {
        if ($application->status !== 'selected') {
            return back()->with('error', 'Offer letters can only be generated for selected candidates.');
        }

        return view('hr.offer-letters.generate', compact('application'));
    }

    public function store(Request $request, Application $application)
    {
        $validated = $request->validate([
            'position_title' => 'required|string|max:255',
            'department' => 'required|string|max:100',
            'duty_station' => 'required|string|max:255',
            'salary_amount' => 'required|numeric|min:0',
            'salary_currency' => 'required|string|size:3',
            'benefits' => 'nullable|string',
            'reporting_date' => 'required|date|after:today',
            'offer_expiry_date' => 'required|date|after:reporting_date',
        ]);

        $offerReferenceNumber = OfferLetter::generateReferenceNumber();

        $offerLetter = OfferLetter::create(array_merge($validated, [
            'application_id' => $application->id,
            'offer_reference_number' => $offerReferenceNumber,
            'status' => 'draft',
            'generated_by' => Auth::id(),
        ]));

        $this->pdfService->generateOfferLetter($application, array_merge($validated, [
            'offer_reference_number' => $offerReferenceNumber,
        ]));

        return redirect()->route('hr.offer-letters.preview', $offerLetter->id);
    }

    public function preview(OfferLetter $offerLetter)
    {
        return view('hr.offer-letters.preview', compact('offerLetter'));
    }

    public function send(OfferLetter $offerLetter)
    {
        $offerLetter->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return redirect()->route('applicant.applications.show', $offerLetter->application_id)
            ->with('success', 'Offer letter sent successfully.');
    }

    public function view(OfferLetter $offerLetter)
    {
        $pdfPath = storage_path("app/private/offer-letters/Offer_Letter_{$offerLetter->offer_reference_number}.pdf");

        if (!file_exists($pdfPath)) {
            abort(404, 'Offer letter PDF not found.');
        }

        return response()->file($pdfPath);
    }

    public function respond(Request $request, OfferLetter $offerLetter)
    {
        $request->validate([
            'response' => 'required|in:accepted,declined',
            'notes' => 'nullable|string',
        ]);

        $offerLetter->update([
            'status' => $request->response,
            'responded_at' => now(),
            'response_notes' => $request->notes,
        ]);

        $application = $offerLetter->application;
        $application->update([
            'status' => $request->response === 'accepted' ? 'selected' : 'rejected',
        ]);

        return back()->with('success', 'Your response has been recorded.');
    }
}
