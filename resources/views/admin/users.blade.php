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
        .sidebar { height: 100vh; background: #343a40; color: #fff; width: 200px; position: fixed; top: 0; left: 0; padding-top: 60px; overflow-y: auto; transition: left 0.3s; z-index: 1000; }
        .sidebar .nav-link { color: #fff; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #495057; color: #ffc107; }
        .content { margin-left: 200px; padding: 20px; transition: margin-left 0.3s; }
        .card { border-radius: 10px; box-shadow: 0 0 30px rgba(0, 0, 0, .08); }
        @media (max-width: 991.98px) {
            .content { margin-left: 0; padding: 10px; }
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
            <h2 class="mb-4">Manage Users</h2>
            <!-- Admins Table -->
            <div class="card shadow-sm mb-4" id="admins-section">
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
            <div class="card shadow-sm mb-4" id="lecturers-section">
                @if(session('success') && session('success_role') === 'lecturer')
                    <div id="lecturers-success" class="alert alert-success m-3">{{ session('success') }}</div>
                @endif
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
            <div class="card shadow-sm" id="students-section">
                @if(session('success') && session('success_role') === 'student')
                    <div id="students-success" class="alert alert-success m-3">{{ session('success') }}</div>
                @endif
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
                        {{ $students->appends(request()->except('students_page'))->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            <br>
            <div class="card shadow-sm">
                <div class="card-header"><h5 class="mb-0">Student unregistration from student Portal</h5></div>
                <div class="card-body">
                    @if(session('error'))
                        <div id="portal-error" class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>password</th>
                                    <th>Register</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @forelse($paginated as $studentPortal)
                                <form action="{{ route('admin.importStudent', $studentPortal->id) }}" method="POST">
                                    @csrf
                                <tr>
                                    <td>{{ $studentPortal->s_name }}</td>
                                    <td>{{ $studentPortal->s_email }}</td>
                                    <td><input type="password" name="password" class="form-control" /></td>
                                    <td>  
                                        <button type="submit" class="btn btn-primary">Import</button>                                    
                                    </td>
                                </tr>
                                
                                 </form>
                                @empty
                                <tr><td colspan="4" class="text-center">No student registrations found.</td></tr>
                                @endforelse
                            
                            </tbody>
                        </table>
                       <div class="d-flex justify-content-center">
                        {{ $paginated->links('pagination::bootstrap-4') }}
                    </div>
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
        // Scroll to role-specific success placement
        (function(){
            var role = "{{ session('success_role') }}";
            var targetEl = null;
            if(role === 'student') targetEl = document.getElementById('students-success') || document.getElementById('students-section');
            else if(role === 'lecturer') targetEl = document.getElementById('lecturers-success') || document.getElementById('lecturers-section');
            else if(role) targetEl = document.getElementById('admins-section');
            if(targetEl){
                targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            var err = document.getElementById('portal-error');
            if(err){
                err.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        })();
    </script>
</body>
</html>