<!DOCTYPE html>
<html>
<head>
    <title>All Submitted Contacts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h3 class="mb-4">All Submitted Contacts</h3>
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
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
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ $contact->message }}</td>
                        <td>{{ $contact->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No contacts submitted yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <a href="/" class="btn btn-primary mt-3">Back to Form</a>
    </div>
</body>
</html>