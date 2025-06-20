<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
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
            <h2 class="mb-4">Edit Course: {{ $course->title }}</h2>

            <div class="card shadow-sm">
                <div class="card-header"><h5 class="mb-0">Course Details</h5></div>
                <div class="card-body">
                    <form action="{{ route('admin.updateCourse', $course) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="title">Course Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $course->title) }}" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $course->start_date->format('Y-m-d')) }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $course->end_date->format('Y-m-d')) }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Course</button>
                        <a href="{{ route('admin.courses') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
</body>
</html> 