<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact List | SecureForm</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 1px;
        }
        .main-content {
            flex-grow: 1;
            padding: 40px 0;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            color: #495057;
        }
        .table td {
            vertical-align: middle;
        }
        .search-container {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
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

    <div class="main-content">
        <div class="container">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0"><i class="bi bi-people-fill text-primary me-2"></i>Submitted Contacts</h3>
                <a href="/" class="btn btn-primary shadow-sm"><i class="bi bi-plus-lg me-1"></i> Add New Contact</a>
            </div>

            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body p-4">
                    
                    <form action="/contacts" method="GET" class="mb-4">
                        <div class="row justify-content-end">
                            <div class="col-md-6 col-lg-5">
                                <div class="input-group shadow-sm rounded-3">
                                    <span class="input-group-text bg-white border-end-0 text-muted">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name or email..." value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary px-4 fw-bold">Search</button>
                                    @if(request('search'))
                                        <a href="/contacts" class="btn btn-danger px-3"><i class="bi bi-x-lg"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Message</th>
                                    <th>Submitted At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contacts as $contact)
                                    <tr>
                                        <td class="fw-bold text-dark">{{ $contact->name }}</td>
                                        <td class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $contact->email }}</td>
                                        <td>{{ Str::limit($contact->message, 50) }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border"><i class="bi bi-clock me-1"></i>{{ $contact->created_at->diffForHumans() }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                            No contacts found matching your criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $contacts->appends(request()->input())->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>

    <footer class="bg-white text-center py-3 mt-auto shadow-sm">
        <small class="text-muted">© {{ date('Y') }} Excelsior Technologies. All Rights Reserved.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>