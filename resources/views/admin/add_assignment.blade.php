<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Assignment to {{ $course->title }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .sidebar { height: 100vh; background: #343a40; color: #fff; width: 200px; position: fixed; top: 0; left: 0; padding-top: 60px; overflow-y: auto; transition: left 0.3s; z-index: 1000; }
        .sidebar .nav-link { color: #fff; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #495057; color: #ffc107; }
        .content { margin-left: 200px; padding: 20px; transition: margin-left 0.3s; }
        .card { border-radius: 10px; box-shadow: 0 0 30px rgba(0, 0, 0, .08); }
        .page-header {
            background: linear-gradient(rgba(0, 0, 0, .5), rgba(0, 0, 0, .5)), url({{ asset('img/page-header.jpg') }}) no-repeat center center;
            background-size: cover;
            padding: 40px 0 20px 0;
            color: white;
            border-radius: 10px;
        }
        .custom-file-label::after { content: "Browse"; }
        @media (max-width: 991.98px) {
            .content { margin-left: 0; padding: 10px; padding-top: 60px !important; }
            .sidebar { left: -200px; }
            .sidebar.active { left: 0; }
        }
        @media (max-width: 767.98px) {
            .page-header { padding: 20px 0 10px 0; font-size: 1.2rem; }
        }
        .menu-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1100;
            background: #ffc107;
            color: #343a40;
            border: none;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        @media (min-width: 992px) {
            .menu-toggle { display: none; }
        }
    </style>
</head>
<body>
    @include('admin.sidebar')
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10 col-12">
                    <div class="page-header mb-4 text-center">
                        <h2 class="font-weight-bold">Add Assignment to {{ $course->title }}</h2>
                        <a href="{{ route('admin.assignments.view', $course) }}" class="text-white">Back to Assignments</a>
                    </div>
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-plus mr-2"></i>Add New Assignment</h5>
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
                            <form action="{{ route('admin.assignments.add.post', ['course' => $course->id]) }}" method="POST" autocomplete="off">
                                @csrf
                                <div class="form-group">
                                    <label for="title">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" required value="{{ old('title') }}" placeholder="Enter assignment title">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter assignment description (optional)">{{ old('description') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="due_date">Due Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="due_date" name="due_date" required value="{{ old('due_date') }}">
                                </div>
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
                                    <button type="submit" class="btn btn-warning mb-2 mb-md-0"><i class="fas fa-save mr-2"></i>Save Assignment</button>
                                    <a href="{{ route('admin.assignments.view', $course) }}" class="btn btn-secondary ml-md-2">Cancel</a>
                                </div>
                            </form>
                        </div>
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
        // 显示选中的文件名
        $(document).on('change', '.custom-file-input', function (event) {
            var inputFile = event.currentTarget;
            $(inputFile).parent().find('.custom-file-label').html(Array.from(inputFile.files).map(f => f.name).join(', '));
        });
    </script>
</body>
</html> 