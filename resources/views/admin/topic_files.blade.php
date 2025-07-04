<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Files for Topic: {{ $topic->title }}</title>
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
        .file-preview { max-width: 200px; max-height: 200px; margin: 10px; }
    </style>
</head>
<body>
    @include('admin.sidebar')
    <div class="content">
        <div class="container-fluid">
            <div class="page-header mb-4 text-center">
                <h1 class="display-4">Files for Topic: {{ $topic->title }}</h1>
                <a href="{{ route('admin.assignments.view', $assignment->course_id) }}" class="text-white">Back to Assignments</a>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-folder-open mr-2"></i>All Files</h5>
                </div>
                <div class="card-body">
                    @if(count($files))
                        <div class="row">
                            @foreach($files as $file)
                                @php
                                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                @endphp
                                <div class="col-md-4 mb-4 text-center">
                                    @if(in_array($ext, ['jpg','jpeg','png','webp','gif']))
                                        <img src="{{ asset('storage/' . $file) }}" class="img-fluid file-preview" alt="Image Preview">
                                    @elseif(in_array($ext, ['mp4']))
                                        <video controls class="file-preview">
                                            <source src="{{ asset('storage/' . $file) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @else
                                        <i class="fas fa-file-alt fa-3x text-secondary mb-2"></i>
                                    @endif
                                    <div>
                                        <a href="{{ asset('storage/' . $file) }}" target="_blank" class="btn btn-outline-primary btn-sm mt-2">Download</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted">No files found for this topic.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
</body>
</html> 