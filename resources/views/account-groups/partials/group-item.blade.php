<div class="accordion-item border-0 {{ $level > 0 ? 'ms-4' : '' }}">
    <h2 class="accordion-header" id="heading{{ $group->id }}">
        <div class="d-flex align-items-center justify-content-between p-3 {{ $level > 0 ? 'bg-light' : 'bg-white' }} border-bottom">
            <div class="d-flex align-items-center flex-grow-1">
                @if($group->children->count() > 0)
                    <button class="btn btn-sm btn-link text-decoration-none p-0 me-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $group->id }}">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                @else
                    <span class="me-2" style="width: 24px; display: inline-block;">
                        <i class="bi bi-dash"></i>
                    </span>
                @endif
                <i class="bi bi-folder me-2 text-primary"></i>
                <strong>{{ $group->name }}</strong>
                @if($group->parent)
                    <small class="text-muted ms-2">(Parent: {{ $group->parent->name }})</small>
                @endif
            </div>
            <div class="btn-group btn-group-sm">
                <a href="{{ route('account-groups.show', $group) }}" class="btn btn-info btn-sm">
                    <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('account-groups.edit', $group) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('account-groups.destroy', $group) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure? This will also delete all child groups.')">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </h2>
    @if($group->children->count() > 0)
        <div id="collapse{{ $group->id }}" class="accordion-collapse collapse show" data-bs-parent="#accountGroupsAccordion">
            <div class="accordion-body p-0">
                @foreach($group->children as $child)
                    @include('account-groups.partials.group-item', ['group' => $child, 'level' => $level + 1])
                @endforeach
            </div>
        </div>
    @endif
</div>
