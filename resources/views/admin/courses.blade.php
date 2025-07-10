<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .sidebar { height: 100vh; background: #343a40; color: #fff; width: 200px; position: fixed; top: 0; left: 0; padding-top: 60px; overflow-y: auto; transition: left 0.3s; z-index: 1000; }
        .sidebar .nav-link { color: #fff; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #495057; color: #ffc107; }
        .content { margin-left: 200px; padding: 20px; transition: margin-left 0.3s; }
        .course-item { border-radius: 10px; overflow: hidden; box-shadow: 0 0 30px rgba(0, 0, 0, .08); background: #fff; }
        .course-item .position-relative { height: 200px; }
        .course-item img { width: 100%; height: 100%; object-fit: cover; }
        .course-item .btn-course { position: absolute; bottom: 10px; left: 10px; background-color: #ffc107; color: #343a40; padding: 5px 15px; border-radius: 5px; font-weight: bold; }
        .course-item .btn-course:hover { background-color: #e0a800; text-decoration: none; color: #343a40; }
        .course-title { font-size: 1.5rem; font-weight: 700; color: #343a40; margin-bottom: 0.5rem; }
        .course-meta { font-size: 1rem; color: #888; }
        .course-actions .btn { margin-left: 5px; }
        @media (max-width: 991.98px) {
            .content { margin-left: 0; padding: 10px; }
            .sidebar { left: -200px; }
            .sidebar.active { left: 0; }
        }
        @media (max-width: 991.98px) {
            .col-lg-4, .col-md-6 { flex: 0 0 50%; max-width: 50%; }
        }
        @media (max-width: 767.98px) {
            .col-lg-4, .col-md-6 { flex: 0 0 100%; max-width: 100%; }
            .course-item .position-relative { height: 160px; }
            .course-title { font-size: 1.2rem; }
        }
    </style>
</head>
<body>
    @include('admin.sidebar')
    <!-- Content -->
    <div class="content">
        <div class="container-fluid">
            <h2 class="mb-4">Manage Courses</h2>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Add New Course</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.addCourse') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title">Course Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="cover_image">Cover Image</label><br>
                            <label class="btn btn-outline-primary" style="position:relative;cursor:pointer;">
                                <i class="fas fa-image mr-1"></i> Choose Cover
                                <input type="file" class="form-control-file" id="cover_image" name="cover_image" accept="image/*" style="display:none;">
                            </label>
                            <span id="cover-image-filename" class="ml-2 text-muted">No file chosen</span>
                            <div id="cover-image-preview" class="mt-2"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Course</button>
                    </form>
                </div>
            </div>

            <div class="container mt-5">
                <div class="text-center mb-4">
                    <h3 style="font-family: 'Segoe UI', Arial, sans-serif; font-weight: 700; letter-spacing: 2px; color: #343a40;">Existing Courses</h3>
                </div>
                <div class="row">
                    @forelse($courses as $course)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="course-item">
                            <div class="position-relative">
                                <img class="img-fluid" src="{{ $course->cover_image ? asset('storage/' . $course->cover_image) : asset('img/cat-'.($loop->iteration % 8 + 1).'.jpg') }}" alt="">
                            </div>
                            <div class="p-4">
                                <div class="d-flex justify-content-between mb-3">
                                    <small class="m-0"><i class="far fa-calendar-alt text-primary mr-2"></i>{{ \Carbon\Carbon::parse($course->start_date)->format('M d, Y') }}</small>
                                    <small class="m-0"><i class="far fa-calendar-alt text-primary mr-2"></i>{{ \Carbon\Carbon::parse($course->end_date)->format('M d, Y') }}</small>
                                </div>
                                <a class="h5" href="{{ route('admin.courseActivities',$course->id) }}">{{ $course->title }}</a>
                                <div class="border-top mt-4 pt-3">
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div style="width:100%">
                                            @php
                                                $lecturers = \App\Models\Enrollment::where('course_id', $course->id)
                                                    ->where('role', 'lecturer')
                                                    ->with('user')
                                                    ->get()
                                                    ->map(function($enrollment) { return $enrollment->user; })
                                                    ->filter();
                                            @endphp
                                            @if($lecturers->count())
                                                <div style="font-size:1em; color:#333; margin-bottom:2px;">
                                                    <i class="fa fa-chalkboard-teacher text-info mr-1"></i><strong>Lecturer(s):</strong>
                                                    @foreach($lecturers as $lecturer)
                                                        <span class="badge badge-info" style="font-size:0.85em; margin-right:2px;">{{ $lecturer->name }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <div style="font-size:0.98em; color:#555; margin-top:2px;">
                                                <i class="fa fa-user text-primary mr-1"></i><strong>Created by:</strong> <span class="font-weight-bold">{{ $course->creator->name ?? 'Unknown' }}</span>
                                            </div>
                                        </div>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.editCourse', $course) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('admin.destroyCourse', $course) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            No courses found. Please add a course first.
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
        // 美化封面上传按钮和预览
        $(function(){
            $('#cover_image').on('change', function(e){
                const file = this.files[0];
                if(file) {
                    $('#cover-image-filename').text(file.name);
                    if(file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            $('#cover-image-preview').html('<img src="'+ev.target.result+'" style="max-width:180px;max-height:120px;border-radius:8px;border:1px solid #ccc;">');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        $('#cover-image-preview').html('');
                    }
                } else {
                    $('#cover-image-filename').text('No file chosen');
                    $('#cover-image-preview').html('');
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
    <!-- 检查并移除多余的Menu按钮，只保留一个主按钮用于触发Offcanvas sidebar -->
</body>
</html> 