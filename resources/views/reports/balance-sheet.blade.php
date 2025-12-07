@extends('layouts.accounting')

@section('title', 'Balance Sheet')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Balance Sheet</h1>
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
        <form method="GET" action="{{ route('reports.balance-sheet') }}">
            <div class="row g-3">
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <label class="form-label">As of Date</label>
                    <input type="date" name="as_of_date" class="form-control" value="{{ $asOfDate }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-magnifying-glass"></i> Generate Report
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Balance Sheet Report -->
<div class="row">
    <!-- Assets Column -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa-solid fa-coins me-2"></i>Assets</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Account Name</th>
                                <th class="text-end">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assets as $item)
                                <tr>
                                    <td><code>{{ $item['account']->code }}</code></td>
                                    <td>{{ $item['account']->name }}</td>
                                    <td class="text-end">{{ number_format($item['balance'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No assets</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-success">
                            <tr>
                                <th colspan="2">Total Assets</th>
                                <th class="text-end">{{ number_format($totalAssets, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Liabilities & Equity Column -->
    <div class="col-md-6 mb-4">
        <!-- Liabilities -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fa-solid fa-credit-card me-2"></i>Liabilities</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Account Name</th>
                                <th class="text-end">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($liabilities as $item)
                                <tr>
                                    <td><code>{{ $item['account']->code }}</code></td>
                                    <td>{{ $item['account']->name }}</td>
                                    <td class="text-end">{{ number_format($item['balance'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No liabilities</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-danger">
                            <tr>
                                <th colspan="2">Total Liabilities</th>
                                <th class="text-end">{{ number_format($totalLiabilities, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Equity -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa-solid fa-landmark me-2"></i>Equity</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Account Name</th>
                                <th class="text-end">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($equities as $item)
                                <tr>
                                    <td><code>{{ $item['account']->code }}</code></td>
                                    <td>{{ $item['account']->name }}</td>
                                    <td class="text-end">{{ number_format($item['balance'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No equity accounts</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-primary">
                            <tr>
                                <th colspan="2">Total Equity</th>
                                <th class="text-end">{{ number_format($totalEquity, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summary Card -->
<div class="card shadow-sm">
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-4">
                <h6 class="text-muted">Total Assets</h6>
                <h4 class="text-success">{{ number_format($totalAssets, 2) }}</h4>
            </div>
            <div class="col-md-4">
                <h6 class="text-muted">Total Liabilities + Equity</h6>
                <h4 class="text-primary">{{ number_format($totalLiabilities + $totalEquity, 2) }}</h4>
            </div>
            <div class="col-md-4">
                <h6 class="text-muted">Difference</h6>
                @php $diff = $totalAssets - ($totalLiabilities + $totalEquity); @endphp
                <h4 class="{{ abs($diff) < 0.01 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($diff, 2) }}
                    @if(abs($diff) < 0.01)
                        <i class="fa-solid fa-check-circle"></i>
                    @else
                        <i class="fa-solid fa-exclamation-triangle"></i>
                    @endif
                </h4>
            </div>
        </div>
    </div>
</div>
@endsection
