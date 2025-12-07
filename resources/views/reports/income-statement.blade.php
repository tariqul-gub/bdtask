@extends('layouts.accounting')

@section('title', 'Income Statement')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Income Statement</h1>
    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Back to Reports
    </a>
</div>

<!-- Filter Card -->
<div class="card shadow-sm mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fa-solid fa-filter me-2"></i>Filter Options</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.income-statement') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Branch</label>
                    <select name="branch_id" class="form-select">
                        <option value="consolidated">All Branches (Consolidated)</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $selectedBranch == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-magnifying-glass"></i> Generate Report
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Income Statement Report -->
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fa-solid fa-file-invoice-dollar me-2"></i>
            Income Statement
        </h5>
        <small class="text-muted">Period: {{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($toDate)->format('M d, Y') }}</small>
    </div>
    <div class="card-body">
        <!-- Revenue Section -->
        <h5 class="text-success mb-3"><i class="fa-solid fa-arrow-trend-up me-2"></i>Revenue</h5>
        <div class="table-responsive mb-4">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Account Name</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($revenues as $item)
                        <tr>
                            <td><code>{{ $item['account']->code }}</code></td>
                            <td>{{ $item['account']->name }}</td>
                            <td class="text-end text-success">{{ number_format($item['amount'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No revenue recorded</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-success">
                    <tr>
                        <th colspan="2">Total Revenue</th>
                        <th class="text-end">{{ number_format($totalRevenue, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Expense Section -->
        <h5 class="text-danger mb-3"><i class="fa-solid fa-arrow-trend-down me-2"></i>Expenses</h5>
        <div class="table-responsive mb-4">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Account Name</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $item)
                        <tr>
                            <td><code>{{ $item['account']->code }}</code></td>
                            <td>{{ $item['account']->name }}</td>
                            <td class="text-end text-danger">{{ number_format($item['amount'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No expenses recorded</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-danger">
                    <tr>
                        <th colspan="2">Total Expenses</th>
                        <th class="text-end">{{ number_format($totalExpense, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Net Income -->
        <div class="card {{ $netIncome >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="mb-0">
                            <i class="fa-solid fa-{{ $netIncome >= 0 ? 'circle-check' : 'circle-xmark' }} me-2"></i>
                            Net {{ $netIncome >= 0 ? 'Income' : 'Loss' }}
                        </h4>
                    </div>
                    <div class="col-auto">
                        <h3 class="mb-0">{{ number_format(abs($netIncome), 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
