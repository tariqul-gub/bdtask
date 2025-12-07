<?php

namespace App\Http\Controllers;

use App\Models\OpeningBalance;
use App\Models\Branch;
use App\Models\Account;
use Illuminate\Http\Request;

class OpeningBalanceController extends Controller
{
    public function index(Request $request)
    {
        $query = OpeningBalance::with(['branch', 'account']);
        
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }
        
        if ($request->filled('search')) {
            $query->whereHas('account', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }
        
        $openingBalances = $query->latest()->paginate(20)->withQueryString();
        $branches = Branch::all();
        $accounts = Account::orderBy('code')->get();
        
        return view('opening-balances.index', compact('openingBalances', 'branches', 'accounts'));
    }

    public function create()
    {
        $branches = Branch::all();
        $accounts = Account::with('accountGroup')->orderBy('code')->get();
        
        return view('opening-balances.create', compact('branches', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'account_id' => 'required|exists:accounts,id',
            'date' => 'required|date',
            'debit' => 'required|numeric|min:0',
            'credit' => 'required|numeric|min:0',
        ]);

        if ($validated['debit'] > 0 && $validated['credit'] > 0) {
            return back()->withErrors(['error' => 'Cannot have both debit and credit. Use only one.'])->withInput();
        }

        if ($validated['debit'] == 0 && $validated['credit'] == 0) {
            return back()->withErrors(['error' => 'Must have either debit or credit amount.'])->withInput();
        }

        OpeningBalance::create($validated);

        return redirect()->route('opening-balances.index')
            ->with('success', 'Opening balance created successfully.');
    }

    public function show(OpeningBalance $openingBalance)
    {
        $openingBalance->load(['branch', 'account.accountGroup']);
        return view('opening-balances.show', compact('openingBalance'));
    }

    public function edit(OpeningBalance $openingBalance)
    {
        $branches = Branch::all();
        $accounts = Account::with('accountGroup')->orderBy('code')->get();
        
        return view('opening-balances.edit', compact('openingBalance', 'branches', 'accounts'));
    }

    public function update(Request $request, OpeningBalance $openingBalance)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'account_id' => 'required|exists:accounts,id',
            'date' => 'required|date',
            'debit' => 'required|numeric|min:0',
            'credit' => 'required|numeric|min:0',
        ]);

        if ($validated['debit'] > 0 && $validated['credit'] > 0) {
            return back()->withErrors(['error' => 'Cannot have both debit and credit. Use only one.'])->withInput();
        }

        if ($validated['debit'] == 0 && $validated['credit'] == 0) {
            return back()->withErrors(['error' => 'Must have either debit or credit amount.'])->withInput();
        }

        $openingBalance->update($validated);

        return redirect()->route('opening-balances.index')
            ->with('success', 'Opening balance updated successfully.');
    }

    public function destroy(OpeningBalance $openingBalance)
    {
        $openingBalance->delete();

        return redirect()->route('opening-balances.index')
            ->with('success', 'Opening balance deleted successfully.');
    }
}
