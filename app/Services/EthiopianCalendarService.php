<?php

namespace App\Services;

use Carbon\Carbon;

class EthiopianCalendarService
{
    public function gregorianToEthiopian($date): array
    {
        $d = Carbon::parse($date);
        $ethYear = $d->year - 7;
        if ($d < Carbon::create($d->year, 9, 11)) $ethYear--;
        
        return [
            'year' => $ethYear,
            'month' => 1,
            'day' => 1,
            'month_name_am' => 'መስከረም',
            'month_name_en' => 'Meskerem',
            'formatted_am' => "መስከረም 1 ቀን {$ethYear} ዓ.ም",
            'formatted_en' => "Meskerem 1, {$ethYear} E.C.",
        ];
    }
}
