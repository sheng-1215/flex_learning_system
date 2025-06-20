<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .sidebar { height: 100vh; background: #343a40; color: #fff; width: 200px; position: fixed; top: 0; left: 0; padding-top: 60px; }
        .sidebar .nav-link { color: #fff; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #495057; color: #ffc107; }
        .content { margin-left: 200px; padding: 20px; }
        .card { border-radius: 10px; }
    </style>
</head>
<body>
    @include('admin.sidebar')

    <div class="content">
        <div class="container-fluid">
            <h2 class="mb-4">Edit User: {{ $user->name }}</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">User Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.updateUser', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>

                        @if ($user->role === 'student')
                        <hr>
                        <div class="form-group">
                            <label>Enrolled Courses</label>
                            @forelse($courses as $course)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="course_{{ $course->id }}" name="courses[]" value="{{ $course->id }}"
                                        {{ $user->enrollments->pluck('course_id')->contains($course->id) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="course_{{ $course->id }}">{{ $course->title }}</label>
                                </div>
                            @empty
                                <p class="text-muted">No courses available.</p>
                            @endforelse
                        </div>
                        @endif

                        <hr>
                        <p class="text-muted">Leave password fields blank to keep the current password.</p>
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm New Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="{{ route('admin.users') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
</body>
</html> 