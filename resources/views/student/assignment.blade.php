@extends('layouts.app')
@section('content')
    <div class="container-fluid py-2 bg-light">
        <div class="container py-2">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="heading mb-2">
                <h2 class="text-center text-primary font-weight-bold">Assignments</h2>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>Assignment</th>
                            <th>Completion Status</th>
                            <th>Score</th>
                            <th>Feedback</th>
                            <th>Evaluation Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($assignments->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-muted">No assignments available.</td>
                            </tr>
                        @endif
                        @foreach ($assignments as $assignment)
                            <tr>
                                <td>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('student.assignment.detail', ['id' => $assignment->id]) }}" class="text-decoration-none text-dark font-weight-bold">
                                            {{ $assignment->assignment_name }}
                                        </a>
                                        <a href="{{ route('student.assignment.detail', ['id' => $assignment->id]) }}" class="submit-icon text-success" data-toggle="tooltip" title="Submit Assignment">
                                            <i class="fas fa-upload"></i>
                                        </a>
                                    </div>
                                    <br>
                                    <small class="text-muted">Due Date: {{ $assignment->due_date }}</small>
                                    <h6>Attachment</h6>
                                    @if($assignment->attachment)
                                        <a href="{{ asset('storage/'.$assignment->attachment) }}" class="btn btn-primary btn-sm" target="_blank">
                                            <i class="fas fa-file-download"></i> Download
                                        </a>
                                    @else
                                        <span class="text-muted">No attachment</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($assignment->assignmentSubmissions->isNotEmpty())
                                        <span class="badge bg-success">{{ $assignment->assignmentSubmissions->count() }} document submitted</span>
                                    @else
                                        <span class="badge bg-warning">0 documents submitted</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($assignment->assignmentSubmissions->isNotEmpty())
                                        {{ $assignment->assignmentSubmissions->first()->grade ?? ' - ' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($assignment->assignmentSubmissions->isNotEmpty())
                                        {{ $assignment->assignmentSubmissions->first()->feedback ?? ' - ' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($assignment->assignmentSubmissions->isNotEmpty())
                                        <span class="badge bg-secondary">{{ $assignment->assignmentSubmissions->first()->status }}</span>
                                    @else
                                        <span class="badge bg-secondary">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@include('student.footer')

<style>
    .table-responsive {
        overflow-x: auto;
    }
    .table {
        border-radius: 10px;
        overflow: hidden;
    }
    .table thead th {
        font-size: 1rem;
        font-weight: 600;
    }
    .table td {
        vertical-align: middle;
    }
    .submit-icon {
        font-size: 1.2rem;
        margin-left: 10px;
        transition: color 0.3s;
    }
    .submit-icon:hover {
        color: #28a745;
        text-decoration: none;
    }
    .badge {
        font-size: 0.85rem;
        padding: 0.25rem 0.5rem;
    }
    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.85rem;
    }
    .heading h2 {
        margin: 0;
    }
    @media (max-width: 768px) {
        .table thead th {
            font-size: 0.9rem;
        }
        .table td a {
            font-size: 0.9rem;
        }
        .submit-icon {
            font-size: 1.1rem;
        }
        .btn-sm {
            font-size: 0.8rem;
            padding: 0.2rem 0.5rem;
        }
    }
    @media (max-width: 576px) {
        .table thead th {
            font-size: 0.85rem;
        }
        .table td a {
            font-size: 0.85rem;
        }
        .submit-icon {
            font-size: 1rem;
        }
        .btn-sm {
            font-size: 0.75rem;
            padding: 0.2rem 0.4rem;
        }
        footer { font-size: 0.9rem; padding: 10px 0; }
    }
</style>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>