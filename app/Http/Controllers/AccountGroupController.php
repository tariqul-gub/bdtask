<?php

namespace App\Http\Controllers;

use App\Models\AccountGroup;
use Illuminate\Http\Request;

class AccountGroupController extends Controller
{
    public function index()
    {
        $accountGroups = AccountGroup::with('parent', 'children')
            ->whereNull('parent_id')
            ->get();
        
        return view('account-groups.index', compact('accountGroups'));
    }

    public function create()
    {
        $parentGroups = AccountGroup::all();
        return view('account-groups.create', compact('parentGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:account_groups,id',
        ]);

        AccountGroup::create($validated);

        return redirect()->route('account-groups.index')
            ->with('success', 'Account Group created successfully.');
    }

    public function show(AccountGroup $accountGroup)
    {
        $accountGroup->load('parent', 'children', 'accounts');
        return view('account-groups.show', compact('accountGroup'));
    }

    public function edit(AccountGroup $accountGroup)
    {
        $parentGroups = AccountGroup::where('id', '!=', $accountGroup->id)->get();
        return view('account-groups.edit', compact('accountGroup', 'parentGroups'));
    }

    public function update(Request $request, AccountGroup $accountGroup)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:account_groups,id',
        ]);

        if ($validated['parent_id'] == $accountGroup->id) {
            return back()->withErrors(['parent_id' => 'An account group cannot be its own parent.']);
        }

        $accountGroup->update($validated);

        return redirect()->route('account-groups.index')
            ->with('success', 'Account Group updated successfully.');
    }

    public function destroy(AccountGroup $accountGroup)
    {
        $accountGroup->delete();

        return redirect()->route('account-groups.index')
            ->with('success', 'Account Group deleted successfully.');
    }
}
