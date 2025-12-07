@extends('layouts.accounting')

@section('title', 'Create Account')

@section('content')
<div class="mb-4">
    <h1 class="h2">Create New Account</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('accounts.index') }}">Chart of Accounts</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('accounts.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="code" class="form-label">Account Code <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('code') is-invalid @enderror" 
                               id="code" 
                               name="code" 
                               value="{{ old('code') }}" 
                               required 
                               autofocus
                               placeholder="e.g., 1000, 2000, etc.">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Unique identifier for the account</small>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Account Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required
                               placeholder="e.g., Cash, Accounts Receivable, etc.">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Account Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" 
                                id="type" 
                                name="type" 
                                required>
                            <option value="">-- Select Type --</option>
                            @foreach($accountTypes as $type)
                                <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="account_group_id" class="form-label">Account Group <span class="text-danger">*</span></label>
                        <select class="form-select @error('account_group_id') is-invalid @enderror" 
                                id="account_group_id" 
                                name="account_group_id" 
                                required>
                            <option value="">-- Select Account Group --</option>
                            @foreach($accountGroups as $group)
                                <option value="{{ $group->id }}" {{ old('account_group_id') == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                    @if($group->parent)
                                        ({{ $group->parent->name }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('account_group_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Account
                        </button>
                        <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
