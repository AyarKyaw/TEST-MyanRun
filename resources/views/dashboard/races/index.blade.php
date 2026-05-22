@extends('dashboard.layouts.master')
@section('content')

<div class="content-body">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Form Section: Create New Race Event Tab -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary py-2">
                        <h4 class="card-title text-white">Create New Race Event Tab</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.races.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label text-black fw-bold">Race Event Name</label>
                                <input type="text" name="race_name" class="form-control" placeholder="e.g., Cherry Trail Run 2026" required>
                            </div>

                            <hr>
                            <label class="form-label text-black fw-bold mb-2">Race Detail Cards (Images, Size Charts, Entitlements)</label>
                            
                            <div id="cards-repeater">
                                <div class="row mb-3 card-item-row border p-2 rounded bg-light">
                                    <div class="col-md-5">
                                        <label class="form-label small">Card Display Title</label>
                                        <input type="text" name="card_titles[]" class="form-control" placeholder="e.g., T-shirt Size Chart (Inches)" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label small">Upload Chart/Info Image</label>
                                        <input type="file" name="card_images[]" class="form-control" accept="image/*" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger w-100 remove-card-btn"><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>

                            <button type="button" id="add-card-btn" class="btn btn-info btn-sm mt-2 mb-4">
                                <i class="fa fa-plus"></i> Add Another Info Card
                            </button>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-5">Save and Publish Race</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Listing Section: Existing Races Created -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header"><h4 class="card-title">Manage Created Races</h4></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Race Event Name</th>
                                        <th>Total Cards Added</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($races as $race)
                                    <tr>
                                        <td class="fw-bold">{{ $race->name }}</td>
                                        <td><span class="badge bg-secondary">{{ $race->cards->count() }} Cards</span></td>
                                        <td>
                                            @if($race->is_active)
                                                <span class="badge bg-success text-white">Active (Shown)</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Disabled (Hidden)</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <form action="{{ route('admin.races.toggle', $race->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ $race->is_active ? 'btn-warning' : 'btn-success' }}">
                                                        {{ $race->is_active ? 'Disable' : 'Enable' }}
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.races.destroy', $race->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely delete this entire race event?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center">No races registered yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Add dynamic image card input fields row
    document.getElementById('add-card-btn').addEventListener('click', function() {
        const container = document.getElementById('cards-repeater');
        const newRow = document.createElement('div');
        newRow.className = 'row mb-3 card-item-row border p-2 rounded bg-light';
        newRow.innerHTML = `
            <div class="col-md-5">
                <label class="form-label small">Card Display Title</label>
                <input type="text" name="card_titles[]" class="form-control" placeholder="e.g., Entitlement Package" required>
            </div>
            <div class="col-md-5">
                <label class="form-label small">Upload Chart/Info Image</label>
                <input type="file" name="card_images[]" class="form-control" accept="image/*" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger w-100 remove-card-btn"><i class="fa fa-trash"></i></button>
            </div>
        `;
        container.appendChild(newRow);
    });

    // Remove single row structure
    document.getElementById('cards-repeater').addEventListener('click', function(e) {
        if (e.target.closest('.remove-card-btn')) {
            const rows = document.querySelectorAll('.card-item-row');
            if (rows.length > 1) {
                e.target.closest('.card-item-row').remove();
            } else {
                alert('At least one item card payload configuration is needed for registration.');
            }
        }
    });
</script>
@endsection