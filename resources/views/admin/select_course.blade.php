<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Course</title>
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
        @media (max-width: 991.98px) {
            .content { margin-left: 0; padding: 10px; padding-top: 60px !important; }
            .sidebar { left: -200px; }
            .sidebar.active { left: 0; }
        }
        @media (max-width: 991.98px) {
            .col-lg-4, .col-md-6 { flex: 0 0 50%; max-width: 50%; }
        }
        @media (max-width: 767.98px) {
            .col-lg-4, .col-md-6 { flex: 0 0 100%; max-width: 100%; }
            .course-item .position-relative { height: 160px; }
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
            <div class="text-center mb-5">
                <h5 class="text-primary text-uppercase mb-3" style="letter-spacing: 5px;">Assignments</h5>
                <h1>Select a Course to add Assignments</h1>
            </div>
            <div class="row" id="course-list">
                @forelse($courses as $course)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="course-item">
                        <div class="position-relative">
                            <img class="img-fluid" src="{{ $course->cover_image ? asset('storage/' . $course->cover_image) : asset('img/cat-'.($loop->iteration % 8 + 1).'.jpg') }}" alt="">
                        </div>
                        <div class="p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <small class="m-0"><i class="fas fa-tasks text-primary mr-2"></i>{{ $course->cu_activities_count ?? 0 }} CU activity</small>
                                <small class="m-0"><i class="far fa-calendar-alt text-primary mr-2"></i>{{ $course->start_date->format('M d, Y') }}</small>
                            </div>
                            <a class="h5" href="{{ route('admin.selectActiviryForAssignment', $course) }}">{{ $course->title }}</a>
                            <div class="border-top mt-4 pt-4">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        @php
                                            $lecturers = $course->enrollments()->where('role', 'lecturer')->with('user')->get()->pluck('user');
                                        @endphp
                                        @if($lecturers->count())
                                            <div style="font-size:1em; color:#333; margin-bottom:2px;">
                                                <i class="fa fa-chalkboard-teacher text-info mr-1"></i><strong>Lecturer(s):</strong>
                                                @foreach($lecturers as $lecturer)
                                                    <span class="badge badge-info" style="font-size:0.95em; margin-right:2px;">{{ $lecturer->name }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                        <h6 class="m-0"><i class="fa fa-user text-primary mr-2"></i>Created by {{ $course->creator->name ?? 'N/A' }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        No courses found. Please <a href="{{ route('admin.courses') }}">add a course</a> first.
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
    </script>
</body>
</html>
