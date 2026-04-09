<div class="card h-100 shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="bg-light p-3 rounded-circle">
                @php
                    // Define icons and colors based on type
                    $icon = 'fa-user text-info';
                    $badgeClass = 'badge-info';
                    $label = 'EVENT ADMIN';

                    if ($type == 'super') {
                        $icon = 'fa-user-shield text-primary';
                        $badgeClass = 'badge-success';
                        $label = 'SUPER ADMIN';
                    } elseif ($type == 'agent') {
                        $icon = 'fa-headset text-warning';
                        $badgeClass = 'badge-warning text-white';
                        $label = 'SUPPORT AGENT';
                    }
                @endphp
                <i class="fas {{ $icon }} fa-2x"></i>
            </div>
            <span class="badge badge-pill {{ $badgeClass }}">
                {{ $label }}
            </span>
        </div>

        {{-- Since you mentioned actually no name, we show email as the primary title --}}
        <h5 class="font-weight-bold text-dark mb-1">{{ $admin->email }}</h5>
        <p class="text-muted small mb-3">Joined: {{ $admin->created_at->format('d M Y') }}</p>

        <hr>

        <div class="d-flex justify-content-between align-items-center">
            @php
                $currentUser = Auth::guard('admin')->user();
                $isSuperAdmin = $currentUser->role === 'super_admin';
                
                // Pointing to the unified AdminManagementController with the 'type' parameter
                if ($type == 'agent') {
                    $editUrl   = route('admin.admins.edit', [$admin->id, 'type' => 'agent']);
                    $deleteUrl = route('admin.admins.destroy', [$admin->id, 'type' => 'agent']);
                } else {
                    $editUrl   = route('admin.admins.edit', $admin->id);
                    $deleteUrl = route('admin.admins.destroy', $admin->id);
                }
            @endphp

            {{-- 1. Edit Button Logic --}}
            @if($isSuperAdmin || (Auth::guard('admin')->id() === $admin->id))
                <a href="{{ $editUrl }}" class="btn btn-outline-primary btn-sm px-4" style="border-radius: 8px;">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
            @else
                <span class="text-muted small"><i class="fas fa-lock mr-1"></i> Locked</span>
            @endif

            {{-- 2. Delete Button Logic --}}
            {{-- Prevent self-deletion --}}
            @if(Auth::guard('admin')->id() !== $admin->id)
                @if($isSuperAdmin)
                    <form action="{{ $deleteUrl }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this {{ $type }} account?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm px-4" style="border-radius: 8px;">
                            <i class="fas fa-trash-alt mr-1"></i> Delete
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>
</div>