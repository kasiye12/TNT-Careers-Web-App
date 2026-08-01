<table>
    <thead>
        <tr><th colspan="4" style="text-align:center;font-size:16px;font-weight:bold;">TNT Construction - Demographics</th></tr>
        <tr><th>Region</th><th>Male</th><th>Female</th><th>Total</th></tr>
    </thead>
    <tbody>
        @foreach($regionalStats as $region => $genders)
            @php $m = $genders->where('gender','male')->first()->count ?? 0; $f = $genders->where('gender','female')->first()->count ?? 0; @endphp
            <tr><td>{{ $region }}</td><td>{{ $m }}</td><td>{{ $f }}</td><td>{{ $m + $f }}</td></tr>
        @endforeach
    </tbody>
</table>
