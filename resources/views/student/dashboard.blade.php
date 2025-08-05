@extends('layouts.app')
@section('content')
    <div class="container-fluid py-2 bg-light min-vh-90">
        <div class="container py-2">
            <div class="text-center mb-2">
                <h5 class="text-warning text-uppercase mb-1 mt-2" style="letter-spacing: 4px;">Registered Courses</h5>
                <h1 class="h4 text-dark font-weight-bold mb-4">{{ auth()->user()->enrollments->first()->course->title }}</h1>
            </div>
            <div class="row">
                @foreach ($activities as $activitie)
                    <x-CUactivity :activitie="$activitie" :studentCount="$studentCount" :course="$course->first()" />
                @endforeach 
            </div>
        </div>
    </div>
@endsection
@include('student.footer')
<style>
    .course-card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        background-color: #f8f9fa;
    }
    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
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
    .course-info h1, .course-info h4 {
        font-size: 1.5rem;
        color: #003087;
    }
    @media (max-width: 768px) {
        .course-image {
            height: 140px;
        }
        .course-info h1, .course-info h4 {
            font-size: 1.2rem;
        }
    }
    @media (max-width: 576px) {
        .course-image {
            height: 110px;
        }
        .course-info {
            padding: 8px;
        }
        .course-info h1, .course-info h4 {
            font-size: 1rem;
        }
    }
</style>