@extends('layouts.accounting')

@section('title', 'Account Groups')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Account Groups</h1>
    <a href="{{ route('account-groups.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create Account Group
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="accordion" id="accountGroupsAccordion">
            @forelse($accountGroups as $group)
                @include('account-groups.partials.group-item', ['group' => $group, 'level' => 0])
            @empty
                <div class="text-center text-muted py-4">
                    No account groups found. Create your first account group!
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
