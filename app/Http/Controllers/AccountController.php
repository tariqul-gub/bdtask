<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountGroup;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $query = Account::with('accountGroup');
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('group_id')) {
            $query->where('account_group_id', $request->group_id);
        }
        
        $accounts = $query->orderBy('code')->paginate(20)->withQueryString();
        $accountGroups = AccountGroup::all();
        $accountTypes = ['Asset', 'Liability', 'Equity', 'Revenue', 'Expense'];
        
        return view('accounts.index', compact('accounts', 'accountGroups', 'accountTypes'));
    }

    public function create()
    {
        $accountGroups = AccountGroup::all();
        $accountTypes = ['Asset', 'Liability', 'Equity', 'Revenue', 'Expense'];
        return view('accounts.create', compact('accountGroups', 'accountTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:accounts,code|max:50',
            'name' => 'required|string|max:255',
            'type' => 'required|in:Asset,Liability,Equity,Revenue,Expense',
            'account_group_id' => 'required|exists:account_groups,id',
        ]);

        Account::create($validated);

        return redirect()->route('accounts.index')
            ->with('success', 'Account created successfully.');
    }

    public function show(Account $account)
    {
        $account->load('accountGroup', 'journalLines.journalEntry');
        return view('accounts.show', compact('account'));
    }

    public function edit(Account $account)
    {
        $accountGroups = AccountGroup::all();
        $accountTypes = ['Asset', 'Liability', 'Equity', 'Revenue', 'Expense'];
        return view('accounts.edit', compact('account', 'accountGroups', 'accountTypes'));
    }

    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:accounts,code,' . $account->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:Asset,Liability,Equity,Revenue,Expense',
            'account_group_id' => 'required|exists:account_groups,id',
        ]);

        $account->update($validated);

        return redirect()->route('accounts.index')
            ->with('success', 'Account updated successfully.');
    }

    public function destroy(Account $account)
    {
        $account->delete();

        return redirect()->route('accounts.index')
            ->with('success', 'Account deleted successfully.');
    }
}
