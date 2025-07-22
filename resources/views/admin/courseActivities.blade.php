<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage CU Activities</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .sidebar { height: 100vh; background: #343a40; color: #fff; width: 200px; position: fixed; top: 0; left: 0; padding-top: 60px; overflow-y: auto; transition: left 0.3s; z-index: 1000; }
        .sidebar .nav-link { color: #fff; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #495057; color: #ffc107; }
        .content { margin-left: 200px; padding: 20px; transition: margin-left 0.3s; }
        .activity-item { border-radius: 10px; overflow: hidden; box-shadow: 0 0 30px rgba(0, 0, 0, .08); background: #fff; }
        .activity-title { font-size: 1.3rem; font-weight: 700; color: #343a40; margin-bottom: 0.5rem; }
        .activity-meta { font-size: 1rem; color: #888; }
        .activity-actions .btn { margin-left: 5px; }
        @media (max-width: 991.98px) {
            .content { margin-left: 0; padding: 10px; }
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
            <h2 class="mb-4">Manage CU Activities</h2>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Add New CU Activity</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.addCourseActivity') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            <label for="course">Course</label>
                            <input type="text" class="form-control" id="course" name="course" value="{{ $course->title }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="title">Activity Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Activity</button>
                    </form>
                </div>
            </div>

            <div class="container mt-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 style="font-family: 'Segoe UI', Arial, sans-serif; font-weight: 700; letter-spacing: 2px; color: #343a40;">Existing CU Activities</h3>
                   
                </div>
                
                <div class="row">
                    @forelse($activities as $activity)
                    <div class="col-lg-4 col-md-6 mb-4">
                        
                        <div class="activity-item p-4">
                            <a href="{{ route('admin.viewActivitiesTopic', ['activity' => $activity->id]) }}" style="color: #007bff; text-decoration: underline; font-weight: 500;">
                                {{ $activity->topics->count() ?? 0 }} Topics
                            </a>
                            <div class="activity-title">{{ $activity->title }}</div>
                            <div class="activity-meta mb-2">
                                <i class="far fa-calendar-alt mr-1"></i>
                                <span class="ml-2 text-muted">Due on {{ $activity->due_date }}</span>
                            </div>
                            <div class="mb-2" style="font-size:0.98em; color:#555;">
                                {{ Str::limit($activity->description, 80) }}
                            </div>
                            <div class="activity-actions d-flex justify-content-end">
                                {{-- <a href="{{ route('admin.editCUActivity', $activity) }}" class="btn btn-sm btn-primary">Edit</a> --}}
                                <a href="{{ route('admin.editCUActivity', $activity) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <div class="btn-group" role="group">
                                            
                                            <form action="{{ route('admin.destroyCUActivity', $activity) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            No CU Activities found. Please add an activity first.
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script>
        // Beautify file upload button and preview filename
        $(function(){
            $('#file').on('change', function(e){
                const file = this.files[0];
                if(file) {
                    $('#file-filename').text(file.name);
                } else {
                    $('#file-filename').text('No file chosen');
                }
            });
        });
    </script>
    <script>
        // Sidebar toggle for mobile
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
    </script>
</body>

</html></html>
