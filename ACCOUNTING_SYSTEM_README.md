# MultiBranch-Accountant

## Production-Ready Multi-Branch, Dual-Entry Accounting System

A comprehensive accounting system built with **Laravel 11**, **Blade Templates**, and **Laravel UI Bootstrap** stack. This application implements a complete double-entry bookkeeping system with multi-branch support and consolidated financial reporting.

---

## 🎯 System Features

### ✅ Core Accounting Features Implemented

1. **Centralized Chart of Accounts (COA)**
   - Unique account codes
   - Account types: Asset, Liability, Equity, Revenue, Expense
   - Full CRUD operations
   - Linked to account groups

2. **Multi-Level Account Grouping**
   - Self-referencing hierarchical structure
   - Parent-child relationships
   - Collapsible Bootstrap nested list view
   - Unlimited nesting levels

3. **Opening Balance Management**
   - Branch-specific opening balances
   - Account-level tracking
   - Date-based balance initialization
   - Separate debit/credit columns

4. **Journal Entry Creation & Approvals**
   - Draft, Pending, and Approved statuses
   - Dynamic line item entry with JavaScript
   - Real-time debit/credit balance validation
   - Approval workflow with status locking
   - Cannot edit/delete approved entries

5. **Recurring Journal Entries**
   - Template-based recurring patterns
   - Frequencies: Monthly, Quarterly, Yearly
   - Artisan command: `php artisan accounting:run-recurring-entries`
   - Automatic generation with status 'Pending'

6. **Auto-Ledger Posting from All Modules**
   - Static method `JournalEntryController::autoPostFromModule()`
   - Documented API for external module integration
   - Example implementations for Sales and Purchases
   - Automatic status: 'Pending' for review

7. **Multi-Branch Ledger Structure**
   - Branch-specific journal entries
   - Branch-level opening balances
   - Branch filtering in reports

8. **Consolidated Financial Views**
   - Consolidated view (all branches)
   - Branch-specific view
   - Real-time balance calculations
   - Account type summaries

---

## 📁 Project Structure

### Database Migrations
```
database/migrations/
├── 2024_01_01_000001_create_branches_table.php
├── 2024_01_01_000002_create_account_groups_table.php
├── 2024_01_01_000003_create_accounts_table.php
├── 2024_01_01_000004_create_opening_balances_table.php
├── 2024_01_01_000005_create_journal_entries_table.php
├── 2024_01_01_000006_create_journal_lines_table.php
└── 2024_01_01_000007_create_recurrence_patterns_table.php
```

### Eloquent Models
```
app/Models/
├── Branch.php
├── AccountGroup.php
├── Account.php
├── OpeningBalance.php
├── JournalEntry.php
├── JournalLine.php
└── RecurrencePattern.php
```

### Controllers
```
app/Http/Controllers/
├── BranchController.php
├── AccountGroupController.php
├── AccountController.php
├── JournalEntryController.php
└── ReportingController.php
```

### Form Requests
```
app/Http/Requests/
└── JournalEntryRequest.php (with debit/credit validation)
```

### Blade Views
```
resources/views/
├── layouts/
│   └── accounting.blade.php (main layout with sidebar)
├── branches/ (index, create, edit, show)
├── account-groups/ (index, create, edit, show, partials/group-item)
├── accounts/ (index, create, edit, show)
├── journal-entries/ (index, create, edit, show)
└── reports/
    └── index.blade.php (consolidated financial view)
```

### Artisan Commands
```
app/Console/Commands/
└── RunRecurringEntries.php
```

---

## 🚀 Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- MySQL/PostgreSQL
- Node.js & NPM

### Installation Steps

