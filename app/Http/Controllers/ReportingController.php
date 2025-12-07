<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Branch;
use App\Models\JournalLine;
use App\Models\OpeningBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportingController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::all();
        $selectedBranch = $request->input('branch_id', 'consolidated');
        
        $accounts = Account::with('accountGroup')
            ->orderBy('code')
            ->get();
        
        $balances = [];
        
        foreach ($accounts as $account) {
            $openingBalance = $this->getOpeningBalance($account->id, $selectedBranch);
            $journalBalance = $this->getJournalBalance($account->id, $selectedBranch);
            
            $totalDebit = $openingBalance['debit'] + $journalBalance['debit'];
            $totalCredit = $openingBalance['credit'] + $journalBalance['credit'];
            
            $currentBalance = $this->calculateBalance(
                $account->type,
                $totalDebit,
                $totalCredit
            );
            
            $balances[] = [
                'account' => $account,
                'opening_debit' => $openingBalance['debit'],
                'opening_credit' => $openingBalance['credit'],
                'period_debit' => $journalBalance['debit'],
                'period_credit' => $journalBalance['credit'],
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
                'current_balance' => $currentBalance,
            ];
        }
        
        return view('reports.index', compact('balances', 'branches', 'selectedBranch'));
    }

    private function getOpeningBalance($accountId, $branchId)
    {
        $query = OpeningBalance::where('account_id', $accountId);
        
        if ($branchId !== 'consolidated') {
            $query->where('branch_id', $branchId);
        }
        
        $result = $query->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
            ->first();
        
        return [
            'debit' => $result->total_debit ?? 0,
            'credit' => $result->total_credit ?? 0,
        ];
    }

    private function getJournalBalance($accountId, $branchId)
    {
        $query = JournalLine::where('account_id', $accountId)
            ->whereHas('journalEntry', function ($q) use ($branchId) {
                $q->where('status', 'Approved');
                
                if ($branchId !== 'consolidated') {
                    $q->where('branch_id', $branchId);
                }
            });
        
        $result = $query->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
            ->first();
        
        return [
            'debit' => $result->total_debit ?? 0,
            'credit' => $result->total_credit ?? 0,
        ];
    }

    private function calculateBalance($accountType, $totalDebit, $totalCredit)
    {
        $balance = 0;
        
        switch ($accountType) {
            case 'Asset':
            case 'Expense':
                $balance = $totalDebit - $totalCredit;
                break;
            case 'Liability':
            case 'Equity':
            case 'Revenue':
                $balance = $totalCredit - $totalDebit;
                break;
        }
        
        return $balance;
    }

    public function trialBalance(Request $request)
    {
        $branches = Branch::all();
        $selectedBranch = $request->input('branch_id', 'consolidated');
        $asOfDate = $request->input('as_of_date', now()->format('Y-m-d'));
        
        $accounts = Account::with('accountGroup')
            ->orderBy('code')
            ->get();
        
        $trialBalanceData = [];
        $totalDebit = 0;
        $totalCredit = 0;
        
        foreach ($accounts as $account) {
            $balance = $this->getAccountBalance($account->id, $selectedBranch, $asOfDate);
            
            if ($balance['debit'] > 0 || $balance['credit'] > 0) {
                $trialBalanceData[] = [
                    'account' => $account,
                    'debit' => $balance['debit'],
                    'credit' => $balance['credit'],
                ];
                
                $totalDebit += $balance['debit'];
                $totalCredit += $balance['credit'];
            }
        }
        
        return view('reports.trial-balance', compact(
            'trialBalanceData',
            'branches',
            'selectedBranch',
            'asOfDate',
            'totalDebit',
            'totalCredit'
        ));
    }

    private function getAccountBalance($accountId, $branchId, $asOfDate)
    {
        $openingBalance = $this->getOpeningBalance($accountId, $branchId);
        $journalBalance = $this->getJournalBalanceUpToDate($accountId, $branchId, $asOfDate);
        
        $totalDebit = $openingBalance['debit'] + $journalBalance['debit'];
        $totalCredit = $openingBalance['credit'] + $journalBalance['credit'];
        
        return [
            'debit' => $totalDebit > $totalCredit ? $totalDebit - $totalCredit : 0,
            'credit' => $totalCredit > $totalDebit ? $totalCredit - $totalDebit : 0,
        ];
    }

    private function getJournalBalanceUpToDate($accountId, $branchId, $asOfDate)
    {
        $query = JournalLine::where('account_id', $accountId)
            ->whereHas('journalEntry', function ($q) use ($branchId, $asOfDate) {
                $q->where('status', 'Approved')
                  ->where('entry_date', '<=', $asOfDate);
                
                if ($branchId !== 'consolidated') {
                    $q->where('branch_id', $branchId);
                }
            });
        
        $result = $query->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
            ->first();
        
        return [
            'debit' => $result->total_debit ?? 0,
            'credit' => $result->total_credit ?? 0,
        ];
    }
}
