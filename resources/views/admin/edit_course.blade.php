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
        .current-image {
            max-width: 200px;
            max-height: 150px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .image-preview {
            margin-top: 10px;
        }
        .image-preview img {
            max-width: 200px;
            max-height: 150px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
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
                    <form action="{{ route('admin.updateCourse', $course) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="title">Course Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $course->title) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="cover_image">Course Cover Image</label>
                            @if($course->cover_image)
                                <div class="mb-3">
                                    <label class="form-label">Current Image:</label>
                                    <div>
                                        <img src="{{ asset('storage/' . $course->cover_image) }}" alt="Current course cover" class="current-image">
                                    </div>
                                </div>
                            @endif
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="cover_image" name="cover_image" accept=".jpg,.jpeg,.png,.webp,.gif">
                                <label class="custom-file-label" for="cover_image">Choose file</label>
                            </div>
                            <small class="form-text text-muted">Upload a new image to replace the current one. Supported formats: JPEG, PNG, JPG, GIF, WEBP. Max size: 5MB.</small>
                            <div class="image-preview mt-2" id="imagePreview" style="display: none;">
                                <label class="form-label">Preview:</label>
                                <div>
                                    <img id="previewImg" src="" alt="Image preview">
                                </div>
                            </div>
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
                        <div class="form-group">
                            <label for="lecturers">Lecturer(s)</label>
                            <select class="form-control" id="lecturers" name="lecturers[]" multiple>
                                @php
                                    $allLecturers = \App\Models\User::where('role', 'lecturer')->get();
                                    $selectedLecturerIds = old('lecturers', $course->enrollments()->where('role', 'lecturer')->pluck('user_id')->toArray());
                                @endphp
                                @foreach($allLecturers as $lecturer)
                                    <option value="{{ $lecturer->id }}" {{ in_array($lecturer->id, $selectedLecturerIds) ? 'selected' : '' }}>{{ $lecturer->name }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple lecturers.</small>
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
    <script>
        // Image preview functionality
        document.getElementById('cover_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            const fileLabel = document.querySelector('label.custom-file-label[for="cover_image"]');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
                if (fileLabel) fileLabel.textContent = file.name;
            } else {
                preview.style.display = 'none';
                if (fileLabel) fileLabel.textContent = 'Choose file';
            }
        });
    </script>
</body>
</html> 