<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalaryCalculatorController extends Controller
{
    public function index()
    {
        return view('tools.salary-calculator');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'working_days' => 'required|numeric|min:1|max:31',
            'transport_allowance' => 'nullable|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'responsibility_allowance' => 'nullable|numeric|min:0',
            'loan' => 'nullable|numeric|min:0',
        ]);

        $basicSalary = (float) $request->basic_salary;
        $workingDays = (float) $request->working_days;
        $monthlyPay = round(($basicSalary / 30) * $workingDays, 2);
        $transportAllowance = (float) ($request->transport_allowance ?? 0);
        
        $hourlyRate = $basicSalary / 30 / 8;
        $overtimeHours = (float) ($request->overtime_hours ?? 0);
        $overtimePay = round($overtimeHours * $hourlyRate * 1.5, 2);
        
        $responsibilityAllowance = (float) ($request->responsibility_allowance ?? 0);
        $loan = (float) ($request->loan ?? 0);
        
        // Taxable = Monthly Pay + OT + Responsibility (Transport NOT taxed)
        $taxableEarning = round($monthlyPay + $overtimePay + $responsibilityAllowance, 2);
        
        // Gross = Taxable + Transport
        $grossSalary = round($taxableEarning + $transportAllowance, 2);
        
        // Ethiopian Tax 2025
        $incomeTax = round($this->calculateTax($taxableEarning), 2);
        
        // Pension
        $pensionEmployee = round($basicSalary * 0.07, 2);
        $pensionCompany = round($basicSalary * 0.11, 2);
        
        // Total Deduction
        $totalDeduction = round($incomeTax + $pensionEmployee + $loan, 2);
        
        // Net Pay
        $netPay = round($grossSalary - $totalDeduction, 2);
        
        return response()->json([
            'success' => true,
            'basic_salary' => round($basicSalary, 2),
            'working_days' => $workingDays,
            'monthly_pay' => $monthlyPay,
            'transport_allowance' => $transportAllowance,
            'overtime' => $overtimePay,
            'responsibility_allowance' => $responsibilityAllowance,
            'loan' => $loan,
            'taxable_earning' => $taxableEarning,
            'gross_salary' => $grossSalary,
            'income_tax' => $incomeTax,
            'pension_employee' => $pensionEmployee,
            'pension_company' => $pensionCompany,
            'total_deduction' => $totalDeduction,
            'net_pay' => $netPay,
            'currency' => 'ETB',
            'formatted' => [
                'basic_salary' => number_format($basicSalary, 2),
                'working_days' => number_format($workingDays, 2),
                'monthly_pay' => number_format($monthlyPay, 2),
                'transport_allowance' => number_format($transportAllowance, 2),
                'overtime' => number_format($overtimePay, 2),
                'responsibility_allowance' => number_format($responsibilityAllowance, 2),
                'loan' => number_format($loan, 2),
                'taxable_earning' => number_format($taxableEarning, 2),
                'gross_salary' => number_format($grossSalary, 2),
                'income_tax' => number_format($incomeTax, 2),
                'pension_employee' => number_format($pensionEmployee, 2),
                'pension_company' => number_format($pensionCompany, 2),
                'total_deduction' => number_format($totalDeduction, 2),
                'net_pay' => number_format($netPay, 2),
            ]
        ]);
    }

    private function calculateTax($income)
    {
        if ($income <= 2000) return 0;
        if ($income <= 4000) return ($income * 0.15) - 300;
        if ($income <= 7000) return ($income * 0.20) - 500;
        if ($income <= 10000) return ($income * 0.25) - 850;
        if ($income <= 14000) return ($income * 0.30) - 1350;
        return ($income * 0.35) - 2050;
    }
}
