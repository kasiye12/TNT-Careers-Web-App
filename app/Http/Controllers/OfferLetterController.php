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
            new Middleware('role:admin,hr_manager'),
        ];
    }

    /**
     * Show offer letter generation form
     */
    public function generate(Application $application)
    {
        if ($application->status !== 'selected') {
            return back()->with('error', 'Offer letters can only be generated for selected candidates.');
        }

        return view('hr.offer-letters.generate', compact('application'));
    }

    /**
     * Store and generate offer letter PDF
     */
    public function store(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'position_title' => 'required|string|max:255',
            'department' => 'required|string|max:100',
            'duty_station' => 'required|string|max:255',
            'salary_amount' => 'required|numeric|min:0',
            'salary_currency' => 'required|string|size:3',
            'benefits' => 'nullable|string',
            'reporting_date' => 'required|date|after:today',
            'offer_expiry_date' => 'required|date|after:reporting_date',
        ]);

        $application = Application::findOrFail($request->application_id);
        
        $offerReferenceNumber = 'TNT-OFFER-' . date('Y') . '-' . str_pad(OfferLetter::count() + 1, 4, '0', STR_PAD_LEFT);

        $offerLetter = OfferLetter::create([
            'application_id' => $application->id,
            'offer_reference_number' => $offerReferenceNumber,
            'position_title' => $request->position_title,
            'department' => $request->department,
            'duty_station' => $request->duty_station,
            'salary_amount' => $request->salary_amount,
            'salary_currency' => $request->salary_currency,
            'benefits' => $request->benefits,
            'reporting_date' => $request->reporting_date,
            'offer_expiry_date' => $request->offer_expiry_date,
            'status' => 'draft',
            'generated_by' => Auth::id(),
        ]);

        // Generate PDF
        try {
            $this->pdfService->generateOfferLetter($application, $offerLetter->toArray());
        } catch (\Exception $e) {
            // Continue even if PDF generation fails
        }

        return redirect()->route('hr.offer-letters.preview', $offerLetter->id)
            ->with('success', '✅ Offer letter generated!');
    }

    /**
     * Preview offer letter
     */
    public function preview(OfferLetter $offerLetter)
    {
        return view('hr.offer-letters.preview', compact('offerLetter'));
    }

    /**
     * Send offer letter to candidate
     */
    public function send(OfferLetter $offerLetter)
    {
        $offerLetter->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return back()->with('success', '✅ Offer letter sent to candidate!');
    }

    /**
     * View offer letter PDF
     */
    public function view(OfferLetter $offerLetter)
    {
        $pdfPath = storage_path('app/private/offer-letters/Offer_Letter_' . $offerLetter->offer_reference_number . '.pdf');
        
        if (!file_exists($pdfPath)) {
            // Try to regenerate
            try {
                $this->pdfService->generateOfferLetter($offerLetter->application, $offerLetter->toArray());
            } catch (\Exception $e) {
                abort(404, 'Offer letter PDF not found.');
            }
        }

        if (!file_exists($pdfPath)) {
            abort(404, 'Offer letter PDF not found.');
        }

        return response()->file($pdfPath);
    }

    /**
     * Candidate responds to offer
     */
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

        return back()->with('success', 'Your response has been recorded.');
    }
}
