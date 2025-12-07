@extends('layouts.accounting')

@section('title', 'Create Journal Entry')

@section('content')
<div class="mb-4">
    <h1 class="h2">Create New Journal Entry</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('journal-entries.index') }}">Journal Entries</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

<form action="{{ route('journal-entries.store') }}" method="POST" id="journalEntryForm">
    @csrf
    
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Entry Header</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
                    <select class="form-select @error('branch_id') is-invalid @enderror" 
                            id="branch_id" 
                            name="branch_id" 
                            required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="entry_date" class="form-label">Entry Date <span class="text-danger">*</span></label>
                    <input type="date" 
                           class="form-control @error('entry_date') is-invalid @enderror" 
                           id="entry_date" 
                           name="entry_date" 
                           value="{{ old('entry_date', date('Y-m-d')) }}" 
                           required>
                    @error('entry_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" 
                          name="description" 
                          rows="3" 
                          required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Journal Lines</h5>
            <button type="button" class="btn btn-sm btn-light" onclick="addLine()">
                <i class="bi bi-plus-circle"></i> Add Line
            </button>
        </div>
        <div class="card-body">
            @error('lines')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            
            <div class="table-responsive">
                <table class="table table-bordered" id="linesTable">
                    <thead class="table-light">
                        <tr>
                            <th width="40%">Account</th>
                            <th width="25%">Debit</th>
                            <th width="25%">Credit</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody id="linesTableBody">
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th class="text-end">Totals:</th>
                            <th><span id="totalDebit" class="fw-bold">0.00</span></th>
                            <th><span id="totalCredit" class="fw-bold">0.00</span></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th class="text-end">Difference:</th>
                            <th colspan="3">
                                <span id="difference" class="fw-bold">0.00</span>
                                <small class="text-muted ms-2">(Must be 0.00 for balanced entry)</small>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Save Journal Entry
        </button>
        <a href="{{ route('journal-entries.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancel
        </a>
    </div>
</form>

<script>
let lineCounter = 0;
const accounts = @json($accounts);

function addLine() {
    const tbody = document.getElementById('linesTableBody');
    const row = document.createElement('tr');
    row.id = `line-${lineCounter}`;
    
    row.innerHTML = `
        <td>
            <select class="form-select form-select-sm" name="lines[${lineCounter}][account_id]" required onchange="updateTotals()">
                <option value="">-- Select Account --</option>
                ${accounts.map(acc => `<option value="${acc.id}">${acc.code} - ${acc.name}</option>`).join('')}
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm debit-input" 
                   name="lines[${lineCounter}][debit]" 
                   step="0.01" 
                   min="0" 
                   value="0.00" 
                   onchange="updateTotals()" 
                   required>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm credit-input" 
                   name="lines[${lineCounter}][credit]" 
                   step="0.01" 
                   min="0" 
                   value="0.00" 
                   onchange="updateTotals()" 
                   required>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeLine(${lineCounter})">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    lineCounter++;
    updateTotals();
}

function removeLine(id) {
    const row = document.getElementById(`line-${id}`);
    if (row) {
        row.remove();
        updateTotals();
    }
}

function updateTotals() {
    const debitInputs = document.querySelectorAll('.debit-input');
    const creditInputs = document.querySelectorAll('.credit-input');
    
    let totalDebit = 0;
    let totalCredit = 0;
    
    debitInputs.forEach(input => {
        totalDebit += parseFloat(input.value) || 0;
    });
    
    creditInputs.forEach(input => {
        totalCredit += parseFloat(input.value) || 0;
    });
    
    document.getElementById('totalDebit').textContent = totalDebit.toFixed(2);
    document.getElementById('totalCredit').textContent = totalCredit.toFixed(2);
    
    const difference = Math.abs(totalDebit - totalCredit);
    const diffElement = document.getElementById('difference');
    diffElement.textContent = difference.toFixed(2);
    
    if (difference < 0.01) {
        diffElement.className = 'fw-bold text-success';
    } else {
        diffElement.className = 'fw-bold text-danger';
    }
}

addLine();
addLine();
</script>
@endsection
