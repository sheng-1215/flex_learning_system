@extends('layouts.app')
@section('content')
    <div class="container-fluid py-2">
        <div class="container py-1">
            <div class="card mb-4">
                <div class="card-header text-white" style="background: url('{{ asset('img/technology.jpeg') }}') center center / cover no-repeat; height: 200px; position: relative;">
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
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{ route('student.CUActivity', ['id'=>'1','topic' => $topic->id]) }}" class="text-decoration-none text-dark flex-grow-1">
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
                                    <video id="topic-video" class="embed-responsive-item w-100" controls>
                                        <source src="{{ asset('asset/video/' . $selectedTopic->file_path) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const video = document.getElementById('topic-video');
                                        let lastSentProgress = 0;

                                        video.addEventListener('timeupdate', function() {
                                            const percent = Math.floor((video.currentTime / video.duration) * 100);

                                            // Only send if progress changed by at least 5%
                                            if (Math.abs(percent - lastSentProgress) >= 5 && percent <= 100) {
                                                lastSentProgress = percent;
                                                fetch("{{ route('student.topic.progress.update') }}", {
                                                    method: "POST",
                                                    headers: {
                                                        "Content-Type": "application/json",
                                                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                                    },
                                                    body: JSON.stringify({
                                                        topic_id: "{{ $selectedTopic->id }}",
                                                        progress: percent
                                                    })
                                                });
                                            }
                                        });
                                    });
                                </script>
                            @elseif(isset($selectedTopic) && $selectedTopic->type==='slideshow')
                                <iframe 
                                    src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('asset/slideshow/')) }}" 
                                    width="100%" 
                                    height="500px" 
                                    frameborder="0">
                                </iframe>

                            @elseif(isset($selectedTopic) && $selectedTopic->type==='document')
                                @php
                                    $fileName = $selectedTopic->file_path
                                @endphp
                                @if(Str::endsWith($fileName, '.pdf'))
                                    <iframe src="{{ asset('asset/document/' . $fileName) }}" width="100%" height="600px"></iframe>

                                @elseif(Str::endsWith($fileName, '.docx'))
                                    <iframe 
                                        src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/files/' . $fileName)) }}" 
                                        width="100%" height="600px">
                                    </iframe>

                                @else
                                    <p>Unsupported file format.</p>
                                @endif
                                <canvas id="pdf-container"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <h2>Welcome to {{ auth()->user()->enrollments->first()->course->activities->first()->title  }}</h2>
                                    <p class="lead mt-3">
                                        {{  auth()->user()->enrollments->first()->course->activities->first()->description  }}
                                    </p>
                                    <img src="{{ asset('img/welcome_learning.svg') }}" alt="Welcome" style="max-width: 300px;" class="my-4">
                                    <p>
                                        Ready to get started? Choose a topic and dive in!
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>

   

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
</style>