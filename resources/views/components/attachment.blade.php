@props(['label'=>'Upload Content','name'=>'file_path','required'=>false,'multiple'=>false])
<div class="form-group">
    <label for="content_file" class="font-weight-bold text-dark">{{ $label }} {{ $required ? '<span class="text-danger">*</span>' : '' }}</label>
    <div class="custom-file">
        <input type="file" class="custom-file-input" id="content_file" name="{{ $name }}" {{ $required ? 'required' : '' }} {{ $multiple ? 'multiple' : '' }} aria-describedby="file-info">
        <label class="custom-file-label" for="content_file">Choose file(s)</label>
    </div>
    <small class="form-text text-muted mt-1">Supported formats: pdf, doc, docx, xls, xlsx</small>
    <div id="file-info" class="mt-3 text-muted small d-flex align-items-center" style="display:none;">
        <span id="file-icon" class="mr-2"></span>
        <span id="file-name" class="text-primary font-weight-medium"></span>
        <span id="file-size" class="ml-2"></span>
    </div>
</div>

<script>
    document.getElementById('content_file').addEventListener('change', function(e) {
        const files = e.target.files;
        const fileInfo = document.getElementById('file-info');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');
        const fileIcon = document.getElementById('file-icon');
        const fileLabel = document.querySelector('.custom-file-label');

        if (files.length > 0) {
            const file = files[0]; // Handle first file for display (extend for multiple if needed)
            fileInfo.style.display = 'flex';
            fileName.textContent = file.name;
            fileSize.textContent = '(' + (file.size / 1024).toFixed(2) + ' KB)';
            fileLabel.textContent = files.length > 1 ? `${files.length} files selected` : file.name;

            // Set icon based on file type
            let iconClass = 'fa-file text-secondary';
            if (file.type === 'application/pdf') iconClass = 'fa-file-pdf text-danger';
            else if (file.type.startsWith('video/')) iconClass = 'fa-file-video text-primary';
            else if (file.name.match(/\.(ppt|pptx)$/i)) iconClass = 'fa-file-powerpoint text-warning';
            else if (file.type.startsWith('image/')) iconClass = 'fa-file-image text-success';
            else if (file.name.match(/\.(doc|docx)$/i)) iconClass = 'fa-file-word text-primary';
            fileIcon.innerHTML = `<i class="fas ${iconClass}"></i>`;

            // Check file size limit (e.g., 10MB per file)
            if (Array.from(files).some(f => f.size > 10 * 1024 * 1024)) {
                alert('One or more files exceed the 10MB limit!');
                e.target.value = ''; // Clear input
                fileInfo.style.display = 'none';
                fileLabel.textContent = 'Choose file(s)';
            }
        } else {
            fileInfo.style.display = 'none';
            fileLabel.textContent = 'Choose file(s)';
        }
    });
</script>

<style>
    .custom-file {
        position: relative;
        overflow: hidden;
        border-radius: 6px;
    }
    .custom-file-input {
        width: 100%;
        height: calc(2.25rem + 2px);
        padding: 0.5rem 1rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 6px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .custom-file-input:focus {
        border-color: #007bff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    .custom-file-label {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        padding: 0.5rem 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #6c757d;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 6px;
        cursor: pointer;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        pointer-events: none;
    }
    .custom-file-label::after {
        content: "Browse";
        position: absolute;
        top: 0;
        right: 0;
        height: 100%;
        padding: 0.5rem 1rem;
        background-color: #e9ecef;
        color: #495057;
        border-top-right-radius: 6px;
        border-bottom-right-radius: 6px;
        font-weight: 500;
        pointer-events: none;
    }
    .custom-file-input:focus ~ .custom-file-label {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    #file-info {
        transition: opacity 0.3s ease;
        opacity: 1;
    }
    #file-info.hidden {
        opacity: 0;
    }
    #file-icon .fas {
        font-size: 1.2rem;
    }
    .form-text.text-muted {
        font-size: 0.85rem;
    }
    @media (max-width: 767.98px) {
        .custom-file-label {
            font-size: 0.9rem;
        }
        .custom-file-label::after {
            padding: 0.3rem 0.8rem;
            font-size: 0.9rem;
        }
    }
</style>