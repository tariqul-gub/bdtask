<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\Branch;
use App\Models\Account;
use App\Http\Requests\JournalEntryRequest;
use Illuminate\Http\Request;

class JournalEntryController extends Controller
{
    public function index(Request $request)
    {
        $query = JournalEntry::with('branch');
        
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('from_date')) {
            $query->where('entry_date', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->where('entry_date', '<=', $request->to_date);
        }
        
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }
        
        $journalEntries = $query->latest('entry_date')->paginate(15)->withQueryString();
        $branches = Branch::all();
        
        return view('journal-entries.index', compact('journalEntries', 'branches'));
    }

    public function create()
    {
        $branches = Branch::all();
        $accounts = Account::with('accountGroup')->get();
        
        return view('journal-entries.create', compact('branches', 'accounts'));
    }

    public function store(JournalEntryRequest $request)
    {
        $journalEntry = JournalEntry::create([
            'branch_id' => $request->branch_id,
            'entry_date' => $request->entry_date,
            'description' => $request->description,
            'status' => 'Draft',
            'source_module' => 'GL',
        ]);

        foreach ($request->lines as $line) {
            $journalEntry->journalLines()->create([
                'account_id' => $line['account_id'],
                'debit' => $line['debit'] ?? 0,
                'credit' => $line['credit'] ?? 0,
            ]);
        }

        return redirect()->route('journal-entries.show', $journalEntry)
            ->with('success', 'Journal Entry created successfully.');
    }

    public function show(JournalEntry $journalEntry)
    {
        $journalEntry->load('branch', 'journalLines.account.accountGroup');
        
        return view('journal-entries.show', compact('journalEntry'));
    }

    public function edit(JournalEntry $journalEntry)
    {
        if ($journalEntry->status === 'Approved') {
            return redirect()->route('journal-entries.show', $journalEntry)
                ->with('error', 'Cannot edit an approved journal entry.');
        }

        $branches = Branch::all();
        $accounts = Account::with('accountGroup')->get();
        $journalEntry->load('journalLines');
        
        return view('journal-entries.edit', compact('journalEntry', 'branches', 'accounts'));
    }

    public function update(JournalEntryRequest $request, JournalEntry $journalEntry)
    {
        if ($journalEntry->status === 'Approved') {
            return redirect()->route('journal-entries.show', $journalEntry)
                ->with('error', 'Cannot update an approved journal entry.');
        }

        $journalEntry->update([
            'branch_id' => $request->branch_id,
            'entry_date' => $request->entry_date,
            'description' => $request->description,
        ]);

        $journalEntry->journalLines()->delete();

        foreach ($request->lines as $line) {
            $journalEntry->journalLines()->create([
                'account_id' => $line['account_id'],
                'debit' => $line['debit'] ?? 0,
                'credit' => $line['credit'] ?? 0,
            ]);
        }

        return redirect()->route('journal-entries.show', $journalEntry)
            ->with('success', 'Journal Entry updated successfully.');
    }

    public function destroy(JournalEntry $journalEntry)
    {
        if ($journalEntry->status === 'Approved') {
            return redirect()->route('journal-entries.index')
                ->with('error', 'Cannot delete an approved journal entry.');
        }

        $journalEntry->delete();

        return redirect()->route('journal-entries.index')
            ->with('success', 'Journal Entry deleted successfully.');
    }

    public function approve($id)
    {
        $journalEntry = JournalEntry::findOrFail($id);

        if ($journalEntry->status === 'Approved') {
            return redirect()->route('journal-entries.show', $journalEntry)
                ->with('error', 'This journal entry is already approved.');
        }

        $journalEntry->update(['status' => 'Approved']);

        return redirect()->route('journal-entries.show', $journalEntry)
            ->with('success', 'Journal Entry approved successfully.');
    }

    /**
     * AUTO-LEDGER POSTING DOCUMENTATION
     * 
     * This method demonstrates how external modules (Sales, Purchases, Inventory, etc.)
     * can automatically create journal entries in the General Ledger system.
     * 
     * USAGE EXAMPLE FROM SALES MODULE:
     * ================================
     * 
     * use App\Http\Controllers\JournalEntryController;
     * 
     * // In your SalesController after creating a sale:
     * $saleAmount = 1000.00;
     * $taxAmount = 150.00;
     * 
     * JournalEntryController::autoPostFromModule([
     *     'branch_id' => $sale->branch_id,
     *     'entry_date' => $sale->sale_date,
     *     'description' => "Sale Invoice #{$sale->invoice_number}",
     *     'source_module' => 'Sales',
     *     'lines' => [
     *         [
     *             'account_id' => 1, // Accounts Receivable
     *             'debit' => $saleAmount + $taxAmount,
     *             'credit' => 0,
     *         ],
     *         [
     *             'account_id' => 10, // Sales Revenue
     *             'debit' => 0,
     *             'credit' => $saleAmount,
     *         ],
     *         [
     *             'account_id' => 15, // Sales Tax Payable
     *             'debit' => 0,
     *             'credit' => $taxAmount,
     *         ],
     *     ],
     * ]);
     * 
     * USAGE EXAMPLE FROM PURCHASE MODULE:
     * ===================================
     * 
     * JournalEntryController::autoPostFromModule([
     *     'branch_id' => $purchase->branch_id,
     *     'entry_date' => $purchase->purchase_date,
     *     'description' => "Purchase Invoice #{$purchase->invoice_number}",
     *     'source_module' => 'Purchases',
     *     'lines' => [
     *         [
     *             'account_id' => 20, // Inventory
     *             'debit' => $purchaseAmount,
     *             'credit' => 0,
     *         ],
     *         [
     *             'account_id' => 5, // Accounts Payable
     *             'debit' => 0,
     *             'credit' => $purchaseAmount,
     *         ],
     *     ],
     * ]);
     * 
     * @param array $data - Array containing branch_id, entry_date, description, source_module, and lines
     * @return JournalEntry - The created journal entry with status 'Pending'
     */
    public static function autoPostFromModule(array $data): JournalEntry
    {
        $journalEntry = JournalEntry::create([
            'branch_id' => $data['branch_id'],
            'entry_date' => $data['entry_date'],
            'description' => $data['description'],
            'status' => 'Pending',
            'source_module' => $data['source_module'],
        ]);

        foreach ($data['lines'] as $line) {
            $journalEntry->journalLines()->create([
                'account_id' => $line['account_id'],
                'debit' => $line['debit'] ?? 0,
                'credit' => $line['credit'] ?? 0,
            ]);
        }

        return $journalEntry;
    }
}
