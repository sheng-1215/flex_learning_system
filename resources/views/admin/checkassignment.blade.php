<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Assignment Submissions</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <style>
        body { background-color: #f0f2f5; }
        .content { padding: 30px; }
        .assignment-card { border-radius: 10px; box-shadow: 0 0 30px rgba(0,0,0,.08); background: #fff; }
        .table th, .table td { vertical-align: middle; }
        .sidebar { height: 100vh; background: #343a40; color: #fff; width: 200px; position: fixed; top: 0; left: 0; padding-top: 60px; overflow-y: auto; transition: left 0.3s; z-index: 1000; }
        .sidebar .nav-link { color: #fff; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #495057; color: #ffc107; }
        .content { margin-left: 200px; transition: margin-left 0.3s; }
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
            <div class="page-header mb-4 text-center" style="background: linear-gradient(rgba(0, 0, 0, .5), rgba(0, 0, 0, .5)), url('{{ asset('img/page-header.jpg') }}') no-repeat center center; background-size: cover; padding: 60px 0; color: white; border-radius: 10px;">
                <h1 class="display-4">Check Assignment Submissions</h1>
                <a href="" class="text-white">All CU Activity</a> / <span class="text-warning">CU activity</span>
            </div>
            

            <!-- Assignment Info Card -->
            <div class="assignment-card p-4 mb-4">
                <h4 class="mb-2">{{ $assignment->assignment_name ?? 'Assignment Name' }}</h4>
                <div class="mb-2 text-muted">
                    <i class="far fa-calendar-alt mr-1"></i>
                    Due on {{ $assignment->due_date ?? '-' }}
                </div>
                <div class="mb-2">{{ $assignment->description ?? 'No description.' }}</div>
            </div>

            <!-- Submissions Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Student Submissions</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:60px;">#</th>
                                <th>Student Name</th>
                                <th>Submitted At</th>
                                <th>File</th>
                                <th style="width:300px">Score</th>
                                <th style="width:160px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($submissions as $i => $submission)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $submission->user->name }}</td>
                                <td>{{ $submission->submitted_at ??  '-' }}</td>
                                <td>
                                    @if($submission->attachment)
                                        <a href="{{ asset('storage/' . $submission->attachment) }}" target="_blank">Download</a>
                                    @else
                                        <span class="text-muted">No file</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.gradeAssignments', [$submission->id]) }}" method="POST" class="form-inline">
                                        @csrf
                                        <input type="number" name="grade" class="form-control form-control-sm mr-2" style="width:70px;" min="0" max="100" value="{{ $submission->grade ?? '' }}">
                                        <button type="submit" class="btn btn-sm btn-success">Save</button>
                                        <button type="button" class="btn btn-sm btn-primary ml-2" data-toggle="modal" data-target="#feedbackModal{{ $submission->id }}">
                                        Feedback
                                    </button>
                                    </form>
                                    <!-- Feedback Button trigger modal -->
                                    

                                    <!-- Feedback Modal -->
                                    <div class="modal fade" id="feedbackModal{{ $submission->id }}" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel{{ $submission->id }}" aria-hidden="true">
                                      <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                          <form action="{{ route('admin.feedbackAssignments', [$submission->id]) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="feedbackModalLabel{{ $submission->id }}">Feedback for {{ $submission->user->name }}</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                            <div class="modal-body">
                                              <textarea name="feedback" class="form-control" rows="4" placeholder="Enter feedback...">{{ $submission->feedback ?? '' }}</textarea>
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                              <button type="submit" class="btn btn-primary">Save Feedback</button>
                                            </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>

                                
                                    <!-- No custom JS needed for modal, Bootstrap 4 handles data-toggle="modal" automatically -->
                                    </script>
                                </td>
                                <td>
                                    @if($submission->grade !== null)
                                        <span class="badge badge-success">Graded</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No submissions found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
