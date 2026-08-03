<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings');
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_short' => 'required|string|max:10',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
            'contact_address' => 'required|string|max:255',
            'company_description' => 'nullable|string|max:500',
            'employee_count' => 'required|string|max:20',
            'year_established' => 'required|string|max:20',
            'grade_level' => 'required|string|max:20',
        ]);

        $this->updateEnv(['APP_NAME' => '"' . $request->company_name . '"']);
        
        cache()->put('setting_company_short', $request->company_short);
        cache()->put('setting_contact_phone', $request->contact_phone);
        cache()->put('setting_contact_address', $request->contact_address);
        cache()->put('setting_company_description', $request->company_description);
        cache()->put('setting_employee_count', $request->employee_count);
        cache()->put('setting_year_established', $request->year_established);
        cache()->put('setting_grade_level', $request->grade_level);
        
        // Social links - only save if provided (optional)
        if ($request->filled('facebook_url')) {
            cache()->put('setting_facebook', $request->facebook_url);
        } else {
            cache()->forget('setting_facebook');
        }
        if ($request->filled('linkedin_url')) {
            cache()->put('setting_linkedin', $request->linkedin_url);
        } else {
            cache()->forget('setting_linkedin');
        }
        if ($request->filled('twitter_url')) {
            cache()->put('setting_twitter', $request->twitter_url);
        } else {
            cache()->forget('setting_twitter');
        }
        if ($request->filled('instagram_url')) {
            cache()->put('setting_instagram', $request->instagram_url);
        } else {
            cache()->forget('setting_instagram');
        }

        return back()->with('success', '✅ General settings updated successfully!');
    }

    public function updateRecruitment(Request $request)
    {
        $request->validate([
            'max_applications' => 'required|integer|min:1',
            'vacancy_duration' => 'required|integer|min:1',
        ]);

        cache()->put('max_applications', $request->max_applications);
        cache()->put('vacancy_duration', $request->vacancy_duration);
        cache()->put('auto_close_expired', $request->has('auto_close_expired'));
        cache()->put('send_notifications', $request->has('send_notifications'));
        cache()->put('require_doc_verification', $request->has('require_doc_verification'));

        return back()->with('success', '✅ Recruitment settings updated!');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'smtp_host' => 'required|string|max:255',
            'smtp_port' => 'required|integer',
            'sender_email' => 'required|email',
        ]);

        $this->updateEnv([
            'MAIL_HOST' => $request->smtp_host,
            'MAIL_PORT' => $request->smtp_port,
            'MAIL_FROM_ADDRESS' => $request->sender_email,
        ]);

        if ($request->filled('smtp_username')) $this->updateEnv(['MAIL_USERNAME' => $request->smtp_username]);
        if ($request->filled('smtp_password')) $this->updateEnv(['MAIL_PASSWORD' => $request->smtp_password]);

        return back()->with('success', '✅ Email settings updated!');
    }

    public function updateDocuments(Request $request)
    {
        $request->validate(['max_file_size' => 'required|integer|min:1|max:50']);
        cache()->put('max_file_size', $request->max_file_size);
        cache()->put('allowed_file_types', $request->allowed_types ?? ['pdf', 'docx', 'jpg', 'png']);
        return back()->with('success', '✅ Document settings updated!');
    }

    public function testEmail(Request $request)
    {
        try {
            \Mail::raw('Test email from TNT RATS at ' . now(), function($msg) use ($request) {
                $msg->to($request->test_email ?? config('mail.from.address'))
                    ->subject('✅ TNT RATS - Email Configuration Test');
            });
            return back()->with('success', '✅ Test email sent! Check your inbox.');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Email failed: ' . $e->getMessage());
        }
    }

    private function updateEnv(array $values)
    {
        $envFile = base_path('.env');
        if (!file_exists($envFile)) return;
        $content = file_get_contents($envFile);
        foreach ($values as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, $replacement, $content);
            } else {
                $content .= "\n{$replacement}";
            }
        }
        file_put_contents($envFile, $content);
        Artisan::call('config:clear');
    }
}
