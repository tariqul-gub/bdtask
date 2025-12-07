<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JournalEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => 'required|exists:branches,id',
            'entry_date' => 'required|date',
            'description' => 'required|string',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.debit' => 'required|numeric|min:0',
            'lines.*.credit' => 'required|numeric|min:0',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $lines = $this->input('lines', []);
            
            $totalDebit = 0;
            $totalCredit = 0;
            
            foreach ($lines as $line) {
                $totalDebit += floatval($line['debit'] ?? 0);
                $totalCredit += floatval($line['credit'] ?? 0);
            }
            
            if (abs($totalDebit - $totalCredit) > 0.01) {
                $validator->errors()->add(
                    'lines',
                    'Total debits must equal total credits. Current: Debit = ' . number_format($totalDebit, 2) . 
                    ', Credit = ' . number_format($totalCredit, 2)
                );
            }
            
            foreach ($lines as $index => $line) {
                $debit = floatval($line['debit'] ?? 0);
                $credit = floatval($line['credit'] ?? 0);
                
                if ($debit > 0 && $credit > 0) {
                    $validator->errors()->add(
                        "lines.{$index}",
                        'A line cannot have both debit and credit amounts. Please use only one.'
                    );
                }
                
                if ($debit == 0 && $credit == 0) {
                    $validator->errors()->add(
                        "lines.{$index}",
                        'A line must have either a debit or credit amount.'
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'branch_id.required' => 'Please select a branch.',
            'entry_date.required' => 'Entry date is required.',
            'description.required' => 'Description is required.',
            'lines.required' => 'At least two journal lines are required.',
            'lines.min' => 'At least two journal lines are required for a valid entry.',
        ];
    }
}
