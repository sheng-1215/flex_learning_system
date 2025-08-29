@extends('layouts.app')
@section('content')
    <div class="container-fluid py-2">
        <div class="container py-1">
            <div class="card mb-4">
                <div class="card-header text-white" style="background: url('{{ asset(auth()->user()->enrollments->first()->course->cover_image) ?? asset('img/no-cover.ppg') }}') center center / cover no-repeat; height: 200px; position: relative;">
                    <h2 class="position-absolute bottom-0 start-0 m-3">{{ auth()->user()->enrollments->first()->course->title }}</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Course Topics</h5>
                            <hr>
                            <ul class="list-group list-group-flush">
                                @foreach ($topics as $topic)
                                    <li class="list-group-item d-flex justify-content-between align-items-center" data-topic-id="{{ $topic->id }}">
                                        <a href="{{ route('student.CUActivity', [$activity->id,'topic' => $topic->id]) }}" class="text-decoration-none text-dark flex-grow-1">
                                            {{ strtoupper($topic->title) }} 
                                        </a>

                                        {{-- Progress circle --}}
                                        <div class="text-center me-2">
                                            <div style="width: 45px; height: 45px; position: relative;">
                                                <svg viewBox="0 0 36 36" class="circular-chart" style="width: 100%; height: 100%;">
                                                    <path class="circle-bg"
                                                        d="M18 2.0845
                                                            a 15.9155 15.9155 0 0 1 0 31.831
                                                            a 15.9155 15.9155 0 0 1 0 -31.831"
                                                        fill="none"
                                                        stroke="#eee"
                                                        stroke-width="3.8"/>
                                                    <path class="circle"
                                                        d="M18 2.0845
                                                            a 15.9155 15.9155 0 0 1 0 31.831
                                                            a 15.9155 15.9155 0 0 1 0 -31.831"
                                                        fill="none"
                                                        stroke="#007bff"
                                                        stroke-width="3.8"
                                                        stroke-dasharray="{{ $topic->progress ?? 0 }}, 100"/>
                                                    <text x="18" y="20.35" class="percentage" text-anchor="middle" font-size="8" fill="#333">
                                                        {{ $topic->progress ?? 0 }}%
                                                    </text>
                                                </svg>
                                            </div>
                                        </div>

                                        {{-- Arrow icon --}}
                                        <i class="fas fa-chevron-right text-secondary"></i>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card mb-4">
                        <div class="card-body">
                            @if(isset($selectedTopic) && $selectedTopic->type==='video')
                                <div class="ratio ratio-16x9">
                                     @php
                                        $videoPath = json_decode($selectedTopic->file_path);        
                                    @endphp
                                    <video id="topic-video" class="embed-responsive-item w-100" controls>
                                        <source src="{{ asset('storage/' . $videoPath[0]->path) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const video = document.getElementById('topic-video');
                                        let lastSentProgress = 0;
                                        let isProgressLoaded = false;
                                        let isVideoReady = false;
                                        let retryCount = 0;
                                        const maxRetries = 3;

                                        // Debug logging
                                        console.log('Video progress tracking initialized for topic: {{ $selectedTopic->id }}');
                                        console.log('CSRF Token:', "{{ csrf_token() }}");
                                        console.log('Progress update route:', "{{ route('student.topic.progress.update') }}");
                                        console.log('Progress get route:', "{{ route('student.topic.progress.get') }}");
                                        
                                        // Update debug panel
                                        if (typeof logToDebugPanel === 'function') {
                                            logToDebugPanel('video', 'Initializing video progress tracking...');
                                            logToDebugPanel('status', 'Initializing...');
                                        }

                                        // Check if video element exists
                                        if (!video) {
                                            console.error('Video element not found!');
                                            return;
                                        }

                                        // Wait for video to be ready
                                        video.addEventListener('loadedmetadata', function() {
                                            console.log('Video metadata loaded. Duration:', video.duration);
                                            isVideoReady = true;
                                            
                                            if (typeof logToDebugPanel === 'function') {
                                                logToDebugPanel('video', `Video ready - Duration: ${video.duration}s`);
                                                logToDebugPanel('status', 'Video loaded, loading saved progress...');
                                            }
                                            
                                            if (!isProgressLoaded) {
                                                loadSavedProgress();
                                                isProgressLoaded = true;
                                            }
                                        });

                                        // Also check for canplay event
                                        video.addEventListener('canplay', function() {
                                            console.log('Video can start playing');
                                            if (!isVideoReady) {
                                                isVideoReady = true;
                                                if (typeof logToDebugPanel === 'function') {
                                                    logToDebugPanel('video', 'Video can start playing');
                                                }
                                                if (!isProgressLoaded) {
                                                    loadSavedProgress();
                                                    isProgressLoaded = true;
                                                }
                                            }
                                        });

                                        // Load saved progress function
                                        function loadSavedProgress() {
                                            console.log('Loading saved progress...');
                                            if (typeof logToDebugPanel === 'function') {
                                                logToDebugPanel('status', 'Loading saved progress...');
                                            }
                                            fetch("{{ route('student.topic.progress.get') }}?topic_id={{ $selectedTopic->id }}", {
                                                method: "GET",
                                                headers: {
                                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                                    "Accept": "application/json",
                                                    "Content-Type": "application/json"
                                                },
                                                credentials: 'same-origin'
                                            })
                                            .then(response => {
                                                console.log('Progress get response status:', response.status);
                                                if (!response.ok) {
                                                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                                                }
                                                return response.json();
                                            })
                                            .then(data => {
                                                console.log('Progress data received:', data);
                                                if (data.success && data.progress > 0 && video.duration) {
                                                    const savedTime = (data.progress / 100) * video.duration;
                                                    video.currentTime = savedTime;
                                                    lastSentProgress = data.progress;
                                                    console.log('Restored progress:', data.progress + '% at time:', savedTime);
                                                    
                                                    // Update progress circle immediately
                                                    updateProgressCircle({{ $selectedTopic->id }}, data.progress);
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error loading progress:', error);
                                                retryLoadProgress();
                                            });
                                        }

                                        // Retry loading progress
                                        function retryLoadProgress() {
                                            if (retryCount < maxRetries) {
                                                retryCount++;
                                                console.log(`Retrying to load progress (attempt ${retryCount}/${maxRetries})...`);
                                                setTimeout(loadSavedProgress, 1000 * retryCount);
                                            }
                                        }

                                        // Update progress function with retry mechanism
                                        function updateProgress(percent) {
                                            if (!isVideoReady || percent < 0 || percent > 100) {
                                                return;
                                            }

                                            console.log('Updating progress to:', percent + '%');
                                            
                                            fetch("{{ route('student.topic.progress.update') }}", {
                                                method: "POST",
                                                headers: {
                                                    "Content-Type": "application/json",
                                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                                    "Accept": "application/json"
                                                },
                                                credentials: 'same-origin',
                                                body: JSON.stringify({
                                                    topic_id: "{{ $selectedTopic->id }}",
                                                    progress: percent
                                                })
                                            })
                                            .then(response => {
                                                console.log('Progress update response status:', response.status);
                                                if (!response.ok) {
                                                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                                                }
                                                return response.json();
                                            })
                                            .then(data => {
                                                console.log('Progress update success:', data);
                                                if (data.success) {
                                                    lastSentProgress = percent;
                                                    // Update progress circle in sidebar
                                                    updateProgressCircle({{ $selectedTopic->id }}, data.progress);
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error updating progress:', error);
                                                // Don't update lastSentProgress on error to allow retry
                                            });
                                        }

                                        // Update progress more frequently (every 1% change)
                                        video.addEventListener('timeupdate', function() {
                                            if (!isVideoReady || !video.duration) {
                                                return;
                                            }

                                            const percent = Math.floor((video.currentTime / video.duration) * 100);

                                            // Send progress update every 1% change
                                            if (Math.abs(percent - lastSentProgress) >= 1 && percent <= 100) {
                                                updateProgress(percent);
                                            }
                                        });

                                        // Save progress when video ends
                                        video.addEventListener('ended', function() {
                                            console.log('Video ended, marking as 100% complete');
                                            updateProgress(100);
                                        });

                                        // Save progress when page is about to unload
                                        window.addEventListener('beforeunload', function() {
                                            if (isVideoReady && video.duration) {
                                                const currentPercent = Math.floor((video.currentTime / video.duration) * 100);
                                                if (currentPercent > lastSentProgress) {
                                                    console.log('Saving final progress before unload:', currentPercent + '%');
                                                    // Send final progress update using FormData
                                                    const formData = new FormData();
                                                    formData.append('topic_id', "{{ $selectedTopic->id }}");
                                                    formData.append('progress', currentPercent);
                                                    formData.append('_token', "{{ csrf_token() }}");
                                                    
                                                    navigator.sendBeacon("{{ route('student.topic.progress.update') }}", formData);
                                                }
                                            }
                                        });

                                        // Save progress when video is paused
                                        video.addEventListener('pause', function() {
                                            if (isVideoReady && video.duration) {
                                                const currentPercent = Math.floor((video.currentTime / video.duration) * 100);
                                                if (currentPercent > lastSentProgress) {
                                                    console.log('Saving progress on pause:', currentPercent + '%');
                                                    updateProgress(currentPercent);
                                                }
                                            }
                                        });

                                        // Handle video errors
                                        video.addEventListener('error', function(e) {
                                            console.error('Video error occurred:', e);
                                            console.error('Video error details:', video.error);
                                        });

                                        // Handle video load start
                                        video.addEventListener('loadstart', function() {
                                            console.log('Video loading started');
                                        });

                                        // Handle video can play through
                                        video.addEventListener('canplaythrough', function() {
                                            console.log('Video can play through without buffering');
                                        });
                                    });

                                    // Function to update progress circle in sidebar
                                    function updateProgressCircle(topicId, progress) {
                                        console.log('Updating progress circle for topic', topicId, 'to', progress + '%');
                                        const progressCircle = document.querySelector(`[data-topic-id="${topicId}"] .circle`);
                                        const progressText = document.querySelector(`[data-topic-id="${topicId}"] .percentage`);
                                        
                                        if (progressCircle && progressText) {
                                            progressCircle.setAttribute('stroke-dasharray', `${progress}, 100`);
                                            progressText.textContent = progress + '%';
                                            console.log('Progress circle updated successfully');
                                        } else {
                                            console.warn('Progress circle elements not found for topic:', topicId);
                                        }
                                    }
                                </script>
                            @elseif(isset($selectedTopic) && $selectedTopic->type==='slideshow')
                                @php
                                    $slidePaths = json_decode($selectedTopic->file_path);
                                    $slides = [];
                                    if (is_array($slidePaths)) {
                                        foreach ($slidePaths as $path) {
                                            $slides[] = asset('storage/' . $path->path);
                                        }
                                    }
                                @endphp
                                <div id="slideshow-container" style="text-align: center;">
                                    <img id="slideshow-image" src="{{ $slides[0] ?? '' }}" style="max-height: 500px; max-width: 100%;">
                                    <div style="margin-top: 10px;">
                                        <button id="prev-slide" class="btn btn-outline-primary me-2">Previous</button>
                                        <button id="next-slide" class="btn btn-primary">Next</button>
                                    </div>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const slides = <?php echo json_encode($slides); ?>;
                                        let currentSlide = 0;

                                        const image = document.getElementById('slideshow-image');
                                        const prevButton = document.getElementById('prev-slide');
                                        const nextButton = document.getElementById('next-slide');

                                        function showSlide(index) {
                                            if (index >= slides.length) currentSlide = 0;
                                            if (index < 0) currentSlide = slides.length - 1;
                                            image.src = slides[currentSlide];
                                        }

                                        prevButton.addEventListener('click', () => {
                                            currentSlide--;
                                            showSlide(currentSlide);
                                        });

                                        nextButton.addEventListener('click', () => {
                                            currentSlide++;
                                            showSlide(currentSlide);
                                        });

                                        showSlide(currentSlide);
                                    });
                                </script>
                            @elseif(isset($selectedTopic) && $selectedTopic->type==='document')
                                @php
                                    $fileName = json_decode($selectedTopic->file_path);
                                    $fileName = $fileName[0]->path ?? '';
                                @endphp
                                @if(Str::endsWith($fileName, '.pdf'))
                                    <iframe src="{{ asset('storage/' . $fileName) }}" width="100%" height="600px"></iframe>
                                @elseif(Str::endsWith($fileName, '.docx'))
                                    <iframe 
                                        src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/' . $fileName)) }}" 
                                        width="100%" height="600px">
                                    </iframe>
                                @else
                                    <p>Unsupported file format.</p>
                                @endif
                                <canvas id="pdf-container"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <h2>Welcome to {{ $activity->title }}</h2>
                                    <p class="lead mt-3">{{ $activity->description }}</p>
                                    <div class="d-inline-block mt-4 p-3 border rounded bg-light left-panel-hint">
                                        <i class="fas fa-hand-point-left text-primary me-2"></i>
                                        <span class="fw-semibold">Start here:</span>
                                        <span class="text-muted">Select a topic from the left panel to begin.</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- Debug Panel for Video Progress --}}
    @if(isset($selectedTopic) && $selectedTopic->type==='video')
    <div class="container-fluid mt-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-bug text-warning"></i> 
                    Video Progress Debug Panel 
                    <button class="btn btn-sm btn-outline-secondary float-end" onclick="toggleDebugPanel()">
                        Toggle Debug
                    </button>
                </h6>
            </div>
            <div class="card-body" id="debug-panel" style="display: none;">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Video Status:</h6>
                        <div id="video-status" class="text-muted">Loading...</div>
                        
                        <h6 class="mt-3">Progress Tracking:</h6>
                        <div id="progress-status" class="text-muted">Waiting...</div>
                        
                        <h6 class="mt-3">Last Update:</h6>
                        <div id="last-update" class="text-muted">None</div>
                    </div>
                    <div class="col-md-6">
                        <h6>Network Requests:</h6>
                        <div id="network-logs" class="text-muted small" style="max-height: 200px; overflow-y: auto;">
                            No requests yet...
                        </div>
                        
                        <h6 class="mt-3">Errors:</h6>
                        <div id="error-logs" class="text-danger small" style="max-height: 150px; overflow-y: auto;">
                            No errors...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Debug panel functionality
        function toggleDebugPanel() {
            const panel = document.getElementById('debug-panel');
            panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        }
        
        // Debug logging functions
        function logToDebugPanel(type, message) {
            const timestamp = new Date().toLocaleTimeString();
            const logEntry = `[${timestamp}] ${message}`;
            
            switch(type) {
                case 'network':
                    const networkLogs = document.getElementById('network-logs');
                    networkLogs.innerHTML += logEntry + '<br>';
                    networkLogs.scrollTop = networkLogs.scrollHeight;
                    break;
                case 'error':
                    const errorLogs = document.getElementById('error-logs');
                    errorLogs.innerHTML += logEntry + '<br>';
                    errorLogs.scrollTop = errorLogs.scrollHeight;
                    break;
                case 'status':
                    document.getElementById('progress-status').innerHTML = message;
                    break;
                case 'video':
                    document.getElementById('video-status').innerHTML = message;
                    break;
                case 'update':
                    document.getElementById('last-update').innerHTML = message;
                    break;
            }
        }
        
        // Override console methods to also log to debug panel
        const originalLog = console.log;
        const originalError = console.error;
        const originalWarn = console.warn;
        
        console.log = function(...args) {
            originalLog.apply(console, args);
            if (args[0] && typeof args[0] === 'string' && args[0].includes('progress')) {
                logToDebugPanel('network', args.join(' '));
            }
        };
        
        console.error = function(...args) {
            originalError.apply(console, args);
            logToDebugPanel('error', args.join(' '));
        };
        
        console.warn = function(...args) {
            originalWarn.apply(console, args);
            logToDebugPanel('error', args.join(' '));
        };
    </script>
    @endif
    
    <script>
        const url = "{{ $filePath ?? '' }}";
        console.log("PDF URL:", url);

        if (!url) {
            console.error("No PDF file path provided.");
        }

        const loadingTask = pdfjsLib.getDocument(url);

        loadingTask.promise.then(pdf => {
            console.log(`PDF loaded with ${pdf.numPages} pages.`);

            for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
                pdf.getPage(pageNumber).then(page => {
                    const scale = 1.5;
                    const viewport = page.getViewport({ scale });

                    const canvas = document.createElement("canvas");
                    const context = canvas.getContext("2d");
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };

                    page.render(renderContext);

                    // Append each canvas to container
                    document.getElementById("pdf-container").appendChild(canvas);
                });
            }
        }).catch(error => {
            console.error("Error loading PDF:", error);
        });
    </script>
@endsection
@include('student.footer')
<style>
@media (max-width: 576px) {
    .container, .card, .row, .col, .form-group, .btn, .table-responsive, .table {
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
    .table {
        font-size: 0.95rem;
    }
}
#slideshow-container .btn {
    border-radius: 5px;
    padding: 8px 16px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
#slideshow-container .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.left-panel-hint {
    box-shadow: 0 2px 6px rgba(13,110,253,0.08);
}
</style>