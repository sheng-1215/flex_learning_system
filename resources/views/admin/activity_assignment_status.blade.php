@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1 text-dark">
                        <i class="fas fa-clipboard-list text-primary mr-2"></i>
                        Assignment Status: {{ $activity->title }}
                    </h2>
                    <p class="text-muted mb-0">
                        Course: {{ $activity->course->title ?? 'No Course' }} | 
                        Due Date: {{ $activity->due_date ? \Carbon\Carbon::parse($activity->due_date)->format('M d, Y') : 'No due date' }}
                    </p>
                </div>
                <div class="d-flex">
                    <a href="{{ route('admin.checkassignmentsStatus') }}" class="btn btn-outline-secondary mr-2">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Overview
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Summary -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-gradient-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle mr-2"></i>
                        Activity Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="h3 text-primary mb-1">{{ $assignments->count() }}</div>
                            <small class="text-muted">Total Assignments</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="h3 text-info mb-1">{{ $enrolledStudents->count() }}</div>
                            <small class="text-muted">Enrolled Students</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="h3 text-success mb-1">{{ $assignments->sum('stats.submitted_count') }}</div>
                            <small class="text-muted">Total Submissions</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="h3 text-warning mb-1">{{ $assignments->sum('stats.not_submitted_count') }}</div>
                            <small class="text-muted">Pending Submissions</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assignments List -->
    @forelse($assignments as $assignment)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-tasks mr-2"></i>
                            {{ $assignment->assignment_name }}
                        </h5>
                        <div class="d-flex align-items-center">
                            <span class="badge badge-{{ $assignment->stats['progress_percentage'] >= 80 ? 'success' : ($assignment->stats['progress_percentage'] >= 50 ? 'warning' : 'danger') }} mr-2">
                                {{ $assignment->stats['progress_percentage'] }}% Complete
                            </span>
                            <small class="text-muted">
                                {{ $assignment->stats['submitted_count'] }}/{{ $assignment->stats['enrolled_students'] }} submitted
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    @if($assignment->description)
                    <p class="text-muted mb-3">{{ $assignment->description }}</p>
                    @endif
                    
                    <!-- Progress Bar -->
                    <div class="mb-3">
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-{{ $assignment->stats['progress_percentage'] >= 80 ? 'success' : ($assignment->stats['progress_percentage'] >= 50 ? 'warning' : 'danger') }}" 
                                 role="progressbar" 
                                 style="width: {{ $assignment->stats['progress_percentage'] }}%" 
                                 aria-valuenow="{{ $assignment->stats['progress_percentage'] }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Student Status Table -->
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Submitted Date</th>
                                    <th>Grade</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignment->studentStatus as $status)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-circle fa-lg text-muted mr-2"></i>
                                            {{ $status['student']->name }}
                                        </div>
                                    </td>
                                    <td>{{ $status['student']->email }}</td>
                                    <td>
                                        @if($status['has_submitted'])
                                            <span class="badge badge-success">
                                                <i class="fas fa-check mr-1"></i>Submitted
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock mr-1"></i>Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($status['submission'])
                                            {{ \Carbon\Carbon::parse($status['submission']->submitted_at)->format('M d, Y H:i') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($status['submission'] && $status['submission']->grade !== null)
                                            <span class="badge badge-info">{{ $status['submission']->grade }}/100</span>
                                        @else
                                            <span class="text-muted">Not graded</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($status['has_submitted'])
                                            <a href="{{ route('admin.checkAssignments', $assignment->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Due: {{ $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') : 'No due date' }}
                        </small>
                        <div>
                            <a href="{{ route('admin.checkAssignments', $assignment->id) }}" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-clipboard-check mr-1"></i> Check All Submissions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Assignments Found</h5>
                    <p class="text-muted">This activity doesn't have any assignments yet.</p>
                    <a href="{{ route('admin.activityAssignment.view', $activity->id) }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Add Assignment
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforelse
</div>

<style>
.bg-gradient-info {
    background: linear-gradient(45deg, #36b9cc 0%, #1a8997 100%);
}

.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15) !important;
}

.progress {
    border-radius: 10px;
    background-color: #e3e6f0;
}

.progress-bar {
    border-radius: 10px;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.badge {
    font-size: 0.75em;
}

@media (max-width: 767.98px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .d-flex.justify-content-between > div:last-child {
        margin-top: 1rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
@endsection
