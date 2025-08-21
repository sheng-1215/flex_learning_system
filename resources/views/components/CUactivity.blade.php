@props(['activitie', 'studentCount', 'course'])

<?php
    $topics = $activitie->topics ?? collect();
    $videoCount = $topics->where('type', 'video')->count();
    $slideshowCount = $topics->where('type', 'slideshow')->count();
    $documentCount = $topics->where('type', 'document')->count();
    $totalTopics = $topics->count();
?>

<div class="col-12 col-md-6 col-lg-4 mb-4">
    <a href="{{ route('student.CUActivity', $activitie->id) ?? '#' }}" class="card-wrapper text-decoration-none">
        <div class="card course-card h-100">
            @if($course->cover_image)
                <img class="card-img-top course-image" src="{{ asset('storage/' . $course->cover_image) }}" alt="{{ $course->title }} cover">
            @else
                <img class="card-img-top course-image" src="{{ asset('img/default-cover.jpg') }}" alt="Default cover">
            @endif
            <div class="card-body course-info p-2">
                <h5 class="card-title text-primary font-weight-bold mb-1">{{ $activitie->title ?? 'Web Design & Development' }}</h5>
                <div class="d-flex justify-content-between text-muted small mb-2">
                    <span><i class="fas fa-users text-warning"></i> {{ $studentCount[0] ?? 0 }} Students</span>
                    <span><i class="far fa-clock text-warning"></i> {{ $activitie->due_date ?? '2025-06-30' }}</span>
                </div>
                @if($totalTopics > 0)
                    <div class="mt-2 text-muted small">
                        <p class="mb-1">Total Topics: <span class="font-weight-bold text-dark">{{ $totalTopics }}</span></p>
                        <p class="mb-1">Video: <span class="font-weight-bold text-dark">{{ $videoCount }}</span></p>
                        <p class="mb-1">Slideshow: <span class="font-weight-bold text-dark">{{ $slideshowCount }}</span></p>
                        <p class="mb-1">Document: <span class="font-weight-bold text-dark">{{ $documentCount }}</span></p>
                    </div>
                @endif
            </div>
        </div>
    </a>
</div>

<style>
    .card-wrapper {
        display: block;
        color: inherit;
    }
    .card-wrapper:hover .course-card {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }
    .course-card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        background-color: #f8f9fa;
    }
    .course-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-bottom: 1px solid #dee2e6;
    }
    .course-info {
        padding: 10px;
    }
    .course-info small {
        font-size: 0.85rem;
        color: #6c757d;
    }
    .course-info h5 {
        font-size: 1.05rem;
        margin-bottom: 0.5rem;
        color: #003087;
    }
    @media (max-width: 768px) {
        .course-image {
            height: 140px;
        }
        .course-info h5 {
            font-size: 0.95rem;
        }
    }
    @media (max-width: 576px) {
        .course-image {
            height: 110px;
        }
        .course-info {
            padding: 8px;
        }
        .course-info h5 {
            font-size: 0.9rem;
        }
    }
</style>