@extends('layouts.accounting')

@section('title', 'Trial Balance')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Trial Balance</h1>
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
        <form method="GET" action="{{ route('reports.trial-balance') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Branch</label>
                    <select name="branch_id" class="form-select">
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

<!-- Trial Balance Report -->
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fa-solid fa-scale-balanced me-2"></i>
            Trial Balance as of {{ \Carbon\Carbon::parse($asOfDate)->format('M d, Y') }}
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Account Name</th>
                        <th>Type</th>
                        <th class="text-end">Debit</th>
                        <th class="text-end">Credit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trialBalanceData as $item)
                        <tr>
                            <td><code>{{ $item['account']->code }}</code></td>
                            <td>{{ $item['account']->name }}</td>
                            <td>
                                <span class="badge 
                                    @if($item['account']->type == 'Asset') bg-success
                                    @elseif($item['account']->type == 'Liability') bg-danger
                                    @elseif($item['account']->type == 'Equity') bg-primary
                                    @elseif($item['account']->type == 'Revenue') bg-info
                                    @else bg-warning
                                    @endif">
                                    {{ $item['account']->type }}
                                </span>
                            </td>
                            <td class="text-end">
                                @if($item['debit'] > 0)
                                    {{ number_format($item['debit'], 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-end">
                                @if($item['credit'] > 0)
                                    {{ number_format($item['credit'], 2) }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No accounts with balances found.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <th colspan="3">Total</th>
                        <th class="text-end">{{ number_format($totalDebit, 2) }}</th>
                        <th class="text-end">{{ number_format($totalCredit, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <!-- Balance Check -->
        <div class="mt-4">
            @php $difference = abs($totalDebit - $totalCredit); @endphp
            @if($difference < 0.01)
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check me-2"></i>
                    <strong>Balanced!</strong> Total Debits equal Total Credits.
                </div>
            @else
                <div class="alert alert-danger">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    <strong>Out of Balance!</strong> Difference: {{ number_format($difference, 2) }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
