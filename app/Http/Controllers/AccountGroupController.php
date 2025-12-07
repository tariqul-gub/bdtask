<?php

namespace App\Http\Controllers;

use App\Models\AccountGroup;
use Illuminate\Http\Request;

class AccountGroupController extends Controller
{
    public function index(Request $request)
    {
        $query = AccountGroup::with(['parent', 'accounts'])->withCount('accounts');
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('parent_id')) {
            if ($request->parent_id === 'root') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $request->parent_id);
            }
        }
        
        $accountGroups = $query->latest()->paginate(15)->withQueryString();
        $parentGroups = AccountGroup::whereNull('parent_id')->get();
        
        return view('account-groups.index', compact('accountGroups', 'parentGroups'));
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
