<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
            <h2 class="mb-4">Manage Users</h2>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Admins Table -->
            <div class="card shadow-sm mb-4">
                <div class="card-header"><h5 class="mb-0">Admins</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created Courses</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admins as $admin)
                                <tr>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>{{ ucfirst($admin->role) }}</td>
                                    <td>
                                        @forelse($admin->courses as $course)
                                            - {{ $course->title }}<br>
                                        @empty
                                            No courses created.
                                        @endforelse
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.editUser', $admin) }}" class="btn btn-sm btn-info">Edit</a>
                                        <form action="{{ route('admin.destroyUser', $admin) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center">No admins found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Lecturers Table -->
            <div class="card shadow-sm mb-4">
                <div class="card-header"><h5 class="mb-0">Lecturers</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Created Courses</th>
                                    <th>Enrolled Courses</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lecturers as $lecturer)
                                <tr>
                                    <td>{{ $lecturer->name }}</td>
                                    <td>{{ $lecturer->email }}</td>
                                    <td>
                                        @forelse($lecturer->courses as $course)
                                            - {{ $course->title }}<br>
                                        @empty
                                            No courses created.
                                        @endforelse
                                    </td>
                                    <td>
                                        @php
                                            $enrolled = $lecturer->lecturerEnrolledCourses;
                                        @endphp
                                        @forelse($enrolled as $course)
                                            - {{ $course->title }}<br>
                                        @empty
                                            Not responsible for any courses.
                                        @endforelse
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.editUser', $lecturer) }}" class="btn btn-sm btn-info">Edit</a>
                                        <form action="{{ route('admin.destroyUser', $lecturer) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center">No lecturers found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Students Table -->
            <div class="card shadow-sm">
                <div class="card-header"><h5 class="mb-0">Students</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Enrolled Courses</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                <tr>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td>
                                        @forelse($student->enrollments as $enrollment)
                                            - {{ $enrollment->course->title }}<br>
                                        @empty
                                            Not enrolled in any courses.
                                        @endforelse
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.editUser', $student) }}" class="btn btn-sm btn-info">Edit</a>
                                        <form action="{{ route('admin.destroyUser', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center">No students found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $students->appends(request()->except('students_page'))->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
</body>
</html> 