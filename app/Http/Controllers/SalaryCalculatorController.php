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
            'employee_name' => 'nullable|string|max:255',
            'basic_salary' => 'required|numeric|min:0',
            'working_days' => 'required|numeric|min:1|max:31',
            'standard_days' => 'required|numeric|min:1|max:31',
            'transport_allowance' => 'nullable|numeric|min:0',
            'responsibility_allowance' => 'nullable|numeric|min:0',
            'fuel_allowance' => 'nullable|numeric|min:0',
            'per_diem' => 'nullable|numeric|min:0',
            'overtime_bonus' => 'nullable|numeric|min:0',
            'other_earnings' => 'nullable|numeric|min:0',
            'loan_deduction' => 'nullable|numeric|min:0',
            'other_deduction' => 'nullable|numeric|min:0',
        ]);

        $employeeName = $request->employee_name ?: 'Employee';
        $basicSalary = (float) $request->basic_salary;
        $workingDays = (float) $request->working_days;
        $standardDays = (float) $request->standard_days;
        
        // Monthly Pay = (Basic Salary / Standard Days) × Working Days
        $monthlyPay = round(($basicSalary / $standardDays) * $workingDays, 2);
        
        // Allowances
        $transportAllowance = (float) ($request->transport_allowance ?? 0);
        $responsibilityAllowance = (float) ($request->responsibility_allowance ?? 0);
        $fuelAllowance = (float) ($request->fuel_allowance ?? 0);
        $perDiem = (float) ($request->per_diem ?? 0);
        $overtimeBonus = (float) ($request->overtime_bonus ?? 0);
        $otherEarnings = (float) ($request->other_earnings ?? 0);
        
        // Total Earnings
        $totalEarnings = round($monthlyPay + $transportAllowance + $responsibilityAllowance + 
                              $fuelAllowance + $perDiem + $overtimeBonus + $otherEarnings, 2);
        
        // Taxable Income = Monthly Pay + Responsibility + Fuel + Per Diem + OT + Other
        // (Transport is usually non-taxable in Ethiopia)
        $taxableIncome = round($monthlyPay + $responsibilityAllowance + $fuelAllowance + 
                              $perDiem + $overtimeBonus + $otherEarnings, 2);
        
        // Income Tax (Ethiopian 2025)
        $incomeTax = round($this->calculateTax($taxableIncome), 2);
        
        // Pension (7% of Basic Salary)
        $pensionEmployee = round($basicSalary * 0.07, 2);
        $pensionCompany = round($basicSalary * 0.11, 2);
        
        // Deductions
        $loanDeduction = (float) ($request->loan_deduction ?? 0);
        $otherDeduction = (float) ($request->other_deduction ?? 0);
        $totalDeductions = round($incomeTax + $pensionEmployee + $loanDeduction + $otherDeduction, 2);
        
        // Net Pay
        $netPay = round($totalEarnings - $totalDeductions, 2);
        
        return response()->json([
            'success' => true,
            'employee_name' => $employeeName,
            'basic_salary' => $basicSalary,
            'working_days' => $workingDays,
            'standard_days' => $standardDays,
            'monthly_pay' => $monthlyPay,
            'transport_allowance' => $transportAllowance,
            'responsibility_allowance' => $responsibilityAllowance,
            'fuel_allowance' => $fuelAllowance,
            'per_diem' => $perDiem,
            'overtime_bonus' => $overtimeBonus,
            'other_earnings' => $otherEarnings,
            'total_earnings' => $totalEarnings,
            'taxable_income' => $taxableIncome,
            'income_tax' => $incomeTax,
            'pension_employee' => $pensionEmployee,
            'pension_company' => $pensionCompany,
            'loan_deduction' => $loanDeduction,
            'other_deduction' => $otherDeduction,
            'total_deductions' => $totalDeductions,
            'net_pay' => $netPay,
            'currency' => 'ETB',
            'formatted' => [
                'basic_salary' => number_format($basicSalary, 2),
                'monthly_pay' => number_format($monthlyPay, 2),
                'transport_allowance' => number_format($transportAllowance, 2),
                'responsibility_allowance' => number_format($responsibilityAllowance, 2),
                'fuel_allowance' => number_format($fuelAllowance, 2),
                'per_diem' => number_format($perDiem, 2),
                'overtime_bonus' => number_format($overtimeBonus, 2),
                'other_earnings' => number_format($otherEarnings, 2),
                'total_earnings' => number_format($totalEarnings, 2),
                'taxable_income' => number_format($taxableIncome, 2),
                'income_tax' => number_format($incomeTax, 2),
                'pension_employee' => number_format($pensionEmployee, 2),
                'pension_company' => number_format($pensionCompany, 2),
                'loan_deduction' => number_format($loanDeduction, 2),
                'other_deduction' => number_format($otherDeduction, 2),
                'total_deductions' => number_format($totalDeductions, 2),
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
