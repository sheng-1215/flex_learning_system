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
        .sidebar { height: 100vh; background: #343a40; color: #fff; width: 200px; position: fixed; top: 0; left: 0; padding-top: 60px; overflow-y: auto; transition: left 0.3s; z-index: 1000; }
        .sidebar .nav-link { color: #fff; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #495057; color: #ffc107; }
        .content { margin-left: 200px; padding: 20px; transition: margin-left 0.3s; }
        .card { border-radius: 10px; box-shadow: 0 0 30px rgba(0, 0, 0, .08); }
        @media (max-width: 991.98px) {
            .content { margin-left: 0; padding: 10px; padding-top: 60px !important; }
            .sidebar { left: -200px; }
            .sidebar.active { left: 0; }
        }
        .menu-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1100;
            background: #ffc107;
            color: #343a40;
            border: none;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        @media (min-width: 992px) {
            .menu-toggle { display: none; }
        }
    </style>
</head>
<body>
    @include('admin.sidebar')
    <div class="content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Edit User: {{ $user->name }}</h2>
                <span class="badge badge-info" style="font-size:1.1em;">Role: {{ ucfirst($user->role) }}</span>
            </div>
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
                            <label>Enrolled Course</label>
                            @foreach($courses as $course)
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="course_{{ $course->id }}" name="student_courses[]" value="{{ $course->id }}" {{ (isset($enrolledCourseIds) && in_array($course->id, $enrolledCourseIds)) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="course_{{ $course->id }}">{{ $course->title }}</label>
                                </div>
                            @endforeach
                        </div>
                        @endif
                        @if ($user->role === 'lecturer')
                        <hr>
                        <div class="form-group">
                            <label>Responsible Courses</label>
                            @foreach($courses as $course)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="lecturer_course_{{ $course->id }}" name="lecturer_courses[]" value="{{ $course->id }}" {{ in_array($course->id, $lecturerCourseIds ?? []) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="lecturer_course_{{ $course->id }}">{{ $course->title }}</label>
                                </div>
                            @endforeach
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
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
    </script>
</body>
</html> 