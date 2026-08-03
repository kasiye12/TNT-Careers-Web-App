<?php

namespace App\Helpers;

class SettingsHelper
{
    public static function get($key, $default = null)
    {
        $value = cache()->get('setting_' . $key);
        if ($value !== null) return $value;
        $value = env($key);
        if ($value !== null) return $value;
        return $default;
    }
    
    public static function companyName() { return self::get('APP_NAME', 'TNT Construction & Trading PLC'); }
    public static function companyShortName() { return 'TNT'; }
    public static function contactEmail() { return self::get('MAIL_FROM_ADDRESS', 'hr@tnt-constructions.com'); }
    public static function contactPhone() { return cache()->get('setting_contact_phone', '+251-11-XXXXXXX'); }
    public static function contactAddress() { return cache()->get('setting_contact_address', 'Addis Ababa, Ethiopia'); }
    public static function companyDescription() { return cache()->get('setting_company_description', 'Grade One General Contractor building Ethiopia\'s future.'); }
    
    public static function socialLinks()
    {
        return [
            'facebook' => cache()->get('setting_facebook'),
            'linkedin' => cache()->get('setting_linkedin'),
            'twitter' => cache()->get('setting_twitter'),
            'instagram' => cache()->get('setting_instagram'),
        ];
    }
    
    public static function hasSocialLinks()
    {
        $links = self::socialLinks();
        return !empty($links['facebook']) || !empty($links['linkedin']) || !empty($links['twitter']) || !empty($links['instagram']);
    }
}
