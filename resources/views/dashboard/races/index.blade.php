@extends('dashboard.layouts.master')
@section('content')

<div class="content-body">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Validation Error Banner --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- ========================================================================== --}}
        {{-- SECTION 1: STANDALONE RACE GUIDE MANAGEMENT                                --}}
        {{-- ========================================================================== --}}
        <div class="row mb-5">
            <div class="col-xl-12">
                <div class="card border-info bg-light-subtle">
                    <div class="card-header bg-info py-2">
                        <h4 class="card-title text-white"><i class="fa fa-book me-2"></i>Global Race Guide Management (Multi-Page)</h4>
                    </div>
                    <div class="card-body bg-white">
                        <div class="row">
                            {{-- Left: View / Manage Uploaded Pages --}}
                            <div class="col-md-7 mb-4 mb-md-0 border-end">
                                <h5 class="text-black fw-bold mb-1">Current Guide Pages</h5>
                                <p class="text-muted small mb-3">Manage and arrange all independent pages uploaded for the general guide booklet.</p>
                                
                                @if(!empty($raceGuides))
                                    <div class="row">
                                        @foreach($raceGuides as $index => $path)
                                            <div class="col-md-4 col-sm-6 mb-3 text-center">
                                                <div class="border p-2 rounded bg-light position-relative">
                                                    <span class="badge bg-dark position-absolute top-0 start-0 m-1">Page {{ $index + 1 }}</span>
                                                    <img src="{{ asset($path) }}" class="img-fluid rounded mb-2 border" style="max-height: 120px; object-fit: contain; width: 100%;">
                                                    
                                                    <div class="d-flex gap-1 justify-content-center">
                                                        <a href="{{ asset($path) }}" target="_blank" class="btn btn-xs btn-info text-white w-50">
                                                            <i class="fa fa-eye"></i> View
                                                        </a>
                                                        <button type="button" class="btn btn-xs btn-danger w-50" 
                                                                onclick="if(confirm('Delete this guide page completely?')){ 
                                                                    document.getElementById('delete-page-path').value = '{{ $path }}';
                                                                    document.getElementById('delete-page-form').submit(); 
                                                                }">
                                                            <i class="fa fa-trash"></i> Drop
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="py-4 text-center border rounded border-dashed bg-light text-muted">
                                        <i class="fa fa-book-open fa-2x mb-2 text-secondary"></i>
                                        <p class="mb-0 small fw-bold">No guide papers uploaded to site settings yet.</p>
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Right: Add More Papers --}}
                            <div class="col-md-5 ps-md-4">
                                <h5 class="text-black fw-bold mb-2">Upload Additional Guide Papers</h5>
                                <form action="{{ route('admin.race-guide.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3 p-3 border rounded bg-light" id="guide-repeater">
                                        <div class="guide-input-row mb-2">
                                            <label class="form-label small text-dark fw-bold">Select Image Paper(s)</label>
                                            <input type="file" name="new_race_guides[]" class="form-control mb-1" accept="image/*" required>
                                        </div>
                                    </div>
                                    
                                    <button type="button" class="btn btn-xs btn-secondary mb-3" id="add-guide-field">
                                        <i class="fa fa-plus"></i> Add Another Page Field
                                    </button>
                                    <div>
                                        <button type="submit" class="btn btn-info text-white px-4 btn-sm">
                                            <i class="fa fa-upload me-1"></i> Upload & Append Pages
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form id="delete-page-form" action="{{ route('admin.race-guide.page.destroy') }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
            <input type="hidden" name="image_path" id="delete-page-path">
        </form>

        <hr class="my-4">

        {{-- ========================================================================== --}}
        {{-- SECTION 2: RACE EVENTS MANAGEMENT                                         --}}
        {{-- ========================================================================== --}}
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
                                <input type="text" name="race_name" class="form-control" placeholder="e.g., Cherry Trail Run 2026" required value="{{ old('race_name') }}">
                            </div>

                            <hr>
                            <label class="form-label text-black fw-bold mb-2">Race Detail Cards (Images, Size Charts, Entitlements)</label>
                            
                            <div id="cards-repeater">
                                {{-- If validation failed, rebuild the dynamically added text fields --}}
                                @if(old('card_titles'))
                                    @foreach(old('card_titles') as $index => $title)
                                        <div class="row mb-3 card-item-row border p-2 rounded bg-light">
                                            <div class="col-md-5">
                                                <label class="form-label small">Card Display Title</label>
                                                <input type="text" name="card_titles[{{ $index }}]" class="form-control" value="{{ $title }}" required>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label small">Upload Chart/Info Image <span class="text-danger small">(Please re-select image)</span></label>
                                                <input type="file" name="card_images[{{ $index }}]" class="form-control" accept="image/*" required>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger w-100 remove-card-btn"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    {{-- Default Row on first page load --}}
                                    <div class="row mb-3 card-item-row border p-2 rounded bg-light">
                                        <div class="col-md-5">
                                            <label class="form-label small">Card Display Title</label>
                                            <input type="text" name="card_titles[0]" class="form-control" placeholder="e.g., T-shirt Size Chart (Inches)" required>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label small">Upload Chart/Info Image</label>
                                            <input type="file" name="card_images[0]" class="form-control" accept="image/*" required>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger w-100 remove-card-btn"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                @endif
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

                                                <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#editRaceModal{{ $race->id }}">
                                                    <i class="fa fa-edit"></i> Edit
                                                </button>

                                                <form action="{{ route('admin.races.destroy', $race->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely delete this entire race event?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="editRaceModal{{ $race->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Race & Manage Cards: {{ $race->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.races.update', $race->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-4">
                                                            <label class="form-label text-black fw-bold">Update Race Name</label>
                                                            <input type="text" name="race_name" class="form-control" value="{{ $race->name }}" required>
                                                        </div>

                                                        <label class="form-label text-black fw-bold mb-2">Existing Cards (Delete or Keep)</label>
                                                        <div class="row mb-3">
                                                            @forelse($race->cards as $card)
                                                                <div class="col-md-4 mb-3 text-center">
                                                                    <div class="border p-2 rounded bg-light position-relative">
                                                                        <img src="{{ asset($card->image) }}" class="img-fluid rounded mb-2" style="max-height: 100px; object-fit: contain;">
                                                                        <h6 class="small fw-bold text-truncate" title="{{ $card->title }}">{{ $card->title }}</h6>
                                                                        
                                                                        <button type="button" class="btn btn-danger btn-xs w-100 mt-1" 
                                                                                onclick="if(confirm('Delete this information card?')){ document.getElementById('delete-card-form-{{ $card->id }}').submit(); }">
                                                                            <i class="fa fa-trash"></i> Remove Card
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            @empty
                                                                <div class="col-12 text-muted text-center py-2">No dynamic resource info cards linked yet.</div>
                                                            @endforelse
                                                        </div>

                                                        <hr>
                                                        <label class="form-label text-black fw-bold mb-2">Upload Additional Cards</label>
                                                        
                                                        <div id="edit-cards-repeater-{{ $race->id }}"></div>
                                                        
                                                        <button type="button" class="btn btn-info btn-xs mt-1" onclick="addCardRowToEdit({{ $race->id }})">
                                                            <i class="fa fa-plus"></i> Add New Field Card
                                                        </button>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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

{{-- Hidden Individual Card Eraser Blueprint Actions --}}
@foreach($races as $race)
    @foreach($race->cards as $card)
        <form id="delete-card-form-{{ $card->id }}" action="{{ route('admin.races.card.destroy', $card->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endforeach

<script>
    function reindexCreateRows() {
        const rows = document.querySelectorAll('#cards-repeater .card-item-row');
        rows.forEach((row, index) => {
            row.querySelector('input[type="text"]').setAttribute('name', `card_titles[${index}]`);
            row.querySelector('input[type="file"]').setAttribute('name', `card_images[${index}]`);
        });
    }

    // Add dynamic input fields to creation layout
    document.getElementById('add-card-btn').addEventListener('click', function() {
        const container = document.getElementById('cards-repeater');
        const currentCount = container.querySelectorAll('.card-item-row').length;
        
        const newRow = document.createElement('div');
        newRow.className = 'row mb-3 card-item-row border p-2 rounded bg-light';
        newRow.innerHTML = `
            <div class="col-md-5">
                <label class="form-label small">Card Display Title</label>
                <input type="text" name="card_titles[${currentCount}]" class="form-control" placeholder="e.g., Entitlement Package" required>
            </div>
            <div class="col-md-5">
                <label class="form-label small">Upload Chart/Info Image</label>
                <input type="file" name="card_images[${currentCount}]" class="form-control" accept="image/*" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger w-100 remove-card-btn"><i class="fa fa-trash"></i></button>
            </div>
        `;
        container.appendChild(newRow);
    });

    // Remove single row structure from Create Form
    document.getElementById('cards-repeater').addEventListener('click', function(e) {
        if (e.target.closest('.remove-card-btn')) {
            const rows = document.querySelectorAll('#cards-repeater .card-item-row');
            if (rows.length > 1) {
                e.target.closest('.card-item-row').remove();
                reindexCreateRows();
            } else {
                alert('At least one item card configuration is needed for registration.');
            }
        }
    });

    // Handle Edit Mode Repeater
    function addCardRowToEdit(raceId) {
        const editContainer = document.getElementById(`edit-cards-repeater-${raceId}`);
        const currentRowsCount = editContainer.querySelectorAll('.edit-card-item-row').length;
        
        const editRow = document.createElement('div');
        editRow.className = 'row mb-2 border p-2 rounded bg-white edit-card-item-row';
        editRow.innerHTML = `
            <div class="col-md-5">
                <label class="form-label small">New Card Title</label>
                <input type="text" name="new_card_titles[${currentRowsCount}]" class="form-control form-control-sm" placeholder="e.g., Route map" required>
            </div>
            <div class="col-md-5">
                <label class="form-label small">Choose Image</label>
                <input type="file" name="new_card_images[${currentRowsCount}]" class="form-control form-control-sm" accept="image/*" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeEditRow(this, ${raceId})"><i class="fa fa-trash"></i></button>
            </div>
        `;
        editContainer.appendChild(editRow);
    }

    function removeEditRow(buttonElement, raceId) {
        buttonElement.closest('.edit-card-item-row').remove();
        
        const editContainer = document.getElementById(`edit-cards-repeater-${raceId}`);
        const rows = editContainer.querySelectorAll('.edit-card-item-row');
        rows.forEach((row, index) => {
            row.querySelector('input[type="text"]').setAttribute('name', `new_card_titles[${index}]`);
            row.querySelector('input[type="file"]').setAttribute('name', `new_card_images[${index}]`);
        });
    }
    document.getElementById('add-guide-field').addEventListener('click', function() {
        const container = document.getElementById('guide-repeater');
        const newRow = document.createElement('div');
        newRow.className = 'guide-input-row mb-2 d-flex gap-2 align-items-center';
        newRow.innerHTML = `
            <input type="file" name="new_race_guides[]" class="form-control" accept="image/*" required>
            <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()"><i class="fa fa-times"></i></button>
        `;
        container.appendChild(newRow);
    });
</script>
@endsection