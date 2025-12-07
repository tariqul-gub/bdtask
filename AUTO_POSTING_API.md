# 🔌 Auto-Ledger Posting API Documentation

## Overview

The MultiBranch-Accountant system provides a powerful API for external modules to automatically post journal entries to the General Ledger. This enables seamless integration with Sales, Purchases, Inventory, Payroll, and other business modules.

---

## API Method

### `JournalEntryController::autoPostFromModule()`

**Location**: `app/Http/Controllers/JournalEntryController.php`

**Type**: Static Method

**Purpose**: Automatically create journal entries from external modules with validation and tracking.

---

## Method Signature

```php
public static function autoPostFromModule(array $data): JournalEntry
```

### Parameters

**$data** (array) - Required. Must contain:

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `branch_id` | integer | Yes | ID of the branch for this entry |
| `entry_date` | string/date | Yes | Date of the transaction (Y-m-d format) |
| `description` | string | Yes | Description of the transaction |
| `source_module` | string | Yes | Name of the source module (e.g., 'Sales', 'Purchases') |
| `lines` | array | Yes | Array of journal line items (minimum 2) |

**lines** array structure:

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `account_id` | integer | Yes | ID of the account from Chart of Accounts |
| `debit` | decimal | Yes | Debit amount (0 if credit entry) |
| `credit` | decimal | Yes | Credit amount (0 if debit entry) |

### Return Value

Returns a `JournalEntry` model instance with:
- Status automatically set to `'Pending'`
- All journal lines created
- Ready for approval workflow

---

## Validation Rules

The API automatically validates:

1. **Debit/Credit Balance**: Sum of debits MUST equal sum of credits
2. **Minimum Lines**: At least 2 journal lines required
3. **Line Validation**: Each line must have either debit OR credit (not both)
4. **Account Existence**: All account IDs must exist in the accounts table
5. **Branch Existence**: Branch ID must be valid

---

## Usage Examples

### Example 1: Sales Invoice

```php
use App\Http\Controllers\JournalEntryController;

// After creating a sale in your SalesController
$sale = Sale::create([...]);

// Automatically post to General Ledger
$journalEntry = JournalEntryController::autoPostFromModule([
    'branch_id' => $sale->branch_id,
    'entry_date' => $sale->sale_date,
    'description' => "Sale Invoice #{$sale->invoice_number} - Customer: {$sale->customer_name}",
    'source_module' => 'Sales',
    'lines' => [
        [
            'account_id' => 1100, // Accounts Receivable
            'debit' => 1150.00,   // Total amount including tax
            'credit' => 0,
        ],
        [
            'account_id' => 4000, // Sales Revenue
            'debit' => 0,
            'credit' => 1000.00,  // Sale amount
        ],
        [
            'account_id' => 2200, // Sales Tax Payable
            'debit' => 0,
            'credit' => 150.00,   // Tax amount
        ],
    ],
]);

// Entry is created with status 'Pending' for review
```

### Example 2: Purchase Invoice

```php
use App\Http\Controllers\JournalEntryController;

// After creating a purchase
$purchase = Purchase::create([...]);

$journalEntry = JournalEntryController::autoPostFromModule([
    'branch_id' => $purchase->branch_id,
    'entry_date' => $purchase->purchase_date,
    'description' => "Purchase Invoice #{$purchase->invoice_number} - Supplier: {$purchase->supplier_name}",
    'source_module' => 'Purchases',
    'lines' => [
        [
            'account_id' => 1200, // Inventory
            'debit' => 5000.00,
            'credit' => 0,
        ],
        [
            'account_id' => 2000, // Accounts Payable
            'debit' => 0,
            'credit' => 5000.00,
        ],
    ],
]);
```

### Example 3: Payment Receipt

```php
use App\Http\Controllers\JournalEntryController;

// After receiving payment
$payment = Payment::create([...]);

$journalEntry = JournalEntryController::autoPostFromModule([
    'branch_id' => $payment->branch_id,
    'entry_date' => $payment->payment_date,
    'description' => "Payment Receipt #{$payment->receipt_number} from {$payment->customer_name}",
    'source_module' => 'Payments',
    'lines' => [
        [
            'account_id' => 1000, // Cash
            'debit' => 1000.00,
            'credit' => 0,
        ],
        [
            'account_id' => 1100, // Accounts Receivable
            'debit' => 0,
            'credit' => 1000.00,
        ],
    ],
]);
```

