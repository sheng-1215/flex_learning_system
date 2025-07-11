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
        .sidebar { height: 100vh; background: #343a40; color: #fff; width: 200px; position: fixed; top: 0; left: 0; padding-top: 60px; overflow-y: auto; }
        .sidebar .nav-link { color: #fff; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #495057; color: #ffc107; }
        .content { margin-left: 200px; padding: 20px; }
        .course-item { border-radius: 10px; overflow: hidden; box-shadow: 0 0 30px rgba(0, 0, 0, .08); background: #fff; }
        .course-item .position-relative { height: 200px; }
        .course-item img { width: 100%; height: 100%; object-fit: cover; }
        .course-item .btn-course { position: absolute; bottom: 10px; left: 10px; background-color: #ffc107; color: #343a40; padding: 5px 15px; border-radius: 5px; font-weight: bold; }
        .course-item .btn-course:hover { background-color: #e0a800; text-decoration: none; color: #343a40; }
    </style>
</head>
<body>
    @include('admin.sidebar')
    <div class="content">
        <div class="container-fluid">
            <div class="page-header mb-4 text-center">
                <h1 class="display-4">{{ $course->title }}</h1>
                
                <a href="{{ route('admin.selectCourseForAssignment') }}" class="text-white">All Courses</a> / <span class="text-warning">CU activity</span>
            </div>
            {{-- Add Assignment Form --}}
            <div class="card mb-4">
                <div class="card-header">Add New Assignment</div>
                <div class="card-body">
                    <form id="add-assignment-form" action="{{ route('admin.activityAssignment.add',$course->id) }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="course_id">Course</label>
                            <select name="activity_id" id="course_id" class="form-control" required>
                                <option value="">Select CU activity</option>
                                @foreach($activities as $activitie)
                                    <option value="{{ $activitie->id }}">{{ $activitie->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="title">Assignment Title</label>
                            <input type="text" name="assignment_name" class="form-control" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                         <x-attachment :name="'attachment'" :label="'Upload Content'" :required="false" :multiple="false" />
                        <div class="form-group mb-2">
                            <label for="due_date">Due Date</label>
                            <input type="date" name="due_date" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Assignment</button>
                    </form>
                </div>
            </div>
            <div class="row" id="course-list">
                @forelse($activities as $activitie)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="course-item">
                        <div class="position-relative">
                            <img class="img-fluid" src="{{ $course->cover_image ? asset('storage/' . $course->cover_image) : asset('img/cat-'.($loop->iteration % 8 + 1).'.jpg') }}" alt="">
                            <a href="{{ route('admin.activityAssignment.view', $activitie->id) }}" class="btn-course">View Assignments</a>
                        </div>
                        <div class="p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <small class="m-0"><i class="fas fa-tasks text-primary mr-2"></i>{{ $activitie->assignments->count() ?? 0 }} Assignments</small>
                                <small class="m-0"><i class="far fa-calendar-alt text-primary mr-2"></i>{{ $course->start_date->format('M d, Y') }}</small>
                            </div>
                            <a class="h5" href="{{ route('admin.activityAssignment.view', $activitie->id) }}">{{ $activitie->title }}</a>
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

    
</body>
</html>
