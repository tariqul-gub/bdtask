<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Branch;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\OpeningBalance;
use App\Models\AccountGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportingController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::all();
        $selectedBranch = $request->input('branch_id', 'consolidated');
        $accountType = $request->input('account_type', '');
        $fromDate = $request->input('from_date', '');
        $toDate = $request->input('to_date', '');
        
        $accountsQuery = Account::with('accountGroup')->orderBy('code');
        
        if ($accountType) {
            $accountsQuery->where('type', $accountType);
        }
        
        $accounts = $accountsQuery->get();
        
        $balances = [];
        
        foreach ($accounts as $account) {
            $openingBalance = $this->getOpeningBalance($account->id, $selectedBranch);
            $journalBalance = $this->getJournalBalance($account->id, $selectedBranch, $fromDate, $toDate);
            
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
        
        return view('reports.index', compact('balances', 'branches', 'selectedBranch', 'accountType', 'fromDate', 'toDate'));
    }
    
    public function ledger(Request $request)
    {
        $branches = Branch::all();
        $accounts = Account::orderBy('code')->get();
        
        $selectedBranch = $request->input('branch_id', 'consolidated');
        $accountId = $request->input('account_id', '');
        $fromDate = $request->input('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));
        
        $ledgerEntries = [];
        $account = null;
        $openingBalance = 0;
        
        if ($accountId) {
            $account = Account::find($accountId);
            
            // Get opening balance
            $obData = $this->getOpeningBalance($accountId, $selectedBranch);
            $priorBalance = $this->getJournalBalanceUpToDate($accountId, $selectedBranch, date('Y-m-d', strtotime($fromDate . ' -1 day')));
            
            $openingDebit = $obData['debit'] + $priorBalance['debit'];
            $openingCredit = $obData['credit'] + $priorBalance['credit'];
            $openingBalance = $this->calculateBalance($account->type, $openingDebit, $openingCredit);
            
            // Get journal entries for period
            $query = JournalLine::with(['journalEntry.branch', 'account'])
                ->where('account_id', $accountId)
                ->whereHas('journalEntry', function ($q) use ($selectedBranch, $fromDate, $toDate) {
                    $q->where('status', 'Approved')
                      ->whereBetween('entry_date', [$fromDate, $toDate]);
                    
                    if ($selectedBranch !== 'consolidated') {
                        $q->where('branch_id', $selectedBranch);
                    }
                })
                ->join('journal_entries', 'journal_lines.journal_entry_id', '=', 'journal_entries.id')
                ->orderBy('journal_entries.entry_date')
                ->orderBy('journal_entries.id')
                ->select('journal_lines.*');
            
            $ledgerEntries = $query->get();
        }
        
        return view('reports.ledger', compact('branches', 'accounts', 'selectedBranch', 'accountId', 'fromDate', 'toDate', 'ledgerEntries', 'account', 'openingBalance'));
    }
    
    public function incomeStatement(Request $request)
    {
        $branches = Branch::all();
        $selectedBranch = $request->input('branch_id', 'consolidated');
        $fromDate = $request->input('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));
        
        // Revenue accounts
        $revenueAccounts = Account::where('type', 'Revenue')->orderBy('code')->get();
        $revenues = [];
        $totalRevenue = 0;
        
        foreach ($revenueAccounts as $account) {
            $balance = $this->getJournalBalance($account->id, $selectedBranch, $fromDate, $toDate);
            $amount = $balance['credit'] - $balance['debit'];
            if ($amount != 0) {
                $revenues[] = ['account' => $account, 'amount' => $amount];
                $totalRevenue += $amount;
            }
        }
        
        // Expense accounts
        $expenseAccounts = Account::where('type', 'Expense')->orderBy('code')->get();
        $expenses = [];
        $totalExpense = 0;
        
        foreach ($expenseAccounts as $account) {
            $balance = $this->getJournalBalance($account->id, $selectedBranch, $fromDate, $toDate);
            $amount = $balance['debit'] - $balance['credit'];
            if ($amount != 0) {
                $expenses[] = ['account' => $account, 'amount' => $amount];
                $totalExpense += $amount;
            }
        }
        
        $netIncome = $totalRevenue - $totalExpense;
        
        return view('reports.income-statement', compact('branches', 'selectedBranch', 'fromDate', 'toDate', 'revenues', 'expenses', 'totalRevenue', 'totalExpense', 'netIncome'));
    }
    
    public function balanceSheet(Request $request)
    {
        $branches = Branch::all();
        $selectedBranch = $request->input('branch_id', 'consolidated');
        $asOfDate = $request->input('as_of_date', now()->format('Y-m-d'));
        
        // Assets
        $assetAccounts = Account::where('type', 'Asset')->orderBy('code')->get();
        $assets = [];
        $totalAssets = 0;
        
        foreach ($assetAccounts as $account) {
            $balance = $this->getAccountBalanceAsOf($account->id, $selectedBranch, $asOfDate);
            if ($balance != 0) {
                $assets[] = ['account' => $account, 'balance' => $balance];
                $totalAssets += $balance;
            }
        }
        
        // Liabilities
        $liabilityAccounts = Account::where('type', 'Liability')->orderBy('code')->get();
        $liabilities = [];
        $totalLiabilities = 0;
        
        foreach ($liabilityAccounts as $account) {
            $balance = $this->getAccountBalanceAsOf($account->id, $selectedBranch, $asOfDate);
            if ($balance != 0) {
                $liabilities[] = ['account' => $account, 'balance' => $balance];
                $totalLiabilities += $balance;
            }
        }
        
        // Equity
        $equityAccounts = Account::where('type', 'Equity')->orderBy('code')->get();
        $equities = [];
        $totalEquity = 0;
        
        foreach ($equityAccounts as $account) {
            $balance = $this->getAccountBalanceAsOf($account->id, $selectedBranch, $asOfDate);
            if ($balance != 0) {
                $equities[] = ['account' => $account, 'balance' => $balance];
                $totalEquity += $balance;
            }
        }
        
        return view('reports.balance-sheet', compact('branches', 'selectedBranch', 'asOfDate', 'assets', 'liabilities', 'equities', 'totalAssets', 'totalLiabilities', 'totalEquity'));
    }
    
    private function getAccountBalanceAsOf($accountId, $branchId, $asOfDate)
    {
        $account = Account::find($accountId);
        $openingBalance = $this->getOpeningBalance($accountId, $branchId);
        $journalBalance = $this->getJournalBalanceUpToDate($accountId, $branchId, $asOfDate);
        
        $totalDebit = $openingBalance['debit'] + $journalBalance['debit'];
        $totalCredit = $openingBalance['credit'] + $journalBalance['credit'];
        
        return $this->calculateBalance($account->type, $totalDebit, $totalCredit);
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

    private function getJournalBalance($accountId, $branchId, $fromDate = null, $toDate = null)
    {
        $query = JournalLine::where('account_id', $accountId)
            ->whereHas('journalEntry', function ($q) use ($branchId, $fromDate, $toDate) {
                $q->where('status', 'Approved');
                
                if ($branchId !== 'consolidated') {
                    $q->where('branch_id', $branchId);
                }
                
                if ($fromDate && $toDate) {
                    $q->whereBetween('entry_date', [$fromDate, $toDate]);
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
