<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignments for {{ $activity->title }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #f0f2f5; }
        /* Sidebar styles */
        .sidebar {
            height: 100vh;
            background: #343a40;
            color: #fff;
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 60px;
            transition: left 0.3s;
        }
        .sidebar .nav-link { color: #fff; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #495057; color: #ffc107; }
        .content { margin-left: 200px; padding: 20px; transition: margin-left 0.3s; }
        .card { border-radius: 10px; }
        .page-header {
            background: linear-gradient(rgba(0, 0, 0, .5), rgba(0, 0, 0, .5)), url('/img/page-header.jpg') no-repeat center center;
            background-size: cover;
            padding: 60px 0;    
            color: white;
            border-radius: 10px;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                left: -200px;
                width: 200px;
                z-index: 1050;
            }
            .sidebar.active {
                left: 0;
            }
            .content {
                margin-left: 0;
                padding: 10px;
            }
            .page-header {
                padding: 30px 0;
                font-size: 1.5rem;
            }
        }
        @media (max-width: 575.98px) {
            .page-header h1 {
                font-size: 1.3rem;
            }
            .card-header h5 {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

 @include('admin.sidebar')
    <div class="content">
        <div class="container-fluid">
            <div class="page-header mb-4 text-center" style="background: linear-gradient(rgba(0, 0, 0, .5), rgba(0, 0, 0, .5)), url('{{ asset('img/page-header.jpg') }}') no-repeat center center; background-size: cover; padding: 60px 0; color: white; border-radius: 10px;">
                <h1 class="display-4">{{ $activity->title }}</h1>
                
                <a href="{{ route('admin.selectCourseForAssignment') }}" class="text-white">All CU Activity</a> / <a href="{{ route('admin.selectActiviryForAssignment', [$activity->course_id]) }}" class="text-white">CU activity</a> / <span class="text-warning">Assignments</span>
            </div>

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Assignments</h5>
                    {{-- <a href="{{ route('admin.assignments.add') }}" class="btn btn-warning"><i class="fas fa-plus mr-2"></i>Add New Assignment</a> --}}
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Assignment Name</th>
                                    <th>Description</th>
                                    <th>Attachment</th>
                                    <th>Due Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignments as $assignment)
                                <tr>
                                    <td>{{ $assignment->assignment_name }}</td>
                                    <td>{{ Str::limit($assignment->description, 50) }}</td>
                                    <td><a href="{{ asset($assignment->attachment) }}" target="_blank" >attachment Link</a></td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($assignment->due_date)->format('Y-m-d') }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-info" href="{{ route('admin.checkAssignments', [$assignment]) }}">
                                            check submissions
                                        </a>
                                        <a href="{{ route('admin.assignment.edit', [$activity->course_id, $assignment]) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('admin.assignment.delete', [$activity->course_id, $assignment]) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this assignment?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No assignments found for this course.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        $(function() {
            $('#sidebarToggle').on('click', function() {
                $('#sidebar').toggleClass('active d-none');
            });
        });
    </script>
</body>
</html> 