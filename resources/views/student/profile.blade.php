@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white d-flex align-items-center">
                        <i class="bi bi-person-circle fs-2 me-3"></i>
                        <h4 class="card-title mb-0">Student Profile</h4>
                        {{-- <a  href="{{ route('student.profile.edit') }}"  class="btn btn-sm btn-light ms-auto" style="float:left;" >Edit</a> --}}
                    </div>
                    <div class="card-body">
                        <div class="row g-4 align-items-center">
                            <div class="col-md-3 text-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff&size=128" 
                                     alt="Profile Picture" class="rounded-circle img-fluid shadow-sm mb-3" style="width: 120px; height: 120px;">
                                <h5 class="mt-2">{{ Auth::user()->name }}</h5>
                                <span class="badge bg-success ">Student</span>
                            </div>
                            <div class="col-md-9">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold"><i class="bi bi-envelope me-2"></i>Email:</span>
                                        <span>{{ Auth::user()->email }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold"><i class="bi bi-book me-2"></i>Enrolled Course:</span>
                                        <span>
                                            @if(Auth::user()->enrollments->isNotEmpty())
                                                {{ Auth::user()->enrollments->first()->course->title }}
                                            @else
                                                <span class="text-muted">No course enrolled</span>
                                            @endif
                                        </span>
                                    </li>
                                    <!-- Add more profile fields here if needed -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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