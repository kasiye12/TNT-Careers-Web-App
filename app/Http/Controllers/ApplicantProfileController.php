<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\EducationHistory;
use App\Models\WorkExperience;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ApplicantProfileController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('auth')];
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->applicant) return redirect()->route('applicant.profile.edit');
        $regions = $this->getRegions();
        return view('applicant.profile.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name_am' => 'nullable|string|max:100',
            'father_name_am' => 'nullable|string|max:100',
            'grand_father_name_am' => 'nullable|string|max:100',
            'first_name_en' => 'required|string|max:100',
            'father_name_en' => 'required|string|max:100',
            'grand_father_name_en' => 'required|string|max:100',
            'gender' => 'required|in:male,female',
            'dob' => 'required|date',
            'nationality' => 'required|string|max:100',
            'region' => 'required|string|max:100',
            'zone_subcity' => 'required|string|max:100',
            'woreda' => 'required|string|max:50',
            'kebele_house_no' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
        ]);
        $validated['user_id'] = Auth::id();
        $validated['profile_completed'] = false;
        Applicant::create($validated);
        return redirect()->route('applicant.education.create')->with('success', 'Personal info saved!');
    }

    public function edit()
    {
        $user = Auth::user();
        $applicant = $user->applicant;
        if (!$applicant) return redirect()->route('applicant.profile.create');
        $regions = $this->getRegions();
        return view('applicant.profile.edit', compact('applicant', 'regions'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'first_name_am' => 'nullable|string|max:100',
            'father_name_am' => 'nullable|string|max:100',
            'grand_father_name_am' => 'nullable|string|max:100',
            'first_name_en' => 'required|string|max:100',
            'father_name_en' => 'required|string|max:100',
            'grand_father_name_en' => 'required|string|max:100',
            'gender' => 'required|in:male,female',
            'dob' => 'required|date',
            'nationality' => 'required|string|max:100',
            'region' => 'required|string|max:100',
            'zone_subcity' => 'required|string|max:100',
            'woreda' => 'required|string|max:50',
            'kebele_house_no' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
        ]);
        Auth::user()->applicant->update($validated);
        return back()->with('success', 'Profile updated.');
    }

    // Skills
    public function editSkills()
    {
        $applicant = Auth::user()->applicant;
        if (!$applicant) return redirect()->route('applicant.profile.create');
        return view('applicant.skills.edit', compact('applicant'));
    }

    public function updateSkills(Request $request)
    {
        $validated = $request->validate([
            'skills' => 'nullable|string|max:2000',
            'languages' => 'nullable|string|max:500',
            'certifications' => 'nullable|string|max:2000',
            'linkedin_url' => 'nullable|url|max:255',
            'professional_title' => 'nullable|string|max:255',
        ]);
        Auth::user()->applicant->update($validated);
        return back()->with('success', 'Skills updated!');
    }

    // Education
    public function addEducation() { return view('applicant.education.create'); }

    public function storeEducation(Request $request)
    {
        $validated = $request->validate([
            'institution' => 'required|string|max:255',
            'qualification' => 'required|string',
            'field_of_study' => 'required|string|max:255',
            'cgpa' => 'nullable|numeric|min:0|max:4.00',
            'graduation_year' => 'required|integer|min:1950|max:'.(date('Y')+1),
            'certificate' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);
        $applicant = Auth::user()->applicant;
        $validated['applicant_id'] = $applicant->id;
        if ($request->hasFile('certificate')) {
            $validated['certificate_file_path'] = $request->file('certificate')->store('private/documents/degrees', 'local');
        }
        EducationHistory::create($validated);
        return back()->with('success', 'Education added!');
    }

    public function editEducation(EducationHistory $education) { return view('applicant.education.edit', compact('education')); }

    public function updateEducation(Request $request, EducationHistory $education)
    {
        $education->update($request->validate([
            'institution' => 'required|string|max:255',
            'qualification' => 'required|string',
            'field_of_study' => 'required|string|max:255',
            'cgpa' => 'nullable|numeric',
            'graduation_year' => 'required|integer',
        ]));
        return redirect()->route('applicant.education.create')->with('success', 'Education updated!');
    }

    public function deleteEducation(EducationHistory $education)
    {
        $education->delete();
        return back()->with('success', 'Education record deleted.');
    }

    // Experience
    public function addExperience() { return view('applicant.experience.create'); }

    public function storeExperience(Request $request)
    {
        $validated = $request->validate([
            'organization_name' => 'required|string|max:255',
            'sector' => 'required|string',
            'position_held' => 'required|string|max:255',
            'from_date' => 'required|date',
            'to_date' => 'nullable|date',
            'is_current' => 'boolean',
            'is_construction_company' => 'boolean',
            'project_type' => 'nullable|string',
            'key_responsibilities' => 'nullable|string',
            'reason_for_leaving' => 'nullable|string',
            'experience_letter' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);
        $applicant = Auth::user()->applicant;
        $validated['applicant_id'] = $applicant->id;
        if ($request->hasFile('experience_letter')) {
            $validated['experience_letter_path'] = $request->file('experience_letter')->store('private/documents/experience_letters', 'local');
        }
        WorkExperience::create($validated);
        $applicant->update([
            'has_construction_exp' => $applicant->hasConstructionExperience(),
            'total_years_exp' => $applicant->calculateTotalExperience(),
        ]);
        return back()->with('success', 'Experience added!');
    }

    public function editExperience(WorkExperience $experience) { return view('applicant.experience.edit', compact('experience')); }

    public function updateExperience(Request $request, WorkExperience $experience)
    {
        $experience->update($request->validate([
            'organization_name' => 'required|string|max:255',
            'sector' => 'required|string',
            'position_held' => 'required|string|max:255',
            'from_date' => 'required|date',
            'to_date' => 'nullable|date',
            'is_current' => 'boolean',
            'is_construction_company' => 'boolean',
            'project_type' => 'nullable|string',
            'key_responsibilities' => 'nullable|string',
            'reason_for_leaving' => 'nullable|string',
        ]));
        $applicant = Auth::user()->applicant;
        $applicant->update([
            'has_construction_exp' => $applicant->hasConstructionExperience(),
            'total_years_exp' => $applicant->calculateTotalExperience(),
        ]);
        return redirect()->route('applicant.experience.create')->with('success', 'Experience updated!');
    }

    public function deleteExperience(WorkExperience $experience)
    {
        $experience->delete();
        return back()->with('success', 'Experience record deleted.');
    }

    // Documents
    public function documents()
    {
        $applicant = Auth::user()->applicant;
        if (!$applicant) return redirect()->route('applicant.profile.create');
        $documents = $applicant->documents()->latest()->get();
        return view('applicant.documents', compact('documents'));
    }

    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string',
            'document' => 'required|file|mimes:pdf,docx,jpg,png|max:5120',
        ]);
        $applicant = Auth::user()->applicant;
        $file = $request->file('document');
        Document::create([
            'applicant_id' => $applicant->id,
            'document_type' => $request->document_type,
            'file_path' => $file->store("private/documents/{$request->document_type}s", 'local'),
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);
        return back()->with('success', 'Document uploaded!');
    }

    public function downloadDocument(Document $document)
    {
        if (!Storage::exists($document->file_path)) abort(404);
        return Storage::download($document->file_path, $document->original_filename);
    }

    /**
     * DELETE DOCUMENT
     */
    public function deleteDocument(Document $document)
    {
        // Delete file from storage
        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }
        // Delete database record
        $document->delete();
        
        return back()->with('success', 'Document deleted successfully!');
    }

    public function completeProfile()
    {
        $applicant = Auth::user()->applicant;
        if (!$applicant->educationHistories()->exists()) {
            return redirect()->route('applicant.education.create')->with('error', 'Add at least one education record.');
        }
        $applicant->update(['profile_completed' => true]);
        return redirect()->route('vacancies.public.index')->with('success', '✅ Profile complete! You can now apply for jobs.');
    }

    private function getRegions(): array
    {
        return [
            'Addis Ababa' => 'Addis Ababa', 'Afar' => 'Afar', 'Amhara' => 'Amhara',
            'Benishangul-Gumuz' => 'Benishangul-Gumuz', 'Dire Dawa' => 'Dire Dawa',
            'Gambela' => 'Gambela', 'Harari' => 'Harari', 'Oromia' => 'Oromia',
            'Sidama' => 'Sidama', 'Somali' => 'Somali', 'SNNPR' => 'SNNPR', 'Tigray' => 'Tigray',
        ];
    }
}
