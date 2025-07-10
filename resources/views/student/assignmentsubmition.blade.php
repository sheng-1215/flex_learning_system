@extends('layouts.app')
@section('content')
    <div class="container-fluid py-2">
        <div class="container py-1">
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
                    <div class="md-3">
                        <small>Attachment:</small>
                        <br>
                        
                        @if($assignment->attachment)
                            <a href="{{ asset('asset/assignment/' . $assignment->attachment) }}"  target="_blank">
                                <i class="fas fa-file-download"></i> Download
                                 {{ $assignment->attachment }}
                            </a>
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
                                    <form action="{{ route('student.assignment.delete',["id"=>$submission->id]) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                        <p>
                                            <i class="fas fa-file-alt"></i>
                                            <a href="{{ asset('storage/' . $submission->attachment) }}" target="_blank">{{ $submission->attachment }}</a>
                                            <button type="submit" class="btn btn-link p-0 align-baseline text-danger" style="border: none; background: none;" title="Delete" onclick="return confirm('Are you sure you want to delete this file?')">
                                                <i class="fas fa-times-circle fa-lg"></i>
                                            </button>
                                        </p>
                                            
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