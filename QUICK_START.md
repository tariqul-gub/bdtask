# Quick Start Guide - MultiBranch-Accountant

## 🚀 Get Started in 5 Minutes

### Step 1: Run Migrations
```bash
php artisan migrate
```

### Step 2: Start the Server
```bash
php artisan serve
```

### Step 3: Register/Login
Visit `http://localhost:8000` and create an account.

### Step 4: Initial Setup (First Time)

#### A. Create a Branch
1. Go to **Setup → Branches**
2. Click "Create Branch"
3. Add "Head Office"

#### B. Create Account Groups
1. Go to **Setup → Account Groups**
2. Create these groups:
   - Current Assets
   - Fixed Assets
   - Current Liabilities
   - Long-term Liabilities
   - Owner's Equity
   - Operating Revenue
   - Operating Expenses

#### C. Create Sample Accounts
1. Go to **Setup → Chart of Accounts**
2. Create these accounts:

| Code | Name | Type | Group |
|------|------|------|-------|
| 1000 | Cash | Asset | Current Assets |
| 1100 | Accounts Receivable | Asset | Current Assets |
| 2000 | Accounts Payable | Liability | Current Liabilities |
| 3000 | Owner's Capital | Equity | Owner's Equity |
| 4000 | Sales Revenue | Revenue | Operating Revenue |
| 5000 | Cost of Goods Sold | Expense | Operating Expenses |

### Step 5: Create Your First Journal Entry

1. Go to **Transactions → Journal Entries**
2. Click "Create Journal Entry"
3. Fill in:
   - Branch: Head Office
   - Date: Today
   - Description: "Initial capital investment"
4. Add lines:
   - Line 1: Cash (1000) - Debit: 10,000.00
   - Line 2: Owner's Capital (3000) - Credit: 10,000.00
5. Click "Save Journal Entry"
6. Click "Approve Entry"

### Step 6: View Reports

1. Go to **Reports → Financial Reports**
2. Select "Consolidated View"
3. See your account balances!

---

## 🎯 Sample Transactions to Try

### Transaction 1: Make a Sale
- **Debit**: Accounts Receivable (1100) - $1,000
- **Credit**: Sales Revenue (4000) - $1,000

### Transaction 2: Pay Expenses
- **Debit**: Operating Expenses (5000) - $500
- **Credit**: Cash (1000) - $500

### Transaction 3: Receive Payment
- **Debit**: Cash (1000) - $1,000
- **Credit**: Accounts Receivable (1100) - $1,000

---

## 🔄 Test Recurring Entries

1. Create a journal entry for monthly rent
2. In database, create a recurrence pattern:
```sql
INSERT INTO recurrence_patterns (template_je_id, frequency, next_run_date, created_at, updated_at)
VALUES (1, 'Monthly', '2024-01-01', NOW(), NOW());
```
3. Run command:
```bash
php artisan accounting:run-recurring-entries
```

---

## 📊 Multi-Branch Testing

1. Create multiple branches (Branch A, Branch B)
2. Create journal entries for each branch
3. View reports:
   - Select "Consolidated View" to see all branches
   - Select specific branch to see individual data

---

## ✅ Validation Testing

Try these to see validation in action:

### Test 1: Unbalanced Entry
- Add Debit: $100
- Add Credit: $50
- Try to save → Error: "Total debits must equal total credits"

### Test 2: Edit Approved Entry
- Approve a journal entry
- Try to edit it → Error: "Cannot edit an approved journal entry"

### Test 3: Both Debit and Credit
- Enter both debit AND credit on same line
- Try to save → Error: "A line cannot have both debit and credit amounts"

---

## 🎓 Understanding the System

### Account Types & Normal Balances
- **Assets**: Normal Debit balance (Debit increases, Credit decreases)
- **Liabilities**: Normal Credit balance (Credit increases, Debit decreases)
- **Equity**: Normal Credit balance (Credit increases, Debit decreases)
- **Revenue**: Normal Credit balance (Credit increases, Debit decreases)
- **Expenses**: Normal Debit balance (Debit increases, Credit decreases)

### Journal Entry Statuses
- **Draft**: Initial state, can be edited
- **Pending**: Awaiting approval, can be edited
- **Approved**: Locked, cannot be edited, appears in reports

### Source Modules
- **GL**: General Ledger (manual entries)
- **Sales**: Auto-posted from sales module
- **Purchases**: Auto-posted from purchases module
- **Inventory**: Auto-posted from inventory module

---

## 🔧 Command Reference

```bash
# Run migrations
php artisan migrate

# Start server
php artisan serve

# Run recurring entries
php artisan accounting:run-recurring-entries

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Compile assets
npm run build
npm run dev  # for development with hot reload
```

---

## 📱 Navigation Guide

### Main Menu Structure
```
Dashboard
├── Setup
│   ├── Branches
│   ├── Account Groups
│   └── Chart of Accounts
├── Transactions
│   └── Journal Entries
└── Reports
    └── Financial Reports
```

---

## 💡 Pro Tips

1. **Always create account groups first** before creating accounts
2. **Approve entries regularly** to see them in reports
3. **Use descriptive descriptions** for easy tracking
4. **Check the difference indicator** when creating entries (must be 0.00)
5. **Use consolidated view** to see overall financial position
6. **Use branch view** to analyze individual branch performance

---

## 🎉 You're Ready!

Your MultiBranch-Accountant system is now set up and ready to use. Start recording transactions and generating financial reports!

For detailed documentation, see `ACCOUNTING_SYSTEM_README.md`.
