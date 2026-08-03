<table>
    <thead>
        <tr>
            <th colspan="4" style="text-align:center;font-size:16px;font-weight:bold;background:#0b3b5a;color:white;padding:10px;">
                TNT Construction - Demographics Report
            </th>
        </tr>
        <tr style="background:#e5e7eb;">
            <th style="border:1px solid #ccc;padding:8px;">Region</th>
            <th style="border:1px solid #ccc;padding:8px;">Male</th>
            <th style="border:1px solid #ccc;padding:8px;">Female</th>
            <th style="border:1px solid #ccc;padding:8px;">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($regionalStats as $region => $genders)
            @php
                $m = $genders->where('gender','male')->first()->count ?? 0;
                $f = $genders->where('gender','female')->first()->count ?? 0;
            @endphp
            <tr>
                <td style="border:1px solid #ccc;padding:6px;">{{ $region }}</td>
                <td style="border:1px solid #ccc;padding:6px;text-align:center;">{{ $m }}</td>
                <td style="border:1px solid #ccc;padding:6px;text-align:center;">{{ $f }}</td>
                <td style="border:1px solid #ccc;padding:6px;text-align:center;font-weight:bold;">{{ $m + $f }}</td>
            </tr>
        @endforeach
        <tr style="background:#e5e7eb;font-weight:bold;">
            <td style="border:1px solid #ccc;padding:6px;">Total</td>
            <td style="border:1px solid #ccc;padding:6px;text-align:center;">{{ $genderStats->where('gender','male')->first()->count ?? 0 }}</td>
            <td style="border:1px solid #ccc;padding:6px;text-align:center;">{{ $genderStats->where('gender','female')->first()->count ?? 0 }}</td>
            <td style="border:1px solid #ccc;padding:6px;text-align:center;">{{ $genderStats->sum('count') }}</td>
        </tr>
    </tbody>
</table>
