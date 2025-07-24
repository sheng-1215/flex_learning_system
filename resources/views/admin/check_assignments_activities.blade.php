@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">All CU Activities</h2>
    <div class="row">
        @foreach($activities as $activity)
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $activity->title }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($activity->description, 60) }}</p>
                    <div class="mt-auto d-flex justify-content-between align-items-end">
                        <span class="badge badge-secondary" style="pointer-events:none;opacity:0.7;">
                            {{ $activity->course->name ?? 'No Course' }}
                        </span>
                        <a href="{{ url('/admin/topics/'.$activity->id.'/assignments') }}" class="btn btn-primary btn-sm">View Assignments</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<style>
@media (max-width: 767.98px) {
    .col-md-4, .col-sm-6 {
        max-width: 100%;
        flex: 0 0 100%;
    }
    .card-title { font-size: 1.1rem; }
    .card-text { font-size: 0.98rem; }
}
</style>
@endsection 