1. **Clone the repository**
   ```bash
   cd c:\Exam\accounting
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database in .env**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=accounting
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Compile assets**
   ```bash
   npm run build
   ```

8. **Start development server**
   ```bash
   php artisan serve
   ```

9. **Access the application**
   ```
   http://localhost:8000
   ```

---

## 📊 Database Schema

### Key Relationships

- **Branches** → has many → **JournalEntries**, **OpeningBalances**
- **AccountGroups** → self-referencing (parent-child)
- **AccountGroups** → has many → **Accounts**
- **Accounts** → has many → **JournalLines**, **OpeningBalances**
- **JournalEntries** → has many → **JournalLines**
- **JournalEntries** → has one → **RecurrencePattern**

---

## 🎨 User Interface

### Design Stack
- **Framework**: Bootstrap 5
- **Icons**: Bootstrap Icons
- **Layout**: Sidebar navigation with main content area
- **Color Scheme**: Professional blue primary theme
- **Responsive**: Mobile-friendly design

### Key UI Features
- Clean card-based layouts
- Color-coded account types
- Status badges for journal entries
- Real-time balance calculations
- Collapsible hierarchical views
- Alert notifications for success/error messages

---

## 🔧 Usage Guide

### 1. Setup Phase

#### Create Branches
1. Navigate to **Setup → Branches**
2. Click "Create Branch"
3. Enter branch name (e.g., "Head Office", "Branch A")

#### Create Account Groups
1. Navigate to **Setup → Account Groups**
2. Click "Create Account Group"
3. Enter group name and optionally select parent group
4. View hierarchical structure in collapsible list

#### Create Chart of Accounts
1. Navigate to **Setup → Chart of Accounts**
2. Click "Create Account"
3. Fill in:
   - Account Code (unique)
   - Account Name
   - Account Type (Asset/Liability/Equity/Revenue/Expense)
   - Account Group

### 2. Transaction Phase

#### Create Journal Entry
1. Navigate to **Transactions → Journal Entries**
2. Click "Create Journal Entry"
3. Fill header:
   - Select Branch
   - Entry Date
   - Description
4. Add journal lines:
   - Click "Add Line" to add rows
   - Select Account
   - Enter Debit or Credit amount
   - System validates: Total Debit = Total Credit
5. Click "Save Journal Entry"

#### Approve Journal Entry
1. View journal entry details
2. Verify all information
3. Click "Approve Entry" button
4. Entry status changes to "Approved" and becomes locked

### 3. Reporting Phase

#### View Financial Reports
1. Navigate to **Reports → Financial Reports**
2. Use dropdown to select:
   - **Consolidated View**: All branches combined
   - **Specific Branch**: Individual branch data
3. View account balances with:
   - Opening balances
   - Period activity (debits/credits)
   - Current balance
   - Totals by account type

---

## 🔄 Recurring Entries

### Setup Recurring Pattern
1. Create a journal entry template
2. Create a `RecurrencePattern` record:
   ```php
   RecurrencePattern::create([
       'template_je_id' => $journalEntry->id,
       'frequency' => 'Monthly', // or 'Quarterly', 'Yearly'
       'next_run_date' => '2024-01-01',
   ]);
   ```

### Run Recurring Entries Command
```bash
php artisan accounting:run-recurring-entries
```

This command:
- Finds all patterns due today or earlier
- Creates new journal entries with status 'Pending'
- Updates next_run_date based on frequency
- Logs all actions to console

### Schedule in Production
Add to `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('accounting:run-recurring-entries')->daily();
}
```

---

## 🔌 Auto-Ledger Posting API

### Integration from External Modules

The system provides a static method for external modules to automatically post journal entries:

```php
use App\Http\Controllers\JournalEntryController;

