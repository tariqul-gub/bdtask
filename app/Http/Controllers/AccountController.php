<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountGroup;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::with('accountGroup')->latest()->paginate(20);
        return view('accounts.index', compact('accounts'));
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
