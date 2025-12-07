@extends('layouts.accounting')

@section('title', 'Financial Reports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Financial Reports</h1>
</div>

<!-- Report Navigation -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <a href="{{ route('reports.index') }}" class="card shadow-sm text-decoration-none h-100 border-primary">
            <div class="card-body text-center">
                <i class="fa-solid fa-chart-bar fa-2x text-primary mb-2"></i>
                <h6 class="mb-0">Account Balances</h6>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('reports.trial-balance') }}" class="card shadow-sm text-decoration-none h-100">
            <div class="card-body text-center">
                <i class="fa-solid fa-scale-balanced fa-2x text-success mb-2"></i>
                <h6 class="mb-0">Trial Balance</h6>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('reports.income-statement') }}" class="card shadow-sm text-decoration-none h-100">
            <div class="card-body text-center">
                <i class="fa-solid fa-file-invoice-dollar fa-2x text-info mb-2"></i>
                <h6 class="mb-0">Income Statement</h6>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('reports.balance-sheet') }}" class="card shadow-sm text-decoration-none h-100">
            <div class="card-body text-center">
                <i class="fa-solid fa-building-columns fa-2x text-warning mb-2"></i>
                <h6 class="mb-0">Balance Sheet</h6>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('reports.ledger') }}" class="card shadow-sm text-decoration-none h-100">
            <div class="card-body text-center">
                <i class="fa-solid fa-book fa-2x text-danger mb-2"></i>
                <h6 class="mb-0">Account Ledger</h6>
            </div>
        </a>
    </div>
</div>

<!-- Filter Card -->
<div class="card shadow-sm mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fa-solid fa-filter me-2"></i>Filter Options</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('reports.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Branch</label>
                    <select class="form-select" name="branch_id">
                        <option value="consolidated" {{ $selectedBranch == 'consolidated' ? 'selected' : '' }}>
                            All Branches (Consolidated)
                        </option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $selectedBranch == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Account Type</label>
                    <select class="form-select" name="account_type">
                        <option value="">All Types</option>
                        <option value="Asset" {{ $accountType == 'Asset' ? 'selected' : '' }}>Asset</option>
                        <option value="Liability" {{ $accountType == 'Liability' ? 'selected' : '' }}>Liability</option>
                        <option value="Equity" {{ $accountType == 'Equity' ? 'selected' : '' }}>Equity</option>
                        <option value="Revenue" {{ $accountType == 'Revenue' ? 'selected' : '' }}>Revenue</option>
                        <option value="Expense" {{ $accountType == 'Expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">From Date</label>
                    <input type="date" class="form-control" name="from_date" value="{{ $fromDate }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">To Date</label>
                    <input type="date" class="form-control" name="to_date" value="{{ $toDate }}">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-magnifying-glass"></i> Filter
                    </button>
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-rotate"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">Account Balances</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Account Name</th>
                        <th>Type</th>
                        <th>Group</th>
                        <th class="text-end">Opening Debit</th>
                        <th class="text-end">Opening Credit</th>
                        <th class="text-end">Period Debit</th>
                        <th class="text-end">Period Credit</th>
                        <th class="text-end">Current Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalBalance = 0;
                        $assetTotal = 0;
                        $liabilityTotal = 0;
                        $equityTotal = 0;
                        $revenueTotal = 0;
                        $expenseTotal = 0;
                    @endphp
                    
                    @foreach($balances as $balance)
                        @php
                            $totalBalance += $balance['current_balance'];
                            
                            switch($balance['account']->type) {
                                case 'Asset':
                                    $assetTotal += $balance['current_balance'];
                                    break;
                                case 'Liability':
                                    $liabilityTotal += $balance['current_balance'];
                                    break;
                                case 'Equity':
                                    $equityTotal += $balance['current_balance'];
                                    break;
                                case 'Revenue':
                                    $revenueTotal += $balance['current_balance'];
                                    break;
                                case 'Expense':
                                    $expenseTotal += $balance['current_balance'];
                                    break;
                            }
                        @endphp
                        
                        <tr>
                            <td><code>{{ $balance['account']->code }}</code></td>
                            <td>{{ $balance['account']->name }}</td>
                            <td>
                                <span class="badge 
                                    @if($balance['account']->type == 'Asset') bg-success
                                    @elseif($balance['account']->type == 'Liability') bg-danger
                                    @elseif($balance['account']->type == 'Equity') bg-primary
                                    @elseif($balance['account']->type == 'Revenue') bg-info
                                    @else bg-warning
                                    @endif">
                                    {{ $balance['account']->type }}
                                </span>
                            </td>
                            <td>{{ $balance['account']->accountGroup->name }}</td>
                            <td class="text-end">{{ number_format($balance['opening_debit'], 2) }}</td>
                            <td class="text-end">{{ number_format($balance['opening_credit'], 2) }}</td>
                            <td class="text-end">{{ number_format($balance['period_debit'], 2) }}</td>
                            <td class="text-end">{{ number_format($balance['period_credit'], 2) }}</td>
                            <td class="text-end">
                                <strong class="{{ $balance['current_balance'] < 0 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format(abs($balance['current_balance']), 2) }}
                                    {{ $balance['current_balance'] < 0 ? 'CR' : 'DR' }}
                                </strong>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr class="fw-bold">
                        <td colspan="8" class="text-end">Total Assets:</td>
                        <td class="text-end text-success">{{ number_format($assetTotal, 2) }}</td>
                    </tr>
                    <tr class="fw-bold">
                        <td colspan="8" class="text-end">Total Liabilities:</td>
                        <td class="text-end text-danger">{{ number_format($liabilityTotal, 2) }}</td>
                    </tr>
                    <tr class="fw-bold">
                        <td colspan="8" class="text-end">Total Equity:</td>
                        <td class="text-end text-primary">{{ number_format($equityTotal, 2) }}</td>
                    </tr>
                    <tr class="fw-bold">
                        <td colspan="8" class="text-end">Total Revenue:</td>
                        <td class="text-end text-info">{{ number_format($revenueTotal, 2) }}</td>
                    </tr>
                    <tr class="fw-bold">
                        <td colspan="8" class="text-end">Total Expenses:</td>
                        <td class="text-end text-warning">{{ number_format($expenseTotal, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Report Information</h6>
                <ul class="mb-0">
                    <li><strong>Opening Balance:</strong> Initial balances set for accounts at the beginning of the period</li>
                    <li><strong>Period Activity:</strong> Debits and credits from approved journal entries</li>
                    <li><strong>Current Balance:</strong> Net balance calculated based on account type (Asset/Expense = DR - CR, Liability/Equity/Revenue = CR - DR)</li>
                    <li><strong>Multi-Branch:</strong> Switch between consolidated view (all branches) or individual branch view using the dropdown above</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
