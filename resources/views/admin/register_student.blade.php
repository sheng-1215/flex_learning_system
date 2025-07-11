<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Student</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .sidebar {
            height: 100vh;
            background: #343a40;
            color: #fff;
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 60px;
        }
        .sidebar .nav-link {
            color: #fff;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background: #495057;
            color: #ffc107;
        }
        .content {
            margin-left: 200px;
            padding: 20px;
        }
        .card {
            border-radius: 10px;
        }
        @media (max-width: 991.98px) {
            .content { margin-left: 0; padding: 10px; padding-top: 60px !important; }
            .sidebar { left: -200px; }
            .sidebar.active { left: 0; }
        }
    </style>
</head>
<body>
    @include('admin.sidebar')
    <!-- Content -->
    <div class="content">
        <div class="container-fluid">
            <h2 class="mb-4">Register New Account</h2>

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
                    <h5 class="mb-0">Account Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.registerStudent') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role" required onchange="toggleCourseSection()">
                                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                                <option value="lecturer" {{ old('role') == 'lecturer' ? 'selected' : '' }}>Lecturer</option>
                            </select>
                        </div>
                        <div class="form-group" id="student-courses-section">
                            <label>Assign Course (for Student)</label>
                            @foreach($courses as $course)
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="student_course_{{ $course->id }}" name="student_course" value="{{ $course->id }}">
                                    <label class="custom-control-label" for="student_course_{{ $course->id }}">{{ $course->title }}</label>
                                </div>
                            @endforeach
                        </div>
                            <div class="form-group" id="lecturer-courses-section" style="display:none;">
                                <label>Assign Responsible Course (for Lecturer)</label>
                            @foreach($courses as $course)
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="lecturer_course_{{ $course->id }}" name="lecturer_course" value="{{ $course->id }}">
                                    <label class="custom-control-label" for="lecturer_course_{{ $course->id }}">{{ $course->title }}</label>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Register Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleCourseSection() {
            var role = document.getElementById('role').value;
            document.getElementById('student-courses-section').style.display = (role === 'student') ? 'block' : 'none';
            document.getElementById('lecturer-courses-section').style.display = (role === 'lecturer') ? 'block' : 'none';
        }
        document.addEventListener('DOMContentLoaded', function() {
            toggleCourseSection();
        });
    </script>
</body>
</html> 