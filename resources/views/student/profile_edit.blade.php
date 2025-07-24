@extends('layouts.app')
@section('content')
<div class="container-fluid py-5">
    <div class="container py-4">
        <div class="card shadow-lg">
            <div class="col-md-12">
            <h2>Edit Profile</h2>
            <form action="{{ route('student.profile.edit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3 text-center">
                    <label for="profile_picture" class="form-label d-block">Profile Picture</label>
                    <div class="mb-2">
                        <img 
                            id="profile-picture-preview"
                            src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://via.placeholder.com/120?text=No+Image' }}" 
                            alt="Profile Picture" 
                            width="120" 
                            height="120" 
                            class="rounded-circle shadow-sm border" 
                            style="object-fit: cover;"
                        >
                    </div>
                    <input 
                        type="file" 
                        class="form-control mx-auto" 
                        style="max-width: 300px;" 
                        id="profile_picture" 
                        name="profile_picture" 
                        accept="image/*"
                        onchange="previewProfilePicture(event)"
                    >
                    <small class="form-text text-muted">Accepted formats: JPG, PNG. Max size: 2MB.</small>
                </div>
                <script>
                    function previewProfilePicture(event) {
                        const input = event.target;
                        const preview = document.getElementById('profile-picture-preview');
                        if (input.files && input.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.src = e.target.result;
                            }
                            reader.readAsDataURL(input.files[0]);
                        }
                    }
                </script>
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="bio" class="form-label">Bio</label>
                    <textarea class="form-control" id="bio" name="bio" rows="3">{{ old('bio', auth()->user()->bio) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
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