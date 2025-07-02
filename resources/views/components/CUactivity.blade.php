@props(['activitie'])

<div class="col-lg-4 col-md-6 mb-4">
    <div class="rounded overflow-hidden mb-2">
        {{-- <img class="img-fluid" src="{{ $image ?? 'img/course-1.jpg' }}" alt=""> --}}
        <div class="bg-secondary p-4">
            <div class="d-flex justify-content-between mb-3">
                <small class="m-0"><i class="fa fa-users text-primary mr-2"></i>{{ $students ?? 25 }} Students</small>
                <small class="m-0"><i class="far fa-clock text-primary mr-2"></i>{{ $activitie->due_date ?? '01h 30m' }}</small>
            </div>
            <a class="h5" href="{{ route('student.CUActivity',$activitie->id) ?? '#' }}">{{ $activitie->title ?? 'Web design & development courses for beginner' }}</a>
            <div class="border-top mt-4 pt-4">
                    {{-- <div class="d-flex justify-content-between">
                        <h6 class="m-0">
                            <i class="fa fa-star text-primary mr-2"></i>{{ $rating ?? '4.5' }}
                            <small>({{ $reviews ?? 250 }})</small>
                        </h6>
                        <h5 class="m-0">${{ $price ?? '99' }}</h5>
                    </div> --}}
            </div>
        </div>
    </div>
</div>
