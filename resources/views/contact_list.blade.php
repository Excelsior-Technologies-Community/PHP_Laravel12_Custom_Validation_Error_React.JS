<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Management Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .stats-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 15px;
        }
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
        }
        .btn-action {
            margin: 0 2px;
            transition: transform 0.2s;
        }
        .btn-action:hover {
            transform: scale(1.1);
        }
        .search-box {
            border-radius: 25px;
            padding: 10px 20px;
        }
        .pagination {
            margin-bottom: 0;
        }
        .modal-content {
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-shield-check text-success me-2"></i>SecureForm
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/"><i class="bi bi-house-door me-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/contacts"><i class="bi bi-list-task me-1"></i> Contact List</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card stats-card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-subtitle mb-2 text-white-50">Total Contacts</h6>
                                <h2 class="card-title mb-0">{{ $stats['total'] }}</h2>
                            </div>
                            <i class="bi bi-people-fill fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stats-card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-subtitle mb-2 text-white-50">Today's Submissions</h6>
                                <h2 class="card-title mb-0">{{ $stats['today'] }}</h2>
                            </div>
                            <i class="bi bi-calendar-day-fill fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stats-card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-subtitle mb-2 text-white-50">This Week</h6>
                                <h2 class="card-title mb-0">{{ $stats['this_week'] }}</h2>
                            </div>
                            <i class="bi bi-calendar-week-fill fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="table-container shadow-lg">
            <div class="p-4 bg-white border-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="mb-0">
                            <i class="bi bi-database-fill text-primary me-2"></i>
                            Contact Submissions
                        </h4>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-md-end gap-2">
                            <a href="{{ route('contacts.export') }}" class="btn btn-success">
                                <i class="bi bi-download me-1"></i> Export CSV
                            </a>
                            <a href="/" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> New Contact
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-3 bg-light border-bottom">
                <form action="/contacts" method="GET" class="row g-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control search-box" 
                                   placeholder="Search by name, email, or message..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="sort" class="form-select">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="select-all" class="form-check-input">
                            </th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Submitted At</th>
                            <th width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contacts as $contact)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input contact-checkbox" value="{{ $contact->id }}">
                            </td>
                            <td class="fw-bold">{{ $contact->name }}</td>
                            <td>{{ $contact->email }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($contact->message, 60) }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-clock me-1"></i>{{ $contact->created_at->diffForHumans() }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info btn-action view-contact" data-id="{{ $contact->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-action delete-contact" data-id="{{ $contact->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
                                <p class="text-muted mb-0">No contacts found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3 bg-white border-top">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <button class="btn btn-danger btn-sm" id="bulk-delete" style="display: none;">
                            <i class="bi bi-trash3 me-1"></i> Delete Selected
                        </button>
                    </div>
                    <div class="col-md-6">
                        {{ $contacts->appends(request()->input())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Contact Modal -->
    <div class="modal fade" id="viewContactModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-person-badge me-2"></i>Contact Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="contact-details"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirm Delete
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this contact?</p>
                    <p class="mb-0 text-muted">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // CSRF Token setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Select All functionality
        const selectAllCheckbox = document.getElementById('select-all');
        const contactCheckboxes = document.querySelectorAll('.contact-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete');

        function updateBulkDeleteButton() {
            const checked = document.querySelectorAll('.contact-checkbox:checked').length;
            if (checked > 0) {
                bulkDeleteBtn.style.display = 'inline-block';
            } else {
                bulkDeleteBtn.style.display = 'none';
            }
        }

        selectAllCheckbox?.addEventListener('change', function() {
            contactCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateBulkDeleteButton();
        });

        contactCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkDeleteButton);
        });

        // Bulk Delete
        bulkDeleteBtn?.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.contact-checkbox:checked'))
                .map(cb => cb.value);
            
            if (selectedIds.length === 0) return;
            
            if (confirm(`Are you sure you want to delete ${selectedIds.length} contact(s)?`)) {
                fetch('/contacts/bulk-delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ ids: selectedIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to delete contacts');
                    }
                });
            }
        });

        // View Contact
        const viewModal = new bootstrap.Modal(document.getElementById('viewContactModal'));
        document.querySelectorAll('.view-contact').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                fetch(`/contacts/${id}`)
                    .then(response => response.json())
                    .then(contact => {
                        document.getElementById('contact-details').innerHTML = `
                            <div class="mb-3">
                                <label class="fw-bold text-muted">Name:</label>
                                <p class="mb-0 fs-5">${contact.name}</p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-muted">Email:</label>
                                <p class="mb-0">${contact.email}</p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-muted">Message:</label>
                                <p class="mb-0">${contact.message}</p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-muted">Submitted:</label>
                                <p class="mb-0">${new Date(contact.created_at).toLocaleString()}</p>
                            </div>
                        `;
                        viewModal.show();
                    });
            });
        });

        // Delete Contact
        let deleteId = null;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        
        document.querySelectorAll('.delete-contact').forEach(btn => {
            btn.addEventListener('click', function() {
                deleteId = this.dataset.id;
                deleteModal.show();
            });
        });

        document.getElementById('confirm-delete')?.addEventListener('click', function() {
            if (deleteId) {
                fetch(`/contacts/${deleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to delete contact');
                    }
                });
            }
        });
    </script>
</body>
</html>