### Example 4: Expense Payment

```php
use App\Http\Controllers\JournalEntryController;

$expense = Expense::create([...]);

$journalEntry = JournalEntryController::autoPostFromModule([
    'branch_id' => $expense->branch_id,
    'entry_date' => $expense->expense_date,
    'description' => "Expense Payment - {$expense->category}: {$expense->description}",
    'source_module' => 'Expenses',
    'lines' => [
        [
            'account_id' => 5200, // Rent Expense (or appropriate expense account)
            'debit' => 2000.00,
            'credit' => 0,
        ],
        [
            'account_id' => 1000, // Cash
            'debit' => 0,
            'credit' => 2000.00,
        ],
    ],
]);
```

### Example 5: Payroll Entry

```php
use App\Http\Controllers\JournalEntryController;

$payroll = Payroll::create([...]);

$journalEntry = JournalEntryController::autoPostFromModule([
    'branch_id' => $payroll->branch_id,
    'entry_date' => $payroll->payroll_date,
    'description' => "Payroll for period {$payroll->period_start} to {$payroll->period_end}",
    'source_module' => 'Payroll',
    'lines' => [
        [
            'account_id' => 5100, // Salaries Expense
            'debit' => 10000.00,
            'credit' => 0,
        ],
        [
            'account_id' => 2100, // Salaries Payable
            'debit' => 0,
            'credit' => 10000.00,
        ],
    ],
]);
```

### Example 6: Inventory Adjustment

```php
use App\Http\Controllers\JournalEntryController;

$adjustment = InventoryAdjustment::create([...]);

$journalEntry = JournalEntryController::autoPostFromModule([
    'branch_id' => $adjustment->branch_id,
    'entry_date' => $adjustment->adjustment_date,
    'description' => "Inventory Adjustment - {$adjustment->reason}",
    'source_module' => 'Inventory',
    'lines' => [
        [
            'account_id' => 5000, // Cost of Goods Sold
            'debit' => 500.00,
            'credit' => 0,
        ],
        [
            'account_id' => 1200, // Inventory
            'debit' => 0,
            'credit' => 500.00,
        ],
    ],
]);
```

---

## Integration Workflow

### Step 1: Transaction Occurs in External Module
```php
// In your SalesController, PurchaseController, etc.
$transaction = YourModel::create($validatedData);
```

### Step 2: Call Auto-Posting API
```php
use App\Http\Controllers\JournalEntryController;

$journalEntry = JournalEntryController::autoPostFromModule([
    'branch_id' => $transaction->branch_id,
    'entry_date' => $transaction->date,
    'description' => "Description of transaction",
    'source_module' => 'YourModule',
    'lines' => [
        // Your debit/credit lines
    ],
]);
```

### Step 3: Store Reference (Optional)
```php
// Store the journal entry ID in your transaction record
$transaction->update([
    'journal_entry_id' => $journalEntry->id,
]);
```

### Step 4: Approval Workflow
- Entry is created with status `'Pending'`
- Accounting staff reviews the entry
- Staff approves the entry (status changes to `'Approved'`)
- Approved entries appear in financial reports

---

## Error Handling

### Try-Catch Pattern

```php
use App\Http\Controllers\JournalEntryController;
use Exception;

try {
    $journalEntry = JournalEntryController::autoPostFromModule([
        'branch_id' => $sale->branch_id,
        'entry_date' => $sale->sale_date,
        'description' => "Sale Invoice #{$sale->invoice_number}",
        'source_module' => 'Sales',
        'lines' => [
            // Your lines
        ],
    ]);
    
    // Success - store reference
    $sale->update(['journal_entry_id' => $journalEntry->id]);
    
} catch (Exception $e) {
    // Handle error
    Log::error('Failed to post journal entry: ' . $e->getMessage());
    
    // Optionally notify user or admin
    // You might want to queue this for retry
}
```

### Common Errors

| Error | Cause | Solution |
|-------|-------|----------|
| Validation Error | Debits ≠ Credits | Verify line amounts sum correctly |
| Foreign Key Error | Invalid account_id | Ensure account exists in COA |
| Foreign Key Error | Invalid branch_id | Verify branch exists |
| Validation Error | Less than 2 lines | Add at least 2 journal lines |
| Validation Error | Both debit and credit | Use only debit OR credit per line |

---

## Best Practices

