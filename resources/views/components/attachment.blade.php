@props(['label'=>'Upload Content','name'=>'file_path','required'=>false,'multiple'=>false])
<div class="form-group">
    <label for="content_file">{{ $label }}</label>
    <input type="file" class="form-control-file" id="file_path" name="{{ $name }}" {{ $required ? 'required' : '' }} {{ $multiple ? 'multiple' : '' }}>
    <div id="file-info" class="mt-2" style="display:none;">
        <span id="file-icon" class="mr-2"></span>
        <span id="file-name"></span>
        <span id="file-size" class="text-muted ml-2"></span>
    </div>
</div>
                        <script>
                            document.getElementById('file_path').addEventListener('change', function(e) {
                                const file = e.target.files[0];
                                const fileInfo = document.getElementById('file-info');
                                const fileName = document.getElementById('file-name');
                                const fileSize = document.getElementById('file-size');
                                const fileIcon = document.getElementById('file-icon');
                                if (file) {
                                    fileInfo.style.display = 'inline-block';
                                    fileName.textContent = file.name;
                                    fileSize.textContent = '(' + (file.size/1024).toFixed(2) + ' KB)';
                                    // Set icon based on file type
                                    let iconClass = 'fa-file';
                                    if (file.type === 'application/pdf') iconClass = 'fa-file-pdf text-danger';
                                    else if (file.type.startsWith('video/')) iconClass = 'fa-file-video text-primary';
                                    else if (file.name.match(/\.(ppt|pptx)$/i)) iconClass = 'fa-file-powerpoint text-warning';
                                    fileIcon.innerHTML = `<i class="fas ${iconClass}"></i>`;
                                } else {
                                    fileInfo.style.display = 'none';
                                }
                            });
                        </script>
