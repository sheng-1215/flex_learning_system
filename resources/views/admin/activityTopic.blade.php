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
    .activity-title-header {
        color: #fff;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        padding: 15px 20px;
        font-weight: 600;
    }
    .activity-title-header h4 {
        margin: 0;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
    }
    .activity-title-header .far {
        font-size: 1.2rem;
        margin-right: 10px;
    }
    .topic-table th, .topic-table td { vertical-align: middle; }
    @media (max-width: 991.98px) {
        .content { margin-left: 0; padding: 10px; }
        .sidebar { left: -200px; }
        .sidebar.active { left: 0; }
        .activity-title-header h4 { font-size: 1.2rem; }
        .activity-title-header .far { font-size: 1rem; }
    }
</style>
</head>
<body>
    @include('admin.sidebar')
    <div class="content">
        <div class="container-fluid">
            <h2 class="mb-4 text-dark font-weight-bold">Manage Topics for CU Activity</h2>
            
            

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif  

            <div class="card activity-card mb-4">
    <div class="card-header activity-title-header">
        <h4 class="mb-0 text-primary d-flex align-items-center">
            <i class="far fa-clipboard mr-2"></i>
            {{ $activity->title }}
        </h4>
    </div>
    <div class="card-body">
        <div class="mb-3 text-muted">
            <i class="far fa-calendar-alt mr-2"></i>
            Due on {{ $activity->due_date }}
        </div>
        <p class="mb-0">{{ $activity->description }}</p>
    </div>
</div>

            <!-- Add Topic Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-plus mr-2"></i>Add New Topic</h5>
                </div>
                <div class="card-body">
                <form action="{{ route('admin.addTopicToActivity', [$activity->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title">Topic Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required placeholder="Enter topic title">
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="document">Document</option>
                                <option value="video">Video</option>
                                <option value="slideshow">Slideshow</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="file_path">Upload Content <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file_path" name="file_path[]" multiple>
                                <label class="custom-file-label" for="file_path">Choose file(s)</label>
                            </div>
                            <small id="type-help" class="form-text text-muted">Select a type to see allowed formats.</small>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Add Topic</button>
                        <a href="{{ route('admin.courseActivities', ['course' => $activity->course_id]) }}" class="btn btn-secondary ml-2">Back to CU Activities</a>
                    </form>
                </div>
            </div>

            <!-- Topics Table -->
            <div id="topics-section" class="card">
                @if(session('success'))
                    <div id="topics-success" class="alert alert-success alert-dismissible fade show m-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list mr-2"></i>Topics List</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover topic-table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Attachment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topics as $key => $topic)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $topic->title }}</td>
                                    <td>{{ $topic->type }}</td>
                                    <td style="min-width: 180px;">
                                        @php
                                            $filepath = json_decode($topic->file_path, true);
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
                                            <a href="{{ route('admin.downloadTopic', $topic->id) }}" target="_blank"
                                               class="btn btn-sm btn-info mb-1 text-truncate d-inline-block"
                                               style="max-width: 140px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <i class="fas {{ $icon }} {{ $iconColor }} mr-1"></i>
                                                {{ $newattach["filename"] }}
                                            </a>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.topic.edit', $topic->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit mr-1"></i>Edit</a>
                                        <form action="{{ route('admin.topic.delete', $topic->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this topic?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash mr-1"></i>Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No topics found.</td>
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
        // Sidebar toggle for mobile
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
        // Custom file input label update
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
        // Dynamic accept and helper by type
        (function(){
            var typeSelect = document.getElementById('type');
            var fileInput = document.getElementById('file_path');
            var help = document.getElementById('type-help');
            var map = {
                document: { accept: '.pdf,.doc,.docx,.xls,.xlsx,.txt', text: 'Allowed: pdf, doc, docx, xls, xlsx, txt' },
                video: { accept: '.mp4', text: 'Allowed: mp4 only' },
                slideshow: { accept: '.jpg,.jpeg,.png,.webp,.gif', text: 'Allowed: jpg, jpeg, png, webp, gif (images)' }
            };
            function apply(){
                var v = typeSelect.value;
                if(map[v]){
                    fileInput.setAttribute('accept', map[v].accept);
                    help.textContent = map[v].text;
                } else {
                    fileInput.removeAttribute('accept');
                    help.textContent = 'Select a type to see allowed formats.';
                }
            }
            typeSelect.addEventListener('change', apply);
            apply();
        })();
        // Scroll to success message in topics section if present
        (function(){
            var successEl = document.getElementById('topics-success');
            if(successEl){
                successEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        })();
    </script>
</body>
</html>