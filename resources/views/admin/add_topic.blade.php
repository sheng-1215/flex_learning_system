<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Topic to {{ $assignment->title }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .sidebar { height: 100vh; background: #343a40; color: #fff; width: 200px; position: fixed; top: 0; left: 0; padding-top: 60px; }
        .sidebar .nav-link { color: #fff; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #495057; color: #ffc107; }
        .content { margin-left: 200px; padding: 20px; }
        .card { border-radius: 10px; }
        .page-header {
            background: linear-gradient(rgba(0, 0, 0, .5), rgba(0, 0, 0, .5)), no-repeat center center;
            background-size: cover;
            padding: 60px 0;
            color: white;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    @include('admin.sidebar')
    <div class="content">
        <div class="container-fluid">
            <div class="page-header mb-4 text-center" style="background: linear-gradient(rgba(0, 0, 0, .5), rgba(0, 0, 0, .5)), url('{{ asset('img/page-header.jpg') }}') no-repeat center center; background-size: cover; padding: 60px 0; color: white; border-radius: 10px;">
                <h1 class="display-4">Add Topic to: {{ $assignment->title }}</h1>
                <a href="{{ route('admin.assignments.view', $assignment->course_id) }}" class="text-white">Back to Assignments</a>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-plus mr-2"></i>Add New Topic</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('admin.topic.store', $assignment) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required value="{{ old('title') }}">
                        </div>
                        <div class="form-group">
                            <label for="type">Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="slide"{{ old('type') == 'slide' ? ' selected' : '' }}>Slide (Picture)</option>
                                <option value="document"{{ old('type') == 'document' ? ' selected' : '' }}>Document (file)</option>
                                <option value="video"{{ old('type') == 'video' ? ' selected' : '' }}>Video (mp4)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="file_path">File <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="file_path" name="file_path[]" required multiple accept=".jpg,.jpeg,.png,.webp,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.mp4">
                        </div>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save mr-2"></i>Save Topic</button>
                        <a href="{{ route('admin.assignments.view', $assignment->course_id) }}" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
</body>
</html> 