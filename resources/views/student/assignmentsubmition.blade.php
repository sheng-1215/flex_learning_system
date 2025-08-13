@extends('layouts.app')
@section('content')
    <div class="container-fluid py-2">
        <div class="container py-1" style="padding-left: 10px; padding-right: 10px;">
            <div class="heading mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('student.assignment') }}">Assignments</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ $assignment->assignment_name }}
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-left">{{ $assignment->assignment_name }}</h3>
                    <div class="mb-3">
                        <p>Due Date: {{ $assignment->due_date }}</p>
                    </div>
                    <div class="mb-3">
                        <p>{{ $assignment->description }}</p>
                    </div>
                    <div class="md-3">
                        <small>Attachment:</small>
                        <br>
                        
                        @if($assignment->attachment)
                            <div style="max-width:90vw; overflow-x:auto; display:inline-block; vertical-align:middle;">
                                <a href="{{ route('student.assignment.download',$assignment->id) }}"  target="_blank" style="display:inline-block; max-width:100%; white-space:nowrap; overflow-x:auto; text-overflow:ellipsis; color:#e67e22;">
                                    <i class="fas fa-file-download"></i> 
                                    <span style="vertical-align:middle;">{{ $assignment->assignment_name.".".pathinfo($assignment->attachment,PATHINFO_EXTENSION) }}</span>
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <h4>submmit assignment</h4>
                        <small>Files to submit *</small>
                        <div id="file-upload" class="mb-3">
                            @if ($submissions->isnotEmpty())
                                <div class="alert alert-success">
                                    <p>Your submission has been successfully uploaded.</p>
                                    @foreach ($submissions as $submission)
                                    <form action="{{ route('student.assignment.delete',['id'=>$submission->id]) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                        <div style="display: flex; align-items: center; flex-wrap: wrap;">
                                            <i class="fas fa-file-alt mr-1"></i>
                                            <div style="max-width: 70vw; overflow-x: auto; display: inline-block; vertical-align: middle;">
                                                <a href="{{ asset('storage/' . $submission->attachment) }}" target="_blank" class="file-link text-break" style="display:inline-block; max-width: 100%; white-space:nowrap; overflow-x:auto; text-overflow:ellipsis; color: #e67e22;">
                                                    {{ $submission->attachment }}
                                                </a>
                                            </div>
                                            <button type="submit" class="btn btn-link p-0 align-baseline text-danger ml-2" style="border: none; background: none;" title="Delete" onclick="return confirm('Are you sure you want to delete this file?')">
                                                <i class="fas fa-times-circle fa-lg"></i>
                                            </button>
                                        </div>
                                            
                                        </form>
                                    @endforeach
                                    <p>Status: {{ $submission->status }}</p>
                                    <p>Submitted at: {{ $submission->submitted_at }}</p>
                                    
                                </div>
                            @else
                                 <div class="alert alert-info">
                                    <p>Upload your assignment file here. Accepted formats: PDF, DOCX, TXT.</p>
                                    <p>Maximum file size: 10MB.</p>
                                </div>
                            @endif
                            

                        </div>

                        <br>
                        <p>After uploading, you must click the submit to completed submission</p>
                        <form action="{{ route('student.assignment.submit', ['id' => $assignment->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <input type="file" name="file" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
                
    
@endsection
@include('student.footer')
<style>
@media (max-width: 576px) {
    .container, .container-fluid, .row, .col-md-12, .form-group, .btn, .alert {
        width: 100% !important;
        max-width: 100% !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    .container, .container-fluid {
        padding-left: 8px !important;
        padding-right: 8px !important;
    }
    .form-control, .btn {
        font-size: 1rem;
    }
    h3, h4, h5 {
        font-size: 1.1rem;
    }
    .breadcrumb {
        font-size: 0.95rem;
        flex-wrap: wrap;
    }
    .alert {
        font-size: 0.98rem;
        padding: 10px 8px;
    }
    .file-link {
        max-width: 88vw;
        overflow-x: auto;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: inline-block;
        vertical-align: middle;
    }
}
</style>