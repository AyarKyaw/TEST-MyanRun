<div class="card h-100 shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="bg-light p-3 rounded-circle">
                <i class="fas {{ $type == 'super' ? 'fa-user-shield text-primary' : 'fa-user text-info' }} fa-2x"></i>
            </div>
            <span class="badge badge-pill {{ $type == 'super' ? 'badge-success' : 'badge-info' }}">
                {{ $type == 'super' ? 'SUPER ADMIN' : 'EVENT ADMIN' }}
            </span>
        </div>

        <h4 class="font-weight-bold text-dark mb-1">{{ $admin->email }}</h4>
        <p class="text-muted small mb-3">Joined: {{ $admin->created_at->format('d M Y') }}</p>

        <hr>

        <div class="d-flex justify-content-between">
            <a href="/dashboard/admins/{{ $admin->id }}/edit" class="btn btn-outline-primary btn-sm px-4" style="border-radius: 8px;">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>

            {{-- Delete Protection --}}
            @if(Auth::guard('admin')->id() !== $admin->id)
                <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this admin?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm px-4" style="border-radius: 8px;">
                        <i class="fas fa-trash-alt mr-1"></i> Delete
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>