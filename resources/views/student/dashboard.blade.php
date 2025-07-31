@extends('layouts.app')
@section('content')
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h5 class="text-primary text-uppercase mb-3" style="letter-spacing: 5px;">Register Courses</h5>
                <h1>{{ auth()->user()->enrollments->first()->course->title }}</h1>
            </div>
            
            <div class="row">
                
                @foreach ($activities as $activitie)
                    <x-CUactivity :activitie="$activitie" :studentCount="$studentCount" />
                @endforeach 
            </div>
        </div>
    </div>
@endsection
@include('student.footer')
<style>
@media (max-width: 576px) {
    .container, .card, .row, .col, .form-group, .btn {
        width: 100% !important;
        max-width: 100% !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    .form-control, .btn {
        font-size: 1rem;
    }
    h2, h3, h4, h5 {
        font-size: 1.1rem;
    }
    img {
        max-width: 100%;
        height: auto;
    }
}
</style>