@extends('layouts.app')
@section('content')
    <div class="container-fluid py-2">
        <div class="container py-1">
           <div class="heading mb-2">
                <h2 class="text-center">Assignments</h2>
            </div>
            <div class="table">
                <table class="table table-bordered">
                    <thead>
                        <th>Assignment</th>
                        <th>Completion Status</th>
                        <th>Score</th>
                        <th>Evaluation status</th>
                    </thead>
                    <tbody>
                        @if($assignments->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center">No assignments available.</td>
                            </tr>
                        @endif
                        @foreach ($assignments as $assignment)
                            <tr>
                                <td>
                                    <a href="{{ route('student.assignment.detail', ['id' => $assignment->id]) }}" >
                                        {{ $assignment->assignment_name }}
                                    </a>
                                    <br>
                                    <br>
                                    <small  class="text-muted">Due Date: {{ $assignment->due_date }}</small>
                                    <h6>Attachment</h6>
                                    @if($assignment->attachment)
                                        <a href="{{ route('student.assignment.download',$assignment->id) }}" class="btn btn-primary" target="_blank">
                                            <i class="fas fa-file-download"></i> Download
                                        </a>
                                    @else
                                        <span class="text-muted">No attachment</span>
                                    @endif
                                </td>
                                <td>
                                    @if($assignment->assignmentSubmissions->isNotEmpty())
                                        <span class="badge bg-success">{{ $assignment->assignmentSubmissions->count()." document submited" }}</span>
                                    @else
                                        <span class="badge bg-warning">{{ $assignment->assignmentSubmissions->count()." document submited" }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($assignment->assignmentSubmissions->isNotEmpty())
                                        {{ $assignment->assignmentSubmissions->first()->score ?? 'N/A' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>

                                </td>
                            </tr>
                            
                        @endforeach

                    </tbody>
                </table>
            </div>
           </div>
        </div>
    </div>
@endsection
@include('student.footer')

<style>
    @media (max-width: 576px) {
        footer { font-size: 0.95rem; padding: 12px 0; }
    }
</style>