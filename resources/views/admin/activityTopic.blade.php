<!-- filepath: c:\xampp5\htdocs\flex_learning_system\resources\views\admin\activityTopic.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Topics for CU Activity</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .sidebar { height: 100vh; background: #343a40; color: #fff; width: 200px; position: fixed; top: 0; left: 0; padding-top: 60px; overflow-y: auto; transition: left 0.3s; z-index: 1000; }
        .sidebar .nav-link { color: #fff; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #495057; color: #ffc107; }
        .content { margin-left: 200px; padding: 20px; transition: margin-left 0.3s; }
        .activity-card { border-radius: 10px; box-shadow: 0 0 30px rgba(0, 0, 0, .08); background: #fff; }
        .topic-table th, .topic-table td { vertical-align: middle; }
        @media (max-width: 991.98px) {
            .content { margin-left: 0; padding: 10px; }
            .sidebar { left: -200px; }
            .sidebar.active { left: 0; }
        }
    </style>
</head>
<body>
    @include('admin.sidebar')
    <div class="content">
        <div class="container-fluid">
            <h2 class="mb-4">Manage Topics for CU Activity</h2>

            <!-- CU Activity Card -->
            <div class="activity-card p-4 mb-4">
                <h4 class="mb-2">{{ $activity->title }}</h4>
                <div class="mb-2 text-muted">
                    <i class="far fa-calendar-alt mr-1"></i>
                    Due on {{ $activity->due_date }}
                </div>
                <div class="mb-2">{{ $activity->description }}</div>
            </div>

            <!-- Add Topic Form -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Add New Topic</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.addTopicToActivity', ['cu_id'=>$activity->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title">Topic Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="content_file">Upload Content</label>
                            <input type="file" class="form-control-file" id="file_path" name="file_path[]" required  multiple>
                            <div id="file-info" class="mt-2" style="display:none;">
                                <span id="file-icon" class="mr-2"></span>
                                <span id="file-name"></span>
                                <span id="file-size" class="text-muted ml-2"></span>
                            </div>
                        </div>
                        <script>
                            document.getElementById('file_path').addEventListener('change', function(e) {
                                const file = e.target.files[0];
                                const fileInfo = document.getElementById('file-info');
                                const fileName = document.getElementById('file-name');
                                const fileSize = document.getElementById('file-size');
                                const fileIcon = document.getElementById('file-icon');
                                if (file) {
                                    fileInfo.style.display = 'inline-block';
                                    fileName.textContent = file.name;
                                    fileSize.textContent = '(' + (file.size/1024).toFixed(2) + ' KB)';
                                    // Set icon based on file type
                                    let iconClass = 'fa-file';
                                    if (file.type === 'application/pdf') iconClass = 'fa-file-pdf text-danger';
                                    else if (file.type.startsWith('video/')) iconClass = 'fa-file-video text-primary';
                                    else if (file.name.match(/\.(ppt|pptx)$/i)) iconClass = 'fa-file-powerpoint text-warning';
                                    fileIcon.innerHTML = `<i class="fas ${iconClass}"></i>`;
                                } else {
                                    fileInfo.style.display = 'none';
                                }
                            });
                        </script>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="document">Document</option>
                                <option value="video">Video</option>
                                <option value="slideshow">Slideshow</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Add Topic</button>
                        <a href="{{ route('admin.courseActivities', ['course' => $activity->course->id]) }}" class="btn btn-secondary ml-2">back</a>
                    </form>
                </div>
            </div>

            <!-- Topics Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Topics List</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-hover topic-table mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:60px;">#</th>
                                <th>Title</th>
                                <th>type</th>
                                <th>attachment</th>
                                {{-- <th style="width:100px;">Order</th> --}}
                                <th style="width:120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topics as $i => $topic)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $topic->title }}</td>
                                    <td>{{ $topic->type }}</td>
                                    <td>
                                    @php
                                        
                                        $filepath=json_decode($topic->file_path, true);
                                        // dd($filepath);
                                    @endphp
                                    @foreach($filepath as $newattach)
                                        @php
                                            $ext = pathinfo($newattach["path"], PATHINFO_EXTENSION);
                                            $icon = 'fa-file';
                                            $iconColor = '';
                                            if ($topic->type === 'document') {
                                                if (in_array($ext, ['pdf'])) {
                                                    $icon = 'fa-file-pdf';
                                                    $iconColor = 'text-danger';
                                                } elseif (in_array($ext, ['doc', 'docx'])) {
                                                    $icon = 'fa-file-word';
                                                    $iconColor = 'text-primary';
                                                } else {
                                                    $icon = 'fa-file-alt';
                                                }
                                            } elseif ($topic->type === 'video') {
                                                $icon = 'fa-file-video';
                                                $iconColor = 'text-primary';
                                            } elseif ($topic->type === 'slideshow') {
                                                if (in_array($ext, ['ppt', 'pptx'])) {
                                                    $icon = 'fa-file-powerpoint';
                                                    $iconColor = 'text-warning';
                                                } else {
                                                    $icon = 'fa-file';
                                                }
                                            }
                                        @endphp
                                        <a href="{{ asset('storage/' . $newattach["path"]) }}" target="_blank" class="btn btn-sm btn-info mb-1">
                                            <i class="fas {{ $icon }} {{ $iconColor }}"></i>
                                            {{ $newattach["filename"] }}
                                        </a>
                                    @endforeach
                                    </td>
                                    <td>{{ $topic->order }}</td>

                                    {{-- <td>
                                        <a href="{{ route('admin.editActivityTopic', [$activity->id, $topic->id]) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('admin.deleteActivityTopic', [$activity->id, $topic->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this topic?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No topics found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
    </script>
</body>
</html>