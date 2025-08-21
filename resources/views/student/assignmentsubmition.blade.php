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
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-left">{{ $assignment->assignment_name }}</h3>
                    <div class="mb-3">
                        <p>Due Date: {{ $assignment->due_date }}</p>
                    </div>
                    <div class="mb-3">
                        <p>{!! $assignment->description !!}</p>
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
                    @if ($submissions->isNotEmpty())
                    <div class="mb-3">
                        <h5>Your submission</h5>
                        @foreach ($submissions as $submission)
                            <div class="alert alert-success py-2 px-3 d-flex align-items-center justify-content-between" role="alert">
                                <div style="max-width: 70vw; overflow-x: auto;">
                                    <a href="{{ asset('storage/' . $submission->attachment) }}" target="_blank" class="text-decoration-none" style="color:#e67e22; white-space:nowrap;">
                                        <i class="fas fa-file-alt mr-2"></i>
                                        {{ $submission->attachment }}
                                    </a>
                                    <small class="text-muted ml-2">Status: {{ $submission->status }} | {{ $submission->submitted_at }}</small>
                                </div>
                                <form action="{{ route('student.assignment.delete',['id'=>$submission->id]) }}" method="post" class="ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this submitted file?')">Delete</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                    @endif
                    <div class="mb-3">
                        <h4>submmit assignment</h4>
                        <small>Files to submit *</small>
                        <div id="file-upload" class="mb-3">
                            @if ($submissions->isnotEmpty())
                                <div class="alert alert-info">You have already submitted a file. If you need to replace it, click the button below to upload a new file.</div>
                                <button type="button" id="toggle-replace" class="btn btn-outline-primary btn-sm">Replace Submission</button>
                            @else
                                <div class="alert alert-info">
                                    <p>Upload your assignment file here. Accepted formats: PDF, DOCX, TXT.</p>
                                    <p>Maximum file size: 10MB.</p>
                                </div>
                            @endif
                            

                        </div>

                        <br>
                        <div id="submit-form-block" style="{{ $submissions->isNotEmpty() ? 'display:none;' : '' }}">
                            <p>After uploading, you must click the submit to completed submission</p>
                            <form action="{{ route('student.assignment.submit', ['id' => $assignment->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <div class="custom-file" style="max-width: 600px;">
                                        <input type="file" class="custom-file-input" id="assignment_file_form" name="file" accept=".pdf,.doc,.docx" required>
                                        <label class="custom-file-label" for="assignment_file_form">Choose file</label>
                                    </div>
                                    <!-- Selected file preview (styled like the Attachment above) -->
                                    <div id="selected-file-wrapper" class="mt-2" style="display:none; max-width: 600px;">
                                        <small>Selected File:</small>
                                        <br>
                                        <a id="selected-file-link" href="#" target="_blank" style="display:inline-block; max-width:100%; white-space:nowrap; overflow-x:auto; text-overflow:ellipsis; color:#e67e22;">
                                            <i class="fas fa-file-alt"></i>
                                            <span id="selected-file-name"></span>
                                        </a>
                                        <small id="selected-file-meta" class="text-muted ml-2"></small>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane mr-1"></i>Submit</button>
                            </form>
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
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    // Match admin activityTopic custom file input behavior
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass('selected').html(fileName || 'Choose file');
        // Populate preview for the main submit input
        const input = this;
        if (input && input.files && input.files.length > 0) {
            const f = input.files[0];
            const sizeKB = Math.round(f.size / 1024);
            $('#selected-file-name').text(f.name);
            $('#selected-file-meta').text(`(${f.type || 'unknown type'}, ${sizeKB} KB)`);
            // Create a temporary object URL for preview/download
            const url = URL.createObjectURL(f);
            $('#selected-file-link').attr('href', url);
            $('#selected-file-wrapper').show();
        } else {
            $('#selected-file-name').text('');
            $('#selected-file-meta').text('');
            $('#selected-file-link').attr('href', '#');
            $('#selected-file-wrapper').hide();
        }
    });

    // Toggle replace submission form visibility
    $(document).on('click', '#toggle-replace', function(){
        $('#submit-form-block').toggle();
        $('html, body').animate({
            scrollTop: $('#submit-form-block').offset().top - 80
        }, 300);
    });

    // If the server rendered an existing file (after choosing but before submit), keep label synced
    (function initExistingLabel(){
        var input = document.getElementById('assignment_file_form');
        if (input && input.files && input.files.length > 0) {
            var f = input.files[0];
            $(input).next('.custom-file-label').addClass('selected').html(f.name);
        }
    })();

    // Fallback: vanilla JS listener in case jQuery binding fails or styles hide label
    document.addEventListener('DOMContentLoaded', function(){
        var input = document.getElementById('assignment_file_form');
        if (!input) return;
        input.addEventListener('change', function(e){
            var f = (e.target.files && e.target.files[0]) ? e.target.files[0] : null;
            if (f) {
                var sizeKB = Math.round(f.size / 1024);
                var link = document.getElementById('selected-file-link');
                var nameEl = document.getElementById('selected-file-name');
                var metaEl = document.getElementById('selected-file-meta');
                var wrapEl = document.getElementById('selected-file-wrapper');
                if (nameEl) nameEl.textContent = f.name;
                if (metaEl) metaEl.textContent = '(' + (f.type || 'unknown type') + ', ' + sizeKB + ' KB)';
                if (link) link.href = URL.createObjectURL(f);
                if (wrapEl) wrapEl.style.display = 'block';
                var lbl = document.querySelector('label[for="assignment_file_form"]');
                if (lbl) lbl.classList.add('selected'), lbl.textContent = f.name;
            }
        });
    });
</script>