<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student to Course</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        body { background-color: #f0f2f5; }
        .content { padding: 30px; }
        .card-custom { border-radius: 10px; box-shadow: 0 0 30px rgba(0,0,0,.08); background: #fff; }
        .sidebar { height: 100vh; background: #343a40; color: #fff; width: 200px; position: fixed; top: 0; left: 0; padding-top: 60px; overflow-y: auto; transition: left 0.3s; z-index: 1000; }
        .sidebar .nav-link { color: #fff; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #495057; color: #ffc107; }
        .content { margin-left: 200px; transition: margin-left 0.3s; }
        @media (max-width: 991.98px) {
            .content { margin-left: 0; padding: 10px; }
            .sidebar { left: -200px; }
            .sidebar.active { left: 0; }
        }
    </style>
</head>
<body>
    @include('admin.sidebar')
    <div class="content">
        <div class="container-fluid">
            <div class="page-header mb-4 text-center" style="background: linear-gradient(rgba(0, 0, 0, .5), rgba(0, 0, 0, .5)), url('{{ asset('img/page-header.jpg') }}') no-repeat center center; background-size: cover; padding: 60px 0; color: white; border-radius: 10px;">
                <h1 class="display-4">{{ $course->title }}</h1>
                <a href="{{ route('admin.courses') }}" class="text-white">All Courses</a> / <span class="text-warning">Add Student</span>
            </div>

            <div class="card-custom p-4 mb-4">
                <h4 class="mb-3">Select Student and Course</h4>
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <form action="{{ route('admin.submitUserToCourse',$course->id) }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <label for="user_id">Student</label>
                            <select class="form-control select2" id="user_id" name="user_id" required>
                                <option value="">-- Select User --</option>
                                @foreach($users as $user)
                                    
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        
                        <div class="form-group col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-block">Add</button>
                        </div>
                    </div>
                </form>
            </div>

            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Enrolled Students</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:60px;">#</th>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th style="width:160px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollments as $i => $enrollment)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $enrollment->user->name }}</td>
                                <td>{{ $enrollment->user->email }}</td>
                                <td>
                                    <form action="{{ route('admin.removeUserFromCourse', $enrollment->id) }}" method="POST" onsubmit="return confirm('Remove this student from course?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No students enrolled in this course.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        $('#user_id').select2({
            placeholder: "-- Select User --",
            allowClear: true
        });
    });
</script>

</body>
</html>