@extends('layouts.app')
@section('title', 'Payroll Calculator')
@push('styles')
<style>.payroll-table{font-size:11px}.payroll-table th{background:#0b3b5a;color:white;padding:8px 6px;border:1px solid #1a5270;text-align:center;font-weight:600}.payroll-table td{padding:6px;border:1px solid #e2e8f0;text-align:right}.payroll-table tr:hover td{background:#f0f9ff}.total-row td{font-weight:700;background:#e0f2fe;border-top:2px solid #0b3b5a}</style>
@endpush
@section('content')
<section class="max-w-full mx-auto px-6 py-8">
    <div class="text-center mb-8"><h1 class="text-3xl font-extrabold text-[#0b3b5a]">TNT Construction & Trading PLC</h1><p class="text-gray-600 text-lg">Salary Adjustment · Payroll Calculator</p></div>
    
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70 mb-8 max-w-4xl mx-auto">
        <h3 class="font-bold text-lg text-[#0b3b5a] mb-4"><i class="fas fa-user-plus mr-2 text-[#0a7aa8]"></i>Add Employee</h3>
        <form id="salaryForm" class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="col-span-2"><label class="block text-xs font-semibold mb-1">Name *</label><input type="text" id="employee_name" required class="search-input w-full px-3 py-2 text-sm rounded-lg"></div>
            <div><label class="block text-xs font-semibold mb-1">Basic Salary *</label><input type="number" id="basic_salary" required class="search-input w-full px-3 py-2 text-sm rounded-lg"></div>
            <div><label class="block text-xs font-semibold mb-1">Working Days</label><input type="number" id="working_days" value="30" class="search-input w-full px-3 py-2 text-sm rounded-lg"></div>
            <div><label class="block text-xs font-semibold mb-1">Transport Allow.</label><input type="number" id="transport_allowance" value="2200" class="search-input w-full px-3 py-2 text-sm rounded-lg"></div>
            <div><label class="block text-xs font-semibold mb-1">OT Hours</label><input type="number" id="overtime_hours" value="0" class="search-input w-full px-3 py-2 text-sm rounded-lg"></div>
            <div><label class="block text-xs font-semibold mb-1">Respo. Allowance</label><input type="number" id="responsibility_allowance" value="0" class="search-input w-full px-3 py-2 text-sm rounded-lg"></div>
            <div><label class="block text-xs font-semibold mb-1">Loan</label><input type="number" id="loan" value="0" class="search-input w-full px-3 py-2 text-sm rounded-lg"></div>
            <div class="col-span-2 md:col-span-4 flex gap-3">
                <button type="submit" class="btn-solid-sky flex-1 py-2 text-sm rounded-xl"><i class="fas fa-plus mr-1"></i> Add</button>
                <button type="button" onclick="clearAll()" class="border border-gray-300 text-gray-600 flex-1 py-2 text-sm rounded-xl hover:bg-gray-50"><i class="fas fa-trash mr-1"></i> Clear</button>
                <button type="button" onclick="window.print()" class="border border-gray-300 text-gray-600 py-2 px-4 text-sm rounded-xl hover:bg-gray-50"><i class="fas fa-print mr-1"></i> Print</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/70 overflow-x-auto" id="payrollSection" style="display:none;">
        <div class="p-4 text-center"><h2 class="text-lg font-bold">Salary Adjustment · <span id="displayMonth"></span></h2></div>
        <table class="payroll-table w-full"><thead><tr><th>NO</th><th>Name</th><th>Basic Salary</th><th>Working Days</th><th>Monthly Pay</th><th>Transport Allow.</th><th>OT</th><th>Respo. Allow.</th><th>Taxable Earning</th><th>Gross Salary</th><th>Income Tax</th><th>Pension Emp.</th><th>Pension Co.</th><th>Loan</th><th>Total Deduction</th><th>Net Pay</th><th>Sign.</th></tr></thead><tbody id="tableBody"></tbody><tfoot id="tableFoot"></tfoot></table>
        <div class="grid grid-cols-4 gap-4 p-6 text-xs"><div class="text-center"><div style="border-top:1px solid #333;padding-top:4px;display:inline-block;min-width:100px"></div><p class="mt-1 font-semibold">Prepared: Hanna M.</p></div><div class="text-center"><div style="border-top:1px solid #333;padding-top:4px;display:inline-block;min-width:100px"></div><p class="mt-1 font-semibold">Checked: Menbere F.</p></div><div class="text-center"><div style="border-top:1px solid #333;padding-top:4px;display:inline-block;min-width:100px"></div><p class="mt-1 font-semibold">Authorized: Samuel A.</p></div><div class="text-center"><div style="border-top:1px solid #333;padding-top:4px;display:inline-block;min-width:100px"></div><p class="mt-1 font-semibold">Approved: Selam H.</p></div></div>
    </div>
</section>

<script>
let entries=[],count=0;
document.getElementById('displayMonth').textContent=new Date().toLocaleString('default',{month:'long',year:'numeric'}).toUpperCase();
document.getElementById('salaryForm').addEventListener('submit',async function(e){
    e.preventDefault();
    const name=document.getElementById('employee_name').value;
    if(!name)return alert('Enter name');
    const fd=new FormData();
    fd.append('basic_salary',document.getElementById('basic_salary').value||0);
    fd.append('working_days',document.getElementById('working_days').value||30);
    fd.append('transport_allowance',document.getElementById('transport_allowance').value||0);
    fd.append('overtime_hours',document.getElementById('overtime_hours').value||0);
    fd.append('responsibility_allowance',document.getElementById('responsibility_allowance').value||0);
    fd.append('loan',document.getElementById('loan').value||0);
    try{
        const r=await fetch('{{ route("salary.calculate") }}',{method:'POST',body:fd,headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}});
        const d=await r.json();
        entries.push({name,...d});count++;updateTable();
        this.reset();document.getElementById('working_days').value=30;document.getElementById('transport_allowance').value=2200;
    }catch(e){alert('Error');}
});
function updateTable(){
    document.getElementById('payrollSection').style.display='block';
    let t={basic:0,monthly:0,transport:0,ot:0,respo:0,taxable:0,gross:0,tax:0,pemp:0,pco:0,loan:0,ded:0,net:0};
    document.getElementById('tableBody').innerHTML=entries.map((e,i)=>{
        t.basic+=e.basic_salary;t.monthly+=e.monthly_pay;t.transport+=e.transport_allowance;t.ot+=e.overtime;t.respo+=e.responsibility_allowance;t.taxable+=e.taxable_earning;t.gross+=e.gross_salary;t.tax+=e.income_tax;t.pemp+=e.pension_employee;t.pco+=e.pension_company;t.loan+=e.loan;t.ded+=e.total_deduction;t.net+=e.net_pay;
        return `<tr><td class="text-center">${i+1}</td><td class="text-left font-medium">${e.name}</td><td style="color:#0284c7;font-weight:600">${e.formatted.basic_salary}</td><td class="text-center">${e.formatted.working_days}</td><td style="color:#0284c7;font-weight:600">${e.formatted.monthly_pay}</td><td>${e.formatted.transport_allowance}</td><td>${e.formatted.overtime}</td><td>${e.formatted.responsibility_allowance}</td><td style="color:#0284c7;font-weight:600">${e.formatted.taxable_earning}</td><td style="color:#0284c7;font-weight:700">${e.formatted.gross_salary}</td><td style="color:#dc2626">${e.formatted.income_tax}</td><td>${e.formatted.pension_employee}</td><td>${e.formatted.pension_company}</td><td>${e.formatted.loan}</td><td style="color:#dc2626;font-weight:700">${e.formatted.total_deduction}</td><td style="color:#059669;font-weight:700">${e.formatted.net_pay}</td><td class="text-center">***</td></tr>`;
    }).join('');
    document.getElementById('tableFoot').innerHTML=`<tr class="total-row"><td colspan="2" class="text-center">Total</td><td>${fmt(t.basic)}</td><td>30.00</td><td>${fmt(t.monthly)}</td><td>${fmt(t.transport)}</td><td>${fmt(t.ot)}</td><td>${fmt(t.respo)}</td><td>${fmt(t.taxable)}</td><td>${fmt(t.gross)}</td><td style="color:#dc2626">${fmt(t.tax)}</td><td>${fmt(t.pemp)}</td><td>${fmt(t.pco)}</td><td>${fmt(t.loan)}</td><td style="color:#dc2626">${fmt(t.ded)}</td><td style="color:#059669">${fmt(t.net)}</td><td></td></tr>`;
}
function fmt(n){return new Intl.NumberFormat('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}).format(n)}
function clearAll(){entries=[];count=0;document.getElementById('tableBody').innerHTML='';document.getElementById('tableFoot').innerHTML='';document.getElementById('payrollSection').style.display='none';}
</script>
@endsection
