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
                                <div class="embed-responsive embed-responsive-16by9 mb-3">
                                    <video class="embed-responsive-item w-100" controls>
                                        <source src="{{ asset('asset/video/' . $selectedTopic->file_path) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            @elseif(isset($selectedTopic) && $selectedTopic->type==='slideshow')
                                <iframe 
                                    src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('asset/slideshow/')) }}" 
                                    width="100%" 
                                    height="500px" 
                                    frameborder="0">
                                </iframe>

                            @elseif(isset($selectedTopic) && $selectedTopic->type==='document')
                                @php
                                    $filePath = asset('asset/document/' . $selectedTopic->file_path);
                                    $fileExtension = pathinfo($selectedTopic->file_path, PATHINFO_EXTENSION);
                                    $isPdf = strtolower($fileExtension) === 'pdf';
                                @endphp
                                {{-- <div class="mb-3">
                                    <iframe 
                                        src="https://docs.google.com/gview?url=&embedded=true" 
                                        style="width:100%; height:600px;" 
                                        frameborder="0">
                                    </iframe>
                                    <a href="{{ asset('asset/document/' . $selectedTopic->file_path) }}" class="btn btn-primary mt-2" target="_blank">
                                        <i class="fas fa-file-download"></i> Download PDF
                                    </a>
                                </div> --}}
                                <canvas id="pdf-container"></canvas>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div>
                
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