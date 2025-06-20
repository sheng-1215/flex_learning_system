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

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Existing Courses</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                            <tr>
                                <td>{{ $course->id }}</td>
                                <td>{{ $course->title }}</td>
                                <td>{{ $course->start_date }}</td>
                                <td>{{ $course->end_date }}</td>
                                <td>{{ $course->creator->name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('admin.editCourse', $course) }}" class="btn btn-sm btn-info">Edit</a>
                                    <form action="{{ route('admin.destroyCourse', $course) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this course? This will also unenroll all students.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
</body>
</html> 