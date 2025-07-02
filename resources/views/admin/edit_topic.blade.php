<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Topic: {{ $topic->title }}</title>
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
            background: linear-gradient(rgba(0, 0, 0, .5), rgba(0, 0, 0, .5)), url({{ asset('img/page-header.jpg') }}) no-repeat center center;
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
            <div class="page-header mb-4 text-center">
                <h1 class="display-4">Edit Topic: {{ $topic->title }}</h1>
                <a href="{{ route('admin.assignments.view', $assignment->course_id) }}" class="text-white">Back to Assignments</a>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-edit mr-2"></i>Edit Topic</h5>
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
                    <form action="{{ route('admin.topic.update', [$assignment, $topic]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required value="{{ old('title', $topic->title) }}">
                        </div>
                        <div class="form-group">
                            <label for="type">Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="slide"{{ old('type', $topic->type) == 'slide' ? ' selected' : '' }}>Slide (Picture)</option>
                                <option value="document"{{ old('type', $topic->type) == 'document' ? ' selected' : '' }}>Document (fi)</option>
                                <option value="video"{{ old('type', $topic->type) == 'video' ? ' selected' : '' }}>Video (mp4)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="file_path">File</label>
                            @if($topic->file_path)
                                <div class="mb-2">
                                    @php
                                        $files = is_array($topic->file_path) ? $topic->file_path : (json_decode($topic->file_path, true) ?: [$topic->file_path]);
                                    @endphp
                                    @foreach($files as $file)
                                        <a href="{{ asset('storage/' . $file) }}" target="_blank">Current File</a>@if(!$loop->last), @endif
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" class="form-control" id="file_path" name="file_path[]" multiple accept=".jpg,.jpeg,.png,.webp,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.mp4">
                            <small class="form-text text-muted">Leave blank to keep the current file(s).</small>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Update Topic</button>
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