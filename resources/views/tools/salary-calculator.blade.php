@extends('layouts.app')
@section('title', 'Payroll Calculator')

@push('styles')
<style>
    .payroll-table th { background: #0b3b5a; color: white; padding: 8px 6px; border: 1px solid #1a5270; text-align: center; font-weight: 600; font-size: 10px; }
    .payroll-table td { padding: 6px; border: 1px solid #e2e8f0; text-align: right; font-size: 11px; }
    .payroll-table tr:hover td { background: #f0f9ff; }
    .total-row td { font-weight: 700; background: #e0f2fe; border-top: 2px solid #0b3b5a; }
</style>
@endpush

@section('content')
<div class="max-w-full mx-auto px-4 sm:px-6 py-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-extrabold text-[#0b3b5a]">TNT Construction & Trading PLC</h1>
        <p class="text-gray-600 text-lg">Payroll Calculator - Ethiopian Tax 2025</p>
    </div>

    <!-- Input Form -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border mb-8 max-w-4xl mx-auto">
        <h3 class="font-bold text-lg text-[#0b3b5a] mb-4"><i class="fas fa-calculator mr-2 text-[#0a7aa8]"></i>Employee Payroll Details</h3>
        <form id="salaryForm" class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <!-- Employee Name -->
            <div class="col-span-2 md:col-span-4">
                <label class="block text-xs font-semibold mb-1">Employee</label>
                <input type="text" id="employee_name" class="search-input w-full px-4 py-2.5 rounded-xl text-sm" placeholder="Employee name (optional)">
            </div>
            
            <!-- Basic Salary -->
            <div>
                <label class="block text-xs font-semibold mb-1">Basic Salary *</label>
                <input type="number" id="basic_salary" required class="search-input w-full px-4 py-2.5 rounded-xl text-sm" placeholder="0.00">
            </div>
            
            <!-- Working Days -->
            <div>
                <label class="block text-xs font-semibold mb-1">Working Days</label>
                <input type="number" id="working_days" value="30" class="search-input w-full px-4 py-2.5 rounded-xl text-sm">
            </div>
            
            <!-- Standard Days -->
            <div>
                <label class="block text-xs font-semibold mb-1">Standard Days</label>
                <input type="number" id="standard_days" value="30" class="search-input w-full px-4 py-2.5 rounded-xl text-sm">
            </div>

            <!-- Transport Allowance -->
            <div>
                <label class="block text-xs font-semibold mb-1">Transport Allow.</label>
                <input type="number" id="transport_allowance" value="0" class="search-input w-full px-4 py-2.5 rounded-xl text-sm">
            </div>
            
            <!-- Responsibility Allowance -->
            <div>
                <label class="block text-xs font-semibold mb-1">Responsibility Allow.</label>
                <input type="number" id="responsibility_allowance" value="0" class="search-input w-full px-4 py-2.5 rounded-xl text-sm">
            </div>
            
            <!-- Fuel Allowance -->
            <div>
                <label class="block text-xs font-semibold mb-1">Fuel Allowance</label>
                <input type="number" id="fuel_allowance" value="0" class="search-input w-full px-4 py-2.5 rounded-xl text-sm">
            </div>
            
            <!-- Per Diem -->
            <div>
                <label class="block text-xs font-semibold mb-1">Per Diem</label>
                <input type="number" id="per_diem" value="0" class="search-input w-full px-4 py-2.5 rounded-xl text-sm">
            </div>
            
            <!-- OT / Bonus -->
            <div>
                <label class="block text-xs font-semibold mb-1">OT / Bonus</label>
                <input type="number" id="overtime_bonus" value="0" class="search-input w-full px-4 py-2.5 rounded-xl text-sm">
            </div>
            
            <!-- Other Earnings -->
            <div>
                <label class="block text-xs font-semibold mb-1">Other Earnings</label>
                <input type="number" id="other_earnings" value="0" class="search-input w-full px-4 py-2.5 rounded-xl text-sm">
            </div>
            
            <!-- Loan Deduction -->
            <div>
                <label class="block text-xs font-semibold mb-1">Loan Deduction</label>
                <input type="number" id="loan_deduction" value="0" class="search-input w-full px-4 py-2.5 rounded-xl text-sm">
            </div>
            
            <!-- Other Deduction -->
            <div>
                <label class="block text-xs font-semibold mb-1">Other Deduction</label>
                <input type="number" id="other_deduction" value="0" class="search-input w-full px-4 py-2.5 rounded-xl text-sm">
            </div>
            
            <!-- Buttons -->
            <div class="col-span-2 md:col-span-4 flex gap-3">
                <button type="submit" class="btn-solid-sky flex-1 py-2.5 text-sm rounded-xl font-bold">
                    <i class="fas fa-calculator mr-2"></i> Calculate
                </button>
                <button type="button" onclick="clearAll()" class="border border-gray-300 text-gray-600 flex-1 py-2.5 text-sm rounded-xl hover:bg-gray-50">
                    <i class="fas fa-redo mr-2"></i> Clear
                </button>
                <button type="button" onclick="window.print()" class="border border-gray-300 text-gray-600 py-2.5 px-4 text-sm rounded-xl hover:bg-gray-50">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </form>
    </div>

    <!-- Results Table -->
    <div class="bg-white rounded-2xl shadow-sm border overflow-x-auto" id="payrollSection" style="display:none;">
        <div class="p-4 text-center border-b">
            <h2 class="text-lg font-bold">Salary Slip - <span id="displayMonth"></span></h2>
            <p class="text-sm text-gray-500" id="displayEmployee"></p>
        </div>
        
        <!-- Result Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4" id="resultCards"></div>
        
        <!-- Payroll Table -->
        <table class="payroll-table w-full">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount (ETB)</th>
                </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('displayMonth').textContent = new Date().toLocaleString('default', {month:'long',year:'numeric'}).toUpperCase();

document.getElementById('salaryForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const fd = new FormData();
    fd.append('employee_name', document.getElementById('employee_name').value);
    fd.append('basic_salary', document.getElementById('basic_salary').value||0);
    fd.append('working_days', document.getElementById('working_days').value||30);
    fd.append('standard_days', document.getElementById('standard_days').value||30);
    fd.append('transport_allowance', document.getElementById('transport_allowance').value||0);
    fd.append('responsibility_allowance', document.getElementById('responsibility_allowance').value||0);
    fd.append('fuel_allowance', document.getElementById('fuel_allowance').value||0);
    fd.append('per_diem', document.getElementById('per_diem').value||0);
    fd.append('overtime_bonus', document.getElementById('overtime_bonus').value||0);
    fd.append('other_earnings', document.getElementById('other_earnings').value||0);
    fd.append('loan_deduction', document.getElementById('loan_deduction').value||0);
    fd.append('other_deduction', document.getElementById('other_deduction').value||0);
    
    try {
        const res = await fetch('{{ route("salary.calculate") }}', {
            method:'POST', body:fd,
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
        });
        const d = await res.json();
        
        document.getElementById('payrollSection').style.display = 'block';
        document.getElementById('displayEmployee').textContent = d.employee_name;
        
        // Result Cards
        document.getElementById('resultCards').innerHTML = `
            <div class="bg-blue-50 rounded-xl p-4 text-center"><p class="text-xs text-gray-500">Gross Earnings</p><p class="text-xl font-extrabold text-blue-700">${d.formatted.total_earnings}</p></div>
            <div class="bg-red-50 rounded-xl p-4 text-center"><p class="text-xs text-gray-500">Total Deductions</p><p class="text-xl font-extrabold text-red-600">${d.formatted.total_deductions}</p></div>
            <div class="bg-green-50 rounded-xl p-4 text-center"><p class="text-xs text-gray-500">Net Pay</p><p class="text-xl font-extrabold text-green-600">${d.formatted.net_pay}</p></div>
            <div class="bg-purple-50 rounded-xl p-4 text-center"><p class="text-xs text-gray-500">Pension (Company)</p><p class="text-xl font-extrabold text-purple-600">${d.formatted.pension_company}</p></div>
        `;
        
        // Table
        document.getElementById('tableBody').innerHTML = `
            <tr><td class="text-left font-semibold">Basic Salary (${d.working_days}/${d.standard_days} days)</td><td class="font-bold">${d.formatted.basic_salary}</td></tr>
            <tr><td class="text-left">Monthly Pay</td><td>${d.formatted.monthly_pay}</td></tr>
            <tr><td class="text-left">Transport Allowance</td><td class="text-green-600">+${d.formatted.transport_allowance}</td></tr>
            <tr><td class="text-left">Responsibility Allowance</td><td class="text-green-600">+${d.formatted.responsibility_allowance}</td></tr>
            <tr><td class="text-left">Fuel Allowance</td><td class="text-green-600">+${d.formatted.fuel_allowance}</td></tr>
            <tr><td class="text-left">Per Diem</td><td class="text-green-600">+${d.formatted.per_diem}</td></tr>
            <tr><td class="text-left">OT / Bonus</td><td class="text-green-600">+${d.formatted.overtime_bonus}</td></tr>
            <tr><td class="text-left">Other Earnings</td><td class="text-green-600">+${d.formatted.other_earnings}</td></tr>
            <tr class="bg-blue-50"><td class="text-left font-bold">TOTAL EARNINGS</td><td class="font-bold text-blue-700">${d.formatted.total_earnings}</td></tr>
            <tr><td class="text-left">Taxable Income</td><td>${d.formatted.taxable_income}</td></tr>
            <tr><td class="text-left text-red-600">Income Tax</td><td class="text-red-600">-${d.formatted.income_tax}</td></tr>
            <tr><td class="text-left">Pension (Employee 7%)</td><td class="text-red-600">-${d.formatted.pension_employee}</td></tr>
            <tr><td class="text-left">Loan Deduction</td><td class="text-red-600">-${d.formatted.loan_deduction}</td></tr>
            <tr><td class="text-left">Other Deduction</td><td class="text-red-600">-${d.formatted.other_deduction}</td></tr>
            <tr class="bg-red-50"><td class="text-left font-bold">TOTAL DEDUCTIONS</td><td class="font-bold text-red-600">-${d.formatted.total_deductions}</td></tr>
            <tr class="total-row"><td class="text-left" style="font-size:14px;">💰 NET PAY</td><td style="font-size:16px;color:#059669;">${d.formatted.net_pay} ETB</td></tr>
        `;
        
    } catch(e) { alert('Error calculating. Please try again.'); }
});

function clearAll() {
    document.getElementById('salaryForm').reset();
    document.getElementById('working_days').value = 30;
    document.getElementById('standard_days').value = 30;
    document.getElementById('payrollSection').style.display = 'none';
}
</script>
@endsection
