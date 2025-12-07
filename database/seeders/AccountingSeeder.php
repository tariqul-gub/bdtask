<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\AccountGroup;
use App\Models\Account;

class AccountingSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding accounting system...');

        $headOffice = Branch::create(['name' => 'Head Office']);
        $branchA = Branch::create(['name' => 'Branch A']);
        $branchB = Branch::create(['name' => 'Branch B']);
        
        $this->command->info('✓ Created branches');

        $currentAssets = AccountGroup::create(['name' => 'Current Assets']);
        $fixedAssets = AccountGroup::create(['name' => 'Fixed Assets']);
        $currentLiabilities = AccountGroup::create(['name' => 'Current Liabilities']);
        $longTermLiabilities = AccountGroup::create(['name' => 'Long-term Liabilities']);
        $equity = AccountGroup::create(['name' => 'Owner\'s Equity']);
        $revenue = AccountGroup::create(['name' => 'Operating Revenue']);
        $expenses = AccountGroup::create(['name' => 'Operating Expenses']);
        
        AccountGroup::create(['name' => 'Cash & Bank', 'parent_id' => $currentAssets->id]);
        AccountGroup::create(['name' => 'Receivables', 'parent_id' => $currentAssets->id]);
        AccountGroup::create(['name' => 'Property & Equipment', 'parent_id' => $fixedAssets->id]);
        
        $this->command->info('✓ Created account groups');

        $accounts = [
            ['code' => '1000', 'name' => 'Cash', 'type' => 'Asset', 'group_id' => $currentAssets->id],
            ['code' => '1010', 'name' => 'Petty Cash', 'type' => 'Asset', 'group_id' => $currentAssets->id],
            ['code' => '1100', 'name' => 'Accounts Receivable', 'type' => 'Asset', 'group_id' => $currentAssets->id],
            ['code' => '1200', 'name' => 'Inventory', 'type' => 'Asset', 'group_id' => $currentAssets->id],
            ['code' => '1500', 'name' => 'Office Equipment', 'type' => 'Asset', 'group_id' => $fixedAssets->id],
            ['code' => '1510', 'name' => 'Furniture & Fixtures', 'type' => 'Asset', 'group_id' => $fixedAssets->id],
            ['code' => '1520', 'name' => 'Vehicles', 'type' => 'Asset', 'group_id' => $fixedAssets->id],
            
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'Liability', 'group_id' => $currentLiabilities->id],
            ['code' => '2100', 'name' => 'Salaries Payable', 'type' => 'Liability', 'group_id' => $currentLiabilities->id],
            ['code' => '2200', 'name' => 'Sales Tax Payable', 'type' => 'Liability', 'group_id' => $currentLiabilities->id],
            ['code' => '2500', 'name' => 'Bank Loan', 'type' => 'Liability', 'group_id' => $longTermLiabilities->id],
            
            ['code' => '3000', 'name' => 'Owner\'s Capital', 'type' => 'Equity', 'group_id' => $equity->id],
            ['code' => '3100', 'name' => 'Retained Earnings', 'type' => 'Equity', 'group_id' => $equity->id],
            ['code' => '3200', 'name' => 'Owner\'s Drawings', 'type' => 'Equity', 'group_id' => $equity->id],
            
            ['code' => '4000', 'name' => 'Sales Revenue', 'type' => 'Revenue', 'group_id' => $revenue->id],
            ['code' => '4100', 'name' => 'Service Revenue', 'type' => 'Revenue', 'group_id' => $revenue->id],
            ['code' => '4200', 'name' => 'Interest Income', 'type' => 'Revenue', 'group_id' => $revenue->id],
            
            ['code' => '5000', 'name' => 'Cost of Goods Sold', 'type' => 'Expense', 'group_id' => $expenses->id],
            ['code' => '5100', 'name' => 'Salaries Expense', 'type' => 'Expense', 'group_id' => $expenses->id],
            ['code' => '5200', 'name' => 'Rent Expense', 'type' => 'Expense', 'group_id' => $expenses->id],
            ['code' => '5300', 'name' => 'Utilities Expense', 'type' => 'Expense', 'group_id' => $expenses->id],
            ['code' => '5400', 'name' => 'Office Supplies Expense', 'type' => 'Expense', 'group_id' => $expenses->id],
            ['code' => '5500', 'name' => 'Depreciation Expense', 'type' => 'Expense', 'group_id' => $expenses->id],
            ['code' => '5600', 'name' => 'Insurance Expense', 'type' => 'Expense', 'group_id' => $expenses->id],
            ['code' => '5700', 'name' => 'Advertising Expense', 'type' => 'Expense', 'group_id' => $expenses->id],
        ];

        foreach ($accounts as $account) {
            Account::create([
                'code' => $account['code'],
                'name' => $account['name'],
                'type' => $account['type'],
                'account_group_id' => $account['group_id'],
            ]);
        }
        
        $this->command->info('✓ Created ' . count($accounts) . ' accounts');
        
        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('Accounting system seeded successfully!');
        $this->command->info('========================================');
        $this->command->info('Branches: 3');
        $this->command->info('Account Groups: 10');
        $this->command->info('Accounts: ' . count($accounts));
        $this->command->info('');
        $this->command->info('You can now start creating journal entries!');
    }
}
