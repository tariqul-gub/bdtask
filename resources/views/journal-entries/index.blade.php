@extends('layouts.accounting')

@section('title', 'Journal Entries')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Journal Entries</h1>
    <a href="{{ route('journal-entries.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create Journal Entry
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Branch</th>
                        <th>Description</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($journalEntries as $entry)
                        <tr>
                            <td>{{ $entry->id }}</td>
                            <td>{{ $entry->entry_date->format('M d, Y') }}</td>
                            <td>{{ $entry->branch->name }}</td>
                            <td>{{ Str::limit($entry->description, 50) }}</td>
                            <td><span class="badge bg-secondary">{{ $entry->source_module }}</span></td>
                            <td>
                                <span class="badge 
                                    @if($entry->status == 'Approved') bg-success
                                    @elseif($entry->status == 'Pending') bg-warning
                                    @else bg-secondary
                                    @endif">
                                    {{ $entry->status }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('journal-entries.show', $entry) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                @if($entry->status !== 'Approved')
                                    <a href="{{ route('journal-entries.edit', $entry) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No journal entries found. Create your first entry!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $journalEntries->links() }}
</div>
@endsection
