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
                    <x-CUactivity :activitie="$activitie" />
                @endforeach 
            </div>
        </div>
    </div>
@endsection