### 1. Always Use Try-Catch
```php
try {
    $journalEntry = JournalEntryController::autoPostFromModule([...]);
} catch (Exception $e) {
    // Handle gracefully
}
```

### 2. Store References
```php
// Link back to the journal entry
$transaction->update(['journal_entry_id' => $journalEntry->id]);
```

### 3. Descriptive Descriptions
```php
'description' => "Sale Invoice #INV-12345 - Customer: John Doe - Items: 5"
```

### 4. Use Correct Source Module
```php
'source_module' => 'Sales',      // Not 'sales' or 'SALES'
'source_module' => 'Purchases',  // Not 'Purchase'
'source_module' => 'Inventory',  // Be consistent
```

### 5. Validate Before Posting
```php
// Ensure amounts balance before calling API
$totalDebit = array_sum(array_column($lines, 'debit'));
$totalCredit = array_sum(array_column($lines, 'credit'));

if (abs($totalDebit - $totalCredit) > 0.01) {
    throw new Exception('Transaction does not balance');
}
```

### 6. Use Database Transactions
```php
use Illuminate\Support\Facades\DB;

DB::beginTransaction();
try {
    $sale = Sale::create([...]);
    $journalEntry = JournalEntryController::autoPostFromModule([...]);
    $sale->update(['journal_entry_id' => $journalEntry->id]);
    
    DB::commit();
} catch (Exception $e) {
    DB::rollBack();
    throw $e;
}
```

---

## Account Mapping Reference

### Common Account Mappings

| Transaction Type | Debit Account | Credit Account |
|-----------------|---------------|----------------|
| Cash Sale | Cash (1000) | Sales Revenue (4000) |
| Credit Sale | Accounts Receivable (1100) | Sales Revenue (4000) |
| Purchase Inventory | Inventory (1200) | Accounts Payable (2000) |
| Pay Expense | Expense Account (5xxx) | Cash (1000) |
| Receive Payment | Cash (1000) | Accounts Receivable (1100) |
| Pay Supplier | Accounts Payable (2000) | Cash (1000) |
| Payroll | Salaries Expense (5100) | Salaries Payable (2100) |
| Depreciation | Depreciation Expense (5500) | Accumulated Depreciation |

---

## Testing

### Unit Test Example

```php
use Tests\TestCase;
use App\Http\Controllers\JournalEntryController;
use App\Models\Branch;
use App\Models\Account;

class AutoPostingTest extends TestCase
{
    public function test_can_auto_post_journal_entry()
    {
        $branch = Branch::factory()->create();
        $cashAccount = Account::factory()->create(['code' => '1000']);
        $revenueAccount = Account::factory()->create(['code' => '4000']);
        
        $journalEntry = JournalEntryController::autoPostFromModule([
            'branch_id' => $branch->id,
            'entry_date' => now()->format('Y-m-d'),
            'description' => 'Test Sale',
            'source_module' => 'Sales',
            'lines' => [
                ['account_id' => $cashAccount->id, 'debit' => 100, 'credit' => 0],
                ['account_id' => $revenueAccount->id, 'debit' => 0, 'credit' => 100],
            ],
        ]);
        
        $this->assertEquals('Pending', $journalEntry->status);
        $this->assertEquals('Sales', $journalEntry->source_module);
        $this->assertCount(2, $journalEntry->journalLines);
    }
}
```

---

## FAQ

**Q: What status is the entry created with?**  
A: Always `'Pending'` - requires approval before appearing in reports.

**Q: Can I create entries with status 'Approved'?**  
A: No, all auto-posted entries require manual approval for audit purposes.

**Q: What if my transaction has more than 2 lines?**  
A: No problem! Add as many lines as needed (e.g., sale with multiple tax types).

**Q: Can I update an auto-posted entry?**  
A: Yes, if status is still 'Pending'. Once approved, entries are locked.

**Q: How do I track which entries came from my module?**  
A: Use the `source_module` field and filter: `JournalEntry::where('source_module', 'Sales')->get()`

**Q: What happens if validation fails?**  
A: An exception is thrown - use try-catch to handle gracefully.

---

## Support

For issues or questions about the Auto-Posting API:
1. Check this documentation
2. Review the examples above
3. Check `app/Http/Controllers/JournalEntryController.php` for implementation
4. Review `app/Http/Requests/JournalEntryRequest.php` for validation rules

---

## Version History

- **v1.0.0** (December 2024) - Initial API release

---

**Happy Integrating!** 🚀
