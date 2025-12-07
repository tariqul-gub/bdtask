<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\AccountGroup;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AccountingSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🇧🇩 বাংলাদেশী ব্যবসায়িক ডেটা সিডিং শুরু হচ্ছে...');
        $this->command->info('   Seeding Bangladeshi Business Data...');
        $this->command->info('');

        // ============================================
        // BANGLADESHI USERS DATA
        // ============================================
        $usersData = [
            [
                'name' => 'মোঃ আব্দুর রহমান (Admin)',
                'email' => 'admin@multibranch.com',
                'password' => 'password123',
            ],
            [
                'name' => 'ফাতেমা আক্তার',
                'email' => 'fatema@multibranch.com',
                'password' => 'password123',
            ],
            [
                'name' => 'মোঃ করিম উদ্দিন',
                'email' => 'karim@multibranch.com',
                'password' => 'password123',
            ],
            [
                'name' => 'সালমা বেগম',
                'email' => 'salma@multibranch.com',
                'password' => 'password123',
            ],
            [
                'name' => 'মোঃ জাহিদ হাসান',
                'email' => 'jahid@multibranch.com',
                'password' => 'password123',
            ],
        ];

        foreach ($usersData as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'email_verified_at' => now(),
            ]);
        }
        $this->command->info('✓ Created ' . count($usersData) . ' users');

        // ============================================
        // BANGLADESHI BRANCHES DATA
        // ============================================
        $branchesData = [
            ['name' => 'প্রধান কার্যালয় - ঢাকা (Head Office)'],
            ['name' => 'চট্টগ্রাম শাখা (Chittagong Branch)'],
            ['name' => 'সিলেট শাখা (Sylhet Branch)'],
            ['name' => 'রাজশাহী শাখা (Rajshahi Branch)'],
            ['name' => 'খুলনা শাখা (Khulna Branch)'],
            ['name' => 'বরিশাল শাখা (Barishal Branch)'],
            ['name' => 'রংপুর শাখা (Rangpur Branch)'],
            ['name' => 'ময়মনসিংহ শাখা (Mymensingh Branch)'],
            ['name' => 'গাজীপুর শাখা (Gazipur Branch)'],
            ['name' => 'নারায়ণগঞ্জ শাখা (Narayanganj Branch)'],
        ];

        foreach ($branchesData as $branch) {
            Branch::create($branch);
        }
        $this->command->info('✓ Created ' . count($branchesData) . ' branches');

        // ============================================
        // ACCOUNT GROUPS - HIERARCHICAL STRUCTURE
        // ============================================
        
        // Level 1: Main Groups
        $assets = AccountGroup::create(['name' => 'সম্পদ (Assets)']);
        $liabilities = AccountGroup::create(['name' => 'দায় (Liabilities)']);
        $equity = AccountGroup::create(['name' => 'মালিকানা স্বত্ব (Equity)']);
        $revenue = AccountGroup::create(['name' => 'আয় (Revenue)']);
        $expenses = AccountGroup::create(['name' => 'ব্যয় (Expenses)']);

        // Level 2: Sub Groups under Assets
        $currentAssets = AccountGroup::create(['name' => 'চলতি সম্পদ (Current Assets)', 'parent_id' => $assets->id]);
        $fixedAssets = AccountGroup::create(['name' => 'স্থায়ী সম্পদ (Fixed Assets)', 'parent_id' => $assets->id]);
        $investments = AccountGroup::create(['name' => 'বিনিয়োগ (Investments)', 'parent_id' => $assets->id]);

        // Level 3: Sub Groups under Current Assets
        $cashBank = AccountGroup::create(['name' => 'নগদ ও ব্যাংক (Cash & Bank)', 'parent_id' => $currentAssets->id]);
        $receivables = AccountGroup::create(['name' => 'প্রাপ্য হিসাব (Receivables)', 'parent_id' => $currentAssets->id]);
        $inventory = AccountGroup::create(['name' => 'মজুদ পণ্য (Inventory)', 'parent_id' => $currentAssets->id]);
        $prepaid = AccountGroup::create(['name' => 'অগ্রিম খরচ (Prepaid Expenses)', 'parent_id' => $currentAssets->id]);

        // Level 3: Sub Groups under Fixed Assets
        $land = AccountGroup::create(['name' => 'জমি ও ভবন (Land & Building)', 'parent_id' => $fixedAssets->id]);
        $machinery = AccountGroup::create(['name' => 'যন্ত্রপাতি (Machinery)', 'parent_id' => $fixedAssets->id]);
        $vehicles = AccountGroup::create(['name' => 'যানবাহন (Vehicles)', 'parent_id' => $fixedAssets->id]);
        $furniture = AccountGroup::create(['name' => 'আসবাবপত্র (Furniture)', 'parent_id' => $fixedAssets->id]);
        $equipment = AccountGroup::create(['name' => 'অফিস সরঞ্জাম (Office Equipment)', 'parent_id' => $fixedAssets->id]);

        // Level 2: Sub Groups under Liabilities
        $currentLiabilities = AccountGroup::create(['name' => 'চলতি দায় (Current Liabilities)', 'parent_id' => $liabilities->id]);
        $longTermLiabilities = AccountGroup::create(['name' => 'দীর্ঘমেয়াদি দায় (Long-term Liabilities)', 'parent_id' => $liabilities->id]);

        // Level 3: Sub Groups under Current Liabilities
        $payables = AccountGroup::create(['name' => 'প্রদেয় হিসাব (Payables)', 'parent_id' => $currentLiabilities->id]);
        $taxPayable = AccountGroup::create(['name' => 'কর প্রদেয় (Tax Payable)', 'parent_id' => $currentLiabilities->id]);
        $provisions = AccountGroup::create(['name' => 'সঞ্চিতি (Provisions)', 'parent_id' => $currentLiabilities->id]);

        // Level 2: Sub Groups under Equity
        $capital = AccountGroup::create(['name' => 'মূলধন (Capital)', 'parent_id' => $equity->id]);
        $reserves = AccountGroup::create(['name' => 'সংরক্ষিত তহবিল (Reserves)', 'parent_id' => $equity->id]);

        // Level 2: Sub Groups under Revenue
        $operatingRevenue = AccountGroup::create(['name' => 'পরিচালন আয় (Operating Revenue)', 'parent_id' => $revenue->id]);
        $otherRevenue = AccountGroup::create(['name' => 'অন্যান্য আয় (Other Revenue)', 'parent_id' => $revenue->id]);

        // Level 2: Sub Groups under Expenses
        $operatingExpenses = AccountGroup::create(['name' => 'পরিচালন ব্যয় (Operating Expenses)', 'parent_id' => $expenses->id]);
        $adminExpenses = AccountGroup::create(['name' => 'প্রশাসনিক ব্যয় (Admin Expenses)', 'parent_id' => $expenses->id]);
        $financialExpenses = AccountGroup::create(['name' => 'আর্থিক ব্যয় (Financial Expenses)', 'parent_id' => $expenses->id]);
        $sellingExpenses = AccountGroup::create(['name' => 'বিক্রয় ব্যয় (Selling Expenses)', 'parent_id' => $expenses->id]);

        $this->command->info('✓ Created hierarchical account groups');

        // ============================================
        // CHART OF ACCOUNTS - BANGLADESHI STANDARD
        // ============================================
        $accountsData = [
            // ========== ASSETS ==========
            // Cash & Bank
            ['code' => '1001', 'name' => 'নগদ তহবিল (Cash in Hand)', 'type' => 'Asset', 'group_id' => $cashBank->id],
            ['code' => '1002', 'name' => 'পেটি ক্যাশ (Petty Cash)', 'type' => 'Asset', 'group_id' => $cashBank->id],
            ['code' => '1010', 'name' => 'সোনালী ব্যাংক - চলতি হিসাব (Sonali Bank - Current)', 'type' => 'Asset', 'group_id' => $cashBank->id],
            ['code' => '1011', 'name' => 'জনতা ব্যাংক - চলতি হিসাব (Janata Bank - Current)', 'type' => 'Asset', 'group_id' => $cashBank->id],
            ['code' => '1012', 'name' => 'ব্র্যাক ব্যাংক - চলতি হিসাব (BRAC Bank - Current)', 'type' => 'Asset', 'group_id' => $cashBank->id],
            ['code' => '1013', 'name' => 'ডাচ-বাংলা ব্যাংক - চলতি হিসাব (Dutch-Bangla Bank)', 'type' => 'Asset', 'group_id' => $cashBank->id],
            ['code' => '1014', 'name' => 'ইসলামী ব্যাংক - চলতি হিসাব (Islami Bank - Current)', 'type' => 'Asset', 'group_id' => $cashBank->id],
            ['code' => '1020', 'name' => 'বিকাশ একাউন্ট (bKash Account)', 'type' => 'Asset', 'group_id' => $cashBank->id],
            ['code' => '1021', 'name' => 'নগদ একাউন্ট (Nagad Account)', 'type' => 'Asset', 'group_id' => $cashBank->id],
            ['code' => '1022', 'name' => 'রকেট একাউন্ট (Rocket Account)', 'type' => 'Asset', 'group_id' => $cashBank->id],

            // Receivables
            ['code' => '1101', 'name' => 'বিক্রয় প্রাপ্য (Accounts Receivable - Trade)', 'type' => 'Asset', 'group_id' => $receivables->id],
            ['code' => '1102', 'name' => 'কর্মচারী অগ্রিম (Employee Advances)', 'type' => 'Asset', 'group_id' => $receivables->id],
            ['code' => '1103', 'name' => 'অন্যান্য প্রাপ্য (Other Receivables)', 'type' => 'Asset', 'group_id' => $receivables->id],
            ['code' => '1104', 'name' => 'সন্দেহজনক ঋণের জন্য সঞ্চিতি (Provision for Doubtful Debts)', 'type' => 'Asset', 'group_id' => $receivables->id],

            // Inventory
            ['code' => '1201', 'name' => 'কাঁচামাল মজুদ (Raw Materials)', 'type' => 'Asset', 'group_id' => $inventory->id],
            ['code' => '1202', 'name' => 'চলমান কাজ (Work in Progress)', 'type' => 'Asset', 'group_id' => $inventory->id],
            ['code' => '1203', 'name' => 'সমাপ্ত পণ্য (Finished Goods)', 'type' => 'Asset', 'group_id' => $inventory->id],
            ['code' => '1204', 'name' => 'বাণিজ্যিক পণ্য (Trading Goods)', 'type' => 'Asset', 'group_id' => $inventory->id],
            ['code' => '1205', 'name' => 'প্যাকেজিং সামগ্রী (Packaging Materials)', 'type' => 'Asset', 'group_id' => $inventory->id],

            // Prepaid Expenses
            ['code' => '1301', 'name' => 'অগ্রিম ভাড়া (Prepaid Rent)', 'type' => 'Asset', 'group_id' => $prepaid->id],
            ['code' => '1302', 'name' => 'অগ্রিম বীমা (Prepaid Insurance)', 'type' => 'Asset', 'group_id' => $prepaid->id],
            ['code' => '1303', 'name' => 'অগ্রিম কর (Advance Income Tax)', 'type' => 'Asset', 'group_id' => $prepaid->id],
            ['code' => '1304', 'name' => 'অগ্রিম ভ্যাট (Advance VAT)', 'type' => 'Asset', 'group_id' => $prepaid->id],

            // Fixed Assets - Land & Building
            ['code' => '1501', 'name' => 'জমি (Land)', 'type' => 'Asset', 'group_id' => $land->id],
            ['code' => '1502', 'name' => 'ভবন (Building)', 'type' => 'Asset', 'group_id' => $land->id],
            ['code' => '1503', 'name' => 'ভবন অবচয় (Accumulated Depreciation - Building)', 'type' => 'Asset', 'group_id' => $land->id],

            // Fixed Assets - Machinery
            ['code' => '1601', 'name' => 'যন্ত্রপাতি (Plant & Machinery)', 'type' => 'Asset', 'group_id' => $machinery->id],
            ['code' => '1602', 'name' => 'যন্ত্রপাতি অবচয় (Accumulated Depreciation - Machinery)', 'type' => 'Asset', 'group_id' => $machinery->id],

            // Fixed Assets - Vehicles
            ['code' => '1701', 'name' => 'মোটরগাড়ি (Motor Vehicles)', 'type' => 'Asset', 'group_id' => $vehicles->id],
            ['code' => '1702', 'name' => 'মোটরসাইকেল (Motorcycles)', 'type' => 'Asset', 'group_id' => $vehicles->id],
            ['code' => '1703', 'name' => 'যানবাহন অবচয় (Accumulated Depreciation - Vehicles)', 'type' => 'Asset', 'group_id' => $vehicles->id],

            // Fixed Assets - Furniture
            ['code' => '1801', 'name' => 'আসবাবপত্র (Furniture & Fixtures)', 'type' => 'Asset', 'group_id' => $furniture->id],
            ['code' => '1802', 'name' => 'আসবাবপত্র অবচয় (Accumulated Depreciation - Furniture)', 'type' => 'Asset', 'group_id' => $furniture->id],

            // Fixed Assets - Equipment
            ['code' => '1901', 'name' => 'কম্পিউটার ও আইটি সরঞ্জাম (Computer & IT Equipment)', 'type' => 'Asset', 'group_id' => $equipment->id],
            ['code' => '1902', 'name' => 'এয়ার কন্ডিশনার (Air Conditioner)', 'type' => 'Asset', 'group_id' => $equipment->id],
            ['code' => '1903', 'name' => 'অফিস সরঞ্জাম অবচয় (Accumulated Depreciation - Equipment)', 'type' => 'Asset', 'group_id' => $equipment->id],

            // Investments
            ['code' => '1951', 'name' => 'এফডিআর বিনিয়োগ (FDR Investment)', 'type' => 'Asset', 'group_id' => $investments->id],
            ['code' => '1952', 'name' => 'সঞ্চয়পত্র বিনিয়োগ (Sanchayapatra Investment)', 'type' => 'Asset', 'group_id' => $investments->id],
            ['code' => '1953', 'name' => 'শেয়ার বিনিয়োগ (Share Investment)', 'type' => 'Asset', 'group_id' => $investments->id],

            // ========== LIABILITIES ==========
            // Payables
            ['code' => '2001', 'name' => 'সরবরাহকারী প্রদেয় (Accounts Payable - Trade)', 'type' => 'Liability', 'group_id' => $payables->id],
            ['code' => '2002', 'name' => 'বেতন প্রদেয় (Salaries Payable)', 'type' => 'Liability', 'group_id' => $payables->id],
            ['code' => '2003', 'name' => 'বোনাস প্রদেয় (Bonus Payable)', 'type' => 'Liability', 'group_id' => $payables->id],
            ['code' => '2004', 'name' => 'অন্যান্য প্রদেয় (Other Payables)', 'type' => 'Liability', 'group_id' => $payables->id],

            // Tax Payable
            ['code' => '2101', 'name' => 'ভ্যাট প্রদেয় (VAT Payable)', 'type' => 'Liability', 'group_id' => $taxPayable->id],
            ['code' => '2102', 'name' => 'আয়কর প্রদেয় (Income Tax Payable)', 'type' => 'Liability', 'group_id' => $taxPayable->id],
            ['code' => '2103', 'name' => 'উৎসে কর প্রদেয় (TDS Payable)', 'type' => 'Liability', 'group_id' => $taxPayable->id],
            ['code' => '2104', 'name' => 'এআইটি প্রদেয় (AIT Payable)', 'type' => 'Liability', 'group_id' => $taxPayable->id],

            // Provisions
            ['code' => '2201', 'name' => 'গ্র্যাচুইটি সঞ্চিতি (Provision for Gratuity)', 'type' => 'Liability', 'group_id' => $provisions->id],
            ['code' => '2202', 'name' => 'ছুটি নগদায়ন সঞ্চিতি (Provision for Leave Encashment)', 'type' => 'Liability', 'group_id' => $provisions->id],

            // Long-term Liabilities
            ['code' => '2501', 'name' => 'ব্যাংক ঋণ (Bank Loan)', 'type' => 'Liability', 'group_id' => $longTermLiabilities->id],
            ['code' => '2502', 'name' => 'পরিচালক ঋণ (Director\'s Loan)', 'type' => 'Liability', 'group_id' => $longTermLiabilities->id],
            ['code' => '2503', 'name' => 'দীর্ঘমেয়াদি অগ্রিম (Long-term Advances)', 'type' => 'Liability', 'group_id' => $longTermLiabilities->id],

            // ========== EQUITY ==========
            // Capital
            ['code' => '3001', 'name' => 'মালিকের মূলধন (Owner\'s Capital)', 'type' => 'Equity', 'group_id' => $capital->id],
            ['code' => '3002', 'name' => 'শেয়ার মূলধন (Share Capital)', 'type' => 'Equity', 'group_id' => $capital->id],
            ['code' => '3003', 'name' => 'মালিকের উত্তোলন (Owner\'s Drawings)', 'type' => 'Equity', 'group_id' => $capital->id],

            // Reserves
            ['code' => '3101', 'name' => 'সংরক্ষিত আয় (Retained Earnings)', 'type' => 'Equity', 'group_id' => $reserves->id],
            ['code' => '3102', 'name' => 'সাধারণ রিজার্ভ (General Reserve)', 'type' => 'Equity', 'group_id' => $reserves->id],
            ['code' => '3103', 'name' => 'শেয়ার প্রিমিয়াম (Share Premium)', 'type' => 'Equity', 'group_id' => $reserves->id],

            // ========== REVENUE ==========
            // Operating Revenue
            ['code' => '4001', 'name' => 'পণ্য বিক্রয় (Sales - Goods)', 'type' => 'Revenue', 'group_id' => $operatingRevenue->id],
            ['code' => '4002', 'name' => 'সেবা বিক্রয় (Sales - Services)', 'type' => 'Revenue', 'group_id' => $operatingRevenue->id],
            ['code' => '4003', 'name' => 'রপ্তানি বিক্রয় (Export Sales)', 'type' => 'Revenue', 'group_id' => $operatingRevenue->id],
            ['code' => '4004', 'name' => 'বিক্রয় ফেরত (Sales Return)', 'type' => 'Revenue', 'group_id' => $operatingRevenue->id],
            ['code' => '4005', 'name' => 'বিক্রয় ছাড় (Sales Discount)', 'type' => 'Revenue', 'group_id' => $operatingRevenue->id],

            // Other Revenue
            ['code' => '4101', 'name' => 'সুদ আয় (Interest Income)', 'type' => 'Revenue', 'group_id' => $otherRevenue->id],
            ['code' => '4102', 'name' => 'ভাড়া আয় (Rental Income)', 'type' => 'Revenue', 'group_id' => $otherRevenue->id],
            ['code' => '4103', 'name' => 'কমিশন আয় (Commission Income)', 'type' => 'Revenue', 'group_id' => $otherRevenue->id],
            ['code' => '4104', 'name' => 'লভ্যাংশ আয় (Dividend Income)', 'type' => 'Revenue', 'group_id' => $otherRevenue->id],
            ['code' => '4105', 'name' => 'বিবিধ আয় (Miscellaneous Income)', 'type' => 'Revenue', 'group_id' => $otherRevenue->id],
            ['code' => '4106', 'name' => 'বিনিময় লাভ (Exchange Gain)', 'type' => 'Revenue', 'group_id' => $otherRevenue->id],
            ['code' => '4107', 'name' => 'সম্পদ বিক্রয় লাভ (Gain on Sale of Assets)', 'type' => 'Revenue', 'group_id' => $otherRevenue->id],

            // ========== EXPENSES ==========
            // Operating Expenses (Cost of Sales)
            ['code' => '5001', 'name' => 'বিক্রিত পণ্যের ব্যয় (Cost of Goods Sold)', 'type' => 'Expense', 'group_id' => $operatingExpenses->id],
            ['code' => '5002', 'name' => 'ক্রয় (Purchases)', 'type' => 'Expense', 'group_id' => $operatingExpenses->id],
            ['code' => '5003', 'name' => 'ক্রয় ফেরত (Purchase Return)', 'type' => 'Expense', 'group_id' => $operatingExpenses->id],
            ['code' => '5004', 'name' => 'ক্রয় ছাড় (Purchase Discount)', 'type' => 'Expense', 'group_id' => $operatingExpenses->id],
            ['code' => '5005', 'name' => 'আমদানি শুল্ক (Import Duty)', 'type' => 'Expense', 'group_id' => $operatingExpenses->id],
            ['code' => '5006', 'name' => 'পরিবহন ব্যয় - ক্রয় (Freight Inward)', 'type' => 'Expense', 'group_id' => $operatingExpenses->id],
            ['code' => '5007', 'name' => 'প্রত্যক্ষ শ্রম (Direct Labor)', 'type' => 'Expense', 'group_id' => $operatingExpenses->id],
            ['code' => '5008', 'name' => 'কারখানা ওভারহেড (Factory Overhead)', 'type' => 'Expense', 'group_id' => $operatingExpenses->id],

            // Admin Expenses
            ['code' => '5101', 'name' => 'বেতন ও মজুরি (Salaries & Wages)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],
            ['code' => '5102', 'name' => 'অফিস ভাড়া (Office Rent)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],
            ['code' => '5103', 'name' => 'বিদ্যুৎ বিল (Electricity Bill)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],
            ['code' => '5104', 'name' => 'পানি বিল (Water Bill)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],
            ['code' => '5105', 'name' => 'গ্যাস বিল (Gas Bill)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],
            ['code' => '5106', 'name' => 'টেলিফোন ও ইন্টারনেট (Telephone & Internet)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],
            ['code' => '5107', 'name' => 'স্টেশনারি ও অফিস সাপ্লাই (Stationery & Office Supplies)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],
            ['code' => '5108', 'name' => 'মেরামত ও রক্ষণাবেক্ষণ (Repairs & Maintenance)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],
            ['code' => '5109', 'name' => 'বীমা ব্যয় (Insurance Expense)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],
            ['code' => '5110', 'name' => 'অবচয় ব্যয় (Depreciation Expense)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],
            ['code' => '5111', 'name' => 'আইনি ও পেশাদার ফি (Legal & Professional Fees)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],
            ['code' => '5112', 'name' => 'অডিট ফি (Audit Fees)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],
            ['code' => '5113', 'name' => 'নিরাপত্তা ব্যয় (Security Expense)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],
            ['code' => '5114', 'name' => 'পরিচ্ছন্নতা ব্যয় (Cleaning Expense)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],
            ['code' => '5115', 'name' => 'প্রশিক্ষণ ব্যয় (Training Expense)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],
            ['code' => '5116', 'name' => 'ভ্রমণ ও বাহন ব্যয় (Travel & Conveyance)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],
            ['code' => '5117', 'name' => 'আপ্যায়ন ব্যয় (Entertainment Expense)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],
            ['code' => '5118', 'name' => 'চা-নাস্তা ব্যয় (Tea & Refreshment)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],
            ['code' => '5119', 'name' => 'মুদ্রণ ও প্রকাশনা (Printing & Publication)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],
            ['code' => '5120', 'name' => 'বিবিধ ব্যয় (Miscellaneous Expense)', 'type' => 'Expense', 'group_id' => $adminExpenses->id],

            // Financial Expenses
            ['code' => '5201', 'name' => 'ব্যাংক চার্জ (Bank Charges)', 'type' => 'Expense', 'group_id' => $financialExpenses->id],
            ['code' => '5202', 'name' => 'ঋণের সুদ (Interest on Loan)', 'type' => 'Expense', 'group_id' => $financialExpenses->id],
            ['code' => '5203', 'name' => 'এলসি চার্জ (LC Charges)', 'type' => 'Expense', 'group_id' => $financialExpenses->id],
            ['code' => '5204', 'name' => 'বিনিময় ক্ষতি (Exchange Loss)', 'type' => 'Expense', 'group_id' => $financialExpenses->id],

            // Selling & Distribution Expenses
            ['code' => '5301', 'name' => 'বিজ্ঞাপন ব্যয় (Advertisement Expense)', 'type' => 'Expense', 'group_id' => $sellingExpenses->id],
            ['code' => '5302', 'name' => 'বিপণন ব্যয় (Marketing Expense)', 'type' => 'Expense', 'group_id' => $sellingExpenses->id],
            ['code' => '5303', 'name' => 'বিক্রয় কমিশন (Sales Commission)', 'type' => 'Expense', 'group_id' => $sellingExpenses->id],
            ['code' => '5304', 'name' => 'পরিবহন ব্যয় - বিক্রয় (Freight Outward)', 'type' => 'Expense', 'group_id' => $sellingExpenses->id],
            ['code' => '5305', 'name' => 'প্যাকেজিং ব্যয় (Packaging Expense)', 'type' => 'Expense', 'group_id' => $sellingExpenses->id],
            ['code' => '5306', 'name' => 'কুরিয়ার ব্যয় (Courier Expense)', 'type' => 'Expense', 'group_id' => $sellingExpenses->id],
            ['code' => '5307', 'name' => 'প্রদর্শনী ব্যয় (Exhibition Expense)', 'type' => 'Expense', 'group_id' => $sellingExpenses->id],
        ];

        foreach ($accountsData as $account) {
            Account::create([
                'code' => $account['code'],
                'name' => $account['name'],
                'type' => $account['type'],
                'account_group_id' => $account['group_id'],
            ]);
        }

        $this->command->info('✓ Created ' . count($accountsData) . ' accounts');

        // ============================================
        // SUMMARY
        // ============================================
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('    🇧🇩 বাংলাদেশী হিসাবরক্ষণ সিস্টেম সফলভাবে সিড করা হয়েছে!');
        $this->command->info('       Bangladeshi Accounting System Seeded Successfully!');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('');
        $this->command->info('📊 সারসংক্ষেপ (Summary):');
        $this->command->info('   ├── 👤 Users: ' . count($usersData));
        $this->command->info('   ├── 🏢 Branches: ' . count($branchesData));
        $this->command->info('   ├── 📁 Account Groups: ' . AccountGroup::count());
        $this->command->info('   └── 📋 Accounts: ' . count($accountsData));
        $this->command->info('');
        $this->command->info('🔐 লগইন তথ্য (Login Credentials):');
        $this->command->info('   ├── Email: admin@multibranch.com');
        $this->command->info('   └── Password: password123');
        $this->command->info('');
        $this->command->info('🚀 এখন জার্নাল এন্ট্রি তৈরি শুরু করুন!');
        $this->command->info('   Start creating journal entries now!');
        $this->command->info('');
    }
}
