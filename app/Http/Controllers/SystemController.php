<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SystemController extends Controller
{
    public function health()
    {
        $checks = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'database' => $this->checkDatabase(),
            'storage' => $this->checkStorage(),
            'mail' => $this->checkMail(),
            'cache' => $this->checkCache(),
        ];
        
        return view('system.health', compact('checks'));
    }
    
    private function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            return ['status' => true, 'message' => 'Connected', 'driver' => DB::connection()->getDriverName()];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
    
    private function checkStorage()
    {
        return [
            'status' => is_writable(storage_path()),
            'free_space' => disk_free_space(storage_path()),
            'total_space' => disk_total_space(storage_path()),
        ];
    }
    
    private function checkMail()
    {
        return [
            'driver' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'from' => config('mail.from.address'),
        ];
    }
    
    private function checkCache()
    {
        return [
            'driver' => config('cache.default'),
            'status' => true,
        ];
    }
}