// Example: Sales Module
JournalEntryController::autoPostFromModule([
    'branch_id' => 1,
    'entry_date' => '2024-01-15',
    'description' => 'Sale Invoice #12345',
    'source_module' => 'Sales',
    'lines' => [
        [
            'account_id' => 1, // Accounts Receivable
            'debit' => 1150.00,
            'credit' => 0,
        ],
        [
            'account_id' => 10, // Sales Revenue
            'debit' => 0,
            'credit' => 1000.00,
        ],
        [
            'account_id' => 15, // Sales Tax Payable
            'debit' => 0,
            'credit' => 150.00,
        ],
    ],
]);
```

**Key Points:**
- Automatically creates journal entry with status 'Pending'
- Requires approval before affecting reports
- Source module tracked for audit trail
- Full debit/credit validation applied

---

## ✅ Validation Rules

### Journal Entry Validation
- **Debit/Credit Balance**: Sum of debits MUST equal sum of credits
- **Minimum Lines**: At least 2 journal lines required
- **Line Validation**: Each line must have either debit OR credit (not both, not neither)
- **Account Existence**: All accounts must exist in COA
- **Branch Existence**: Branch must be valid
- **Date Format**: Valid date required

### Account Validation
- **Unique Code**: Account codes must be unique
- **Valid Type**: Must be one of: Asset, Liability, Equity, Revenue, Expense
- **Account Group**: Must belong to an existing account group

---

## 🔐 Security Features

- **Authentication Required**: All routes protected by auth middleware
- **Status Locking**: Approved entries cannot be edited/deleted
- **Validation**: Server-side validation on all inputs
- **CSRF Protection**: Laravel CSRF tokens on all forms
- **SQL Injection Prevention**: Eloquent ORM with parameter binding

---

## 📈 Reporting Capabilities

### Current Reports
1. **Account Balances Report**
   - Opening balances
   - Period activity
   - Current balances
   - Totals by account type
   - Multi-branch filtering

### Future Enhancement Ideas
- Trial Balance Report
- Income Statement
- Balance Sheet
- Cash Flow Statement
- General Ledger Report
- Subsidiary Ledgers
- Aging Reports

---

## 🛠️ Technical Stack

- **Backend**: Laravel 11
- **Frontend**: Blade Templates
- **CSS Framework**: Bootstrap 5
- **JavaScript**: Vanilla JS (for dynamic forms)
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel UI
- **Icons**: Bootstrap Icons

---

## 📝 Code Quality

- **MVC Architecture**: Strict separation of concerns
- **Eloquent Relationships**: Proper model relationships
- **Form Requests**: Dedicated validation classes
- **Blade Components**: Reusable view partials
- **PSR Standards**: Following Laravel conventions
- **Comments**: Comprehensive inline documentation

---

## 🎓 Learning Resources

### Key Concepts Demonstrated
1. **Double-Entry Bookkeeping**: Every transaction has equal debits and credits
2. **Account Types**: Understanding Asset, Liability, Equity, Revenue, Expense
3. **Multi-Branch Accounting**: Separate ledgers with consolidated reporting
4. **Hierarchical Data**: Self-referencing models with recursive relationships
5. **Workflow Management**: Draft → Pending → Approved status flow
6. **Recurring Transactions**: Template-based automation
7. **Module Integration**: API for external system posting

---

## 🐛 Troubleshooting

### Common Issues

**Issue**: Debit/Credit not balancing
- **Solution**: Check all line items, ensure sum(debit) = sum(credit)

**Issue**: Cannot edit journal entry
- **Solution**: Check if entry is approved (approved entries are locked)

**Issue**: Recurring entries not generating
- **Solution**: Run `php artisan accounting:run-recurring-entries` manually

**Issue**: Consolidated view not showing data
- **Solution**: Ensure journal entries are approved (only approved entries appear in reports)

---

## 📞 Support & Contribution

This is a production-ready accounting system template. Feel free to:
- Extend with additional features
- Customize for specific business needs
- Add more reports and analytics
- Integrate with other modules

---

## 📄 License

This project is built as an educational and production-ready template for accounting systems.

---

## ✨ Credits

Built with Laravel 11, Bootstrap 5, and modern web development best practices.

**System Name**: MultiBranch-Accountant  
**Version**: 1.0.0  
**Built**: December 2024
