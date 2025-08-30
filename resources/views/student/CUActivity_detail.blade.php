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
                            <div class="topics-scroll-container">
                                <ul class="list-group list-group-flush">
                                    @foreach ($topics as $topic)
                                        <li class="list-group-item d-flex justify-content-between align-items-center" data-topic-id="{{ $topic->id }}">
                                            <a href="{{ route('student.CUActivity', [$activity->id,'topic' => $topic->id]) }}" 
                                               class="text-decoration-none text-dark flex-grow-1 topic-link" 
                                               data-topic-id="{{ $topic->id }}">
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
                </div>
                <div class="col-md-9">
                    <div class="card mb-4">
                        <div class="card-body">
                            @if(isset($selectedTopic) && $selectedTopic->type==='video')
                                <div class="ratio ratio-16x9">
                                     @php
                                        $videoPath = json_decode($selectedTopic->file_path);        
                                    @endphp
                                    <video id="topic-video" class="embed-responsive-item w-100" controls controlsList="nodownload noplaybackrate" disablepictureinpicture>
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

                                        // Check if video element exists
                                        if (!video) {
                                            console.error('Video element not found!');
                                            return;
                                        }

                                        // Prevent skipping forward
                                        let lastAllowedTime = 0;
                                        video.addEventListener('seeking', function() {
                                            if (video.currentTime > lastAllowedTime + 0.5) {
                                                video.currentTime = lastAllowedTime;
                                            }
                                        });
                                        video.addEventListener('timeupdate', function() {
                                            if (video.currentTime > lastAllowedTime) {
                                                lastAllowedTime = video.currentTime;
                                            }
                                        });

                                        // Wait for video to be ready
                                        video.addEventListener('loadedmetadata', function() {
                                            console.log('Video metadata loaded. Duration:', video.duration);
                                            isVideoReady = true;
                                            
                                            // No server progress to load
                                        });

                                        // Also check for canplay event
                                        video.addEventListener('canplay', function() {
                                            console.log('Video can start playing');
                                            if (!isVideoReady) {
                                                isVideoReady = true;
                                                if (!isProgressLoaded) {
                                                    loadSavedProgress();
                                                    isProgressLoaded = true;
                                                }
                                            }
                                        });

                                        // Removed server-based saved progress fetching

                                        // Update progress UI only (no server request)
                                        function updateProgress(percent) {
                                            if (!isVideoReady || percent < 0 || percent > 100) {
                                                return;
                                            }
                                            lastSentProgress = percent;
                                            updateProgressCircle({{ $selectedTopic->id }}, percent);
                                        }

                                        // --- Simple session-based completion logic ---
                                        const sessionKey = `topic-{{ $selectedTopic->id }}-completeBy`;

                                        // When metadata is loaded, if no session key, set an expiry time = now + duration
                                        video.addEventListener('loadedmetadata', function() {
                                            try {
                                                if (video.duration && !sessionStorage.getItem(sessionKey)) {
                                                    const completeBy = Date.now() + Math.ceil(video.duration * 1000);
                                                    sessionStorage.setItem(sessionKey, String(completeBy));
                                                    console.log('Set session completeBy:', completeBy);
                                                }
                                            } catch (e) {
                                                console.warn('Session storage not available:', e);
                                            }
                                        });

                                        // On page load/refresh, if time already passed, mark 100% once
                                        (function checkSessionAndComplete() {
                                            try {
                                                const completeByStr = sessionStorage.getItem(sessionKey);
                                                if (completeByStr) {
                                                    const completeBy = parseInt(completeByStr, 10);
                                                    if (!Number.isNaN(completeBy) && Date.now() >= completeBy) {
                                                        console.log('Session time reached. Marking topic as 100%.');
                                                        updateProgress(100);
                                                        // Update UI immediately
                                                        updateProgressCircle({{ $selectedTopic->id }}, 100);
                                                        // Clear the session marker to avoid repeated calls
                                                        sessionStorage.removeItem(sessionKey);
                                                    }
                                                }
                                            } catch (e) {
                                                console.warn('Could not read session storage:', e);
                                            }
                                        })();

                                        // Mark complete when video ends
                                        video.addEventListener('ended', function() {
                                            console.log('Video ended, marking as 100% complete');
                                            try { sessionStorage.removeItem(sessionKey); } catch (e) {}
                                            updateProgress(100);
                                        });

                                        // Remove continuous and frequent updates. Progress will only be set to 100%
                                        // when session time is reached on refresh or when the video ends.

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
    
    <script>
        // Topic link auto-scroll functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Check if we have a selected topic (URL contains topic parameter)
            const urlParams = new URLSearchParams(window.location.search);
            const selectedTopicId = urlParams.get('topic');
            
            if (selectedTopicId) {
                // Wait a bit for the page to fully load
                setTimeout(function() {
                    // Find the content area to scroll to
                    const contentArea = document.querySelector('.col-md-9 .card');
                    if (contentArea) {
                        // Scroll to the content area with smooth animation
                        contentArea.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'start',
                            inline: 'nearest'
                        });
                        
                        // Add a subtle highlight effect to the selected topic
                        const selectedTopicLink = document.querySelector(`[data-topic-id="${selectedTopicId}"]`);
                        if (selectedTopicLink) {
                            selectedTopicLink.closest('li').style.backgroundColor = '#e3f2fd';
                            selectedTopicLink.closest('li').style.borderLeft = '4px solid #007bff';
                            
                            // Remove highlight after 3 seconds
                            setTimeout(function() {
                                selectedTopicLink.closest('li').style.backgroundColor = '';
                                selectedTopicLink.closest('li').style.borderLeft = '';
                            }, 3000);
                        }
                    }
                }, 300);
            }
            
            // Add click event listeners to all topic links for smooth scrolling
            const topicLinks = document.querySelectorAll('.topic-link');
            topicLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Store the target topic ID
                    const targetTopicId = this.getAttribute('data-topic-id');
                    
                    // Add a small delay to allow the page to start loading
                    setTimeout(function() {
                        // Find the content area and scroll to it
                        const contentArea = document.querySelector('.col-md-9 .card');
                        if (contentArea) {
                            contentArea.scrollIntoView({ 
                                behavior: 'smooth', 
                                block: 'start',
                                inline: 'nearest'
                            });
                        }
                    }, 100);
                });
            });
        });
    </script>
    
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

/* Course Topics Scroll Container */
.topics-scroll-container {
    max-height: 400px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #007bff #f8f9fa;
}

.topics-scroll-container::-webkit-scrollbar {
    width: 6px;
}

.topics-scroll-container::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 3px;
}

.topics-scroll-container::-webkit-scrollbar-thumb {
    background: #007bff;
    border-radius: 3px;
}

.topics-scroll-container::-webkit-scrollbar-thumb:hover {
    background: #0056b3;
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