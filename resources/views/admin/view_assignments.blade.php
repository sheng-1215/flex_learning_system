<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignments for {{ $course->title }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .sidebar { height: 100vh; background: #343a40; color: #fff; width: 200px; position: fixed; top: 0; left: 0; padding-top: 60px; overflow-y: auto; transition: left 0.3s; z-index: 1000; }
        .sidebar .nav-link { color: #fff; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #495057; color: #ffc107; }
        .content { margin-left: 200px; padding: 20px; transition: margin-left 0.3s; }
        .card { border-radius: 10px; box-shadow: 0 0 30px rgba(0, 0, 0, .08); }
        .page-header {
            background: linear-gradient(rgba(0, 0, 0, .5), rgba(0, 0, 0, .5)), url({{ asset('img/page-header.jpg') }}) no-repeat center center;
            background-size: cover;
            padding: 40px 0 20px 0;
            color: white;
            border-radius: 10px;
        }
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
            <div class="page-header mb-4 text-center">
                <h1 class="display-4">{{ $course->title }}</h1>
                <a href="{{ route('admin.selectCourseForAssignment') }}" class="text-white">All Courses</a> / <span class="text-warning">CU activity</span>
            </div>
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Assignments</h5>
                    <a href="{{ route('admin.assignments.add', $course) }}" class="btn btn-warning"><i class="fas fa-plus mr-2"></i>Add New Assignment</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Due Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignments as $assignment)
                                <tr>
                                    <td>{{ $assignment->title }}</td>
                                    <td>{{ Str::limit($assignment->description, 50) }}</td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($assignment->due_date)->format('Y-m-d') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" type="button" data-toggle="collapse" data-target="#topics-{{ $assignment->id }}" aria-expanded="false" aria-controls="topics-{{ $assignment->id }}">
                                            View
                                        </button>
                                        <a href="{{ route('admin.assignment.edit', [$course, $assignment]) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('admin.assignment.delete', [$course, $assignment]) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this assignment?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <tr class="collapse" id="topics-{{ $assignment->id }}">
                                    <td colspan="4">
                                        <div class="card card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="mb-0">Topics</h6>
                                                <a href="{{ route('admin.topic.add', $assignment) }}" class="btn btn-success btn-sm">+ Add Topic</a>
                                            </div>
                                            @if($assignment->topics->count())
                                            <table class="table table-bordered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Type</th>
                                                        <th>File</th>
                                                        <th>Created At</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($assignment->topics as $topic)
                                                    <tr>
                                                        <td>{{ $topic->title }}</td>
                                                        <td>{{ $topic->type }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.topic.files', [$assignment, $topic]) }}" class="btn btn-info btn-sm" target="_blank">View All</a>
                                                        </td>
                                                        <td>{{ $topic->created_at->format('Y-m-d') }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.topic.edit', $topic) }}" class="btn btn-sm btn-primary">Edit</a>
                                                            <form action="{{ route('admin.topic.delete', $topic) }}" method="POST" style="display:inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this topic?')">Delete</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @else
                                                <div class="text-muted">No topics found for this assignment.</div>
                                            @endif
                                        </div>
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
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
    </script>
</body>
</html> 