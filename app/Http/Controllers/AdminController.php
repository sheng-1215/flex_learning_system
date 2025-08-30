<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\topic;
use App\Models\Course;
use App\Models\assignment;
use App\Models\CUActivity;
use App\Models\Enrollment;
use Illuminate\Http\Request;

use App\Models\assignmentSubmit;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminController extends Controller
{
    public function courses()
    {
        $courses = Course::with(['enrollments.user', 'enrollments' => function($query) {
            $query->where('role', 'student');
        }])->orderByDesc('created_at')->get();
        return view('admin.courses', ['courses' => $courses]);
    }

    public function importStudent($id,Request $request)
    {
        $student = DB::connection('second_db')->table('student')->where('id', $id)->first();
        if (!$student || !$student->s_email || !$student->ic) {
            return redirect()->back()->with('error', 'Student email not found.');
        }
        // Validate new password provided by admin (set, not verified against portal)
        $request->validate([
            'password' => ['required','string','min:2'],
        ]);
        
        // 1) Reset/Set password in the school portal db (second_db.sqlite)
        $now = now()->format('Y-m-d H:i:s');
        DB::connection('second_db')->table('student_login')
            ->updateOrInsert(
                ['student_ic' => $student->ic],
                [
                    'password' => Hash::make($request->password),
                    'status' => 'ACTIVE',
                    'date_update' => $now,
                ]
            );

        // Create or update a user in the main database with the admin-provided password
        $user = User::firstOrNew(['email' => $student->s_email]);
        $isNew = !$user->exists;
        $user->name = $student->s_name;
        $user->password = Hash::make($request->password);
        $user->role = 'student';
        $user->save();

        $successMessage = $isNew
            ? ($user->name . ' this student register successfully')
            : ($user->name . ' this student password reset successfully');
        return redirect()->route('admin.users')->with([
            'success' => $successMessage,
            'success_role' => 'student',
        ]);
    }

    public function addCourse(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
        }

        $course = Course::create([
            'title' => $request->title,
            'cover_image' => $coverPath,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.courses')->with('success', $course->title . ' added successfully.');
    }

    public function editCourse(Course $course)
    {
        return view('admin.edit_course', compact('course'));
    }

    public function updateCourse(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $coverPath = $course->cover_image; // Keep existing image by default
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
        }

        $course->update([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'cover_image' => $coverPath,
        ]);

        $course->enrollments()->where('role', 'lecturer')->delete();
        if ($request->filled('lecturers')) {
            foreach ($request->lecturers as $lecturerId) {
                Enrollment::create([
                    'user_id' => $lecturerId,
                    'course_id' => $course->id,
                    'role' => 'lecturer',
                ]);
            }
        }

        return redirect()->route('admin.courses')->with('success', $course->title . ' updated successfully.');
    }
    

    public function destroyCourse(Course $course)
    {
        $courseTitle = $course->title;
        $course->enrollments()->delete();
        $course->activities()->delete();
        $course->delete();

        return redirect()->route('admin.courses')->with('success', $courseTitle . ' deleted successfully.');
    }

    public function addUserToCourse(Course $course)
    {
        $users = User::where('role', 'student')
            ->whereDoesntHave('enrollments')
            ->get();
        $enrollments = Enrollment::where('course_id', $course->id)
            ->whereHas('user', function($query) {
                $query->where('role', 'student');
            })
            ->get();
        
        // 获取学生和讲师数量
        $studentCount = $course->student_count;
        $lecturerCount = $course->lecturer_count;
        
        return view('admin.addUserToCourse', compact('course', 'users', 'enrollments', 'studentCount', 'lecturerCount'));
    }

    public function submitUserToCourse(Request $request, Course $course)
    {
        $checkenrollment = Enrollment::where('user_id', $request->user_id);
        if ($checkenrollment->where('course_id', $course->id)->where('role', 'student')->exists()) {
            return back()->with("error", "User already enrolled in this course");
        }

        $enrollment = Enrollment::create([
            "course_id" => $course->id,
            "user_id" => $request->user_id,
        ]);

        if ($enrollment) {
            return redirect()->route('admin.addUserToCourse', $course->id)->with('success', 'Student added successfully.');
        }

        return back()->with("error", "User added failed, please try again later");
    }

    public function removeUserFromCourse(Enrollment $enrollment)
    {
        $courseId = $enrollment->course_id;
        $enrollment->delete();
        return redirect()->route('admin.addUserToCourse', $courseId)->with('success', 'Student removed successfully.');
    }

    public function registerStudentView()
    {
        $courses = \App\Models\Course::all();
        return view('admin.register_student', compact('courses'));
    }

    public function registerStudent(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
            'role' => ['required', 'in:student,lecturer'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($user->role === 'student' && $request->has('student_course')) {
            $courseId = $request->input('student_course');
            if (!Enrollment::where('user_id', $user->id)->where('course_id', $courseId)->where('role', 'student')->exists()) {
                Enrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $courseId,
                    'role' => 'student',
                    'enrollment_date' => now(),
                ]);
            }
        }

        if ($user->role === 'lecturer' && $request->has('lecturer_course')) {
            $courseId = $request->input('lecturer_course');
            if (!Enrollment::where('user_id', $user->id)->where('course_id', $courseId)->where('role', 'lecturer')->exists()) {
                Enrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $courseId,
                    'role' => 'lecturer',
                    'enrollment_date' => now(),
                ]);
            }
        }

        // Redirect to Manage Users with a tailored success message and role for targeted scrolling
        $successMessage = $user->name . ' this ' . $user->role . ' register successfully';
        return redirect()->route('admin.users')->with([
            'success' => $successMessage,
            'success_role' => $user->role,
        ]);
    }

    public function users()
    {
        $admins = User::where('role', 'admin')->orderByDesc('created_at')->get();
        $lecturers = User::where('role', 'lecturer')
            ->orderByDesc('created_at')
            ->paginate(6, ['*'], 'lecturers_page');
        
        $students = User::where('role', 'student')
            ->with('enrollments.course')
            ->orderByDesc('created_at')
            ->paginate(6, ['*'], 'students_page');
        $studentPortals= DB::connection('second_db')->table('student')->where("s_status",'ACTIVE')->get();
        
        $filtered=$studentPortals->flatMap(function($student) {
            $user= User::where('email', $student->s_email)->first();
            if(!$user){
                return [$student];
            }
            return [];
        });
        $page = request()->get('page', 1);
        $perPage = 10;
        $paginated = new LengthAwarePaginator(
            $filtered->forPage($page, $perPage),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return view('admin.users', compact('admins', 'lecturers', 'students', 'paginated'));
    }

    public function editUser(User $user)
    {
        $courses = Course::all();
        $enrolledCourseIds = $user->enrollments()->where('role', 'student')->pluck('course_id')->toArray();
        $lecturerCourseIds = $user->enrollments()->where('role', 'lecturer')->pluck('course_id')->toArray();
        
        return view('admin.edit_user', compact('user', 'courses', 'enrolledCourseIds', 'lecturerCourseIds'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'student_courses' => 'nullable|array',
            'lecturer_courses' => 'nullable|array',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if ($user->role === 'student') {
            $user->enrollments()->where('role', 'student')->delete();
            if ($request->has('student_courses')) {
                foreach ($request->student_courses as $courseId) {
                    if (!Enrollment::where('user_id', $user->id)->where('course_id', $courseId)->where('role', 'student')->exists()) {
                        Enrollment::create([
                            'user_id' => $user->id,
                            'course_id' => $courseId,
                            'role' => 'student',
                        ]);
                    }
                }
            }
        }
        if ($user->role === 'lecturer') {
            $user->enrollments()->where('role', 'lecturer')->delete();
            if ($request->has('lecturer_courses')) {
                foreach ($request->lecturer_courses as $courseId) {
                    if (!Enrollment::where('user_id', $user->id)->where('course_id', $courseId)->where('role', 'lecturer')->exists()) {
                        Enrollment::create([
                            'user_id' => $user->id,
                            'course_id' => $courseId,
                            'role' => 'lecturer',
                        ]);
                    }
                }
            }
        }

        $successMessage = $user->name . ' this ' . $user->role . ' updated successfully';
        return redirect()->route('admin.users')->with([
            'success' => $successMessage,
            'success_role' => $user->role,
        ]);
    }

    public function destroyUser(User $user)
    {
        $role = $user->role;
        $name = $user->name;
        $user->enrollments()->delete();
        $user->delete();
        $successMessage = $name . ' this ' . $role . ' deleted successfully';
        return redirect()->route('admin.users')->with([
            'success' => $successMessage,
            'success_role' => $role,
        ]);
    }

    public function viewCourseAssignments(Course $course)
    {
        $assignments = $course->cuActivities()->with('topics')->get();
        return view('admin.view_assignments', compact('course', 'assignments'));
    }

    public function addAssignmentToCourse(Request $request, Course $course)
    {
        if ($request->isMethod('get')) {
            return view('admin.add_assignment', compact('course'));
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
        ]);

        \App\Models\CUActivity::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('admin.assignments.view', $course)->with('success', 'Assignment created successfully!');
    }

    public function selectCourseForAssignment()
    {
        $courses = Course::withCount('cuActivities')->get();
        return view('admin.select_course', compact('courses'));
    }

    public function selectActiviryForAssignment(Course $course)
    {
        $activities = $course->cuActivities()->get();
        return view('admin.select_cuactivity', compact('course', 'activities'));
    }

    public function viewCourseActivities(Course $course)
    {
        $activities = $course->cuActivities()->with('topics')->orderByDesc('created_at')->get();
        $topics = $activities->flatMap(function ($activity) {
            return $activity->topics;
        });
        return view('admin.courseActivities ', compact('course', 'activities','topics'));
    }

    public function viewEditActivity(CUActivity $activity)
    {
        return view('admin.editCUActivity',compact('activity'));
    }

    public function EditActivity(CUActivity $activity,Request $request)
    {
        $formValidation=$request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
        ]);
        $editCU=$activity->update($formValidation);
        if($editCU){
            return redirect()->route('admin.courseActivities', $activity->course_id)->with('success', 'CU Activity updated successfully!');
        }
        return redirect()->back()->with('error', 'Failed to update CU Activity. Please try again.');
    }

    public function destroyActivity(CUActivity $activity)
    {
        $course_id=$activity->course_id;
        $activity->topics()->delete();
        $activity->assignments()->delete();
        $activity->delete();
        return redirect()->route('admin.courseActivities',$course_id)->with('success', 'CU Activity deleted successfully!');
    }

    public function viewActivitiesTopic(CUActivity $activity)
    {
        $topics = $activity->topics;
        return view('admin.activityTopic', compact('activity', 'topics'));
    }

    public function addTopicToActivity(CUActivity $activity, Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:slideshow,document,video',
            'file_path.*' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,pdf,doc,docx,xls,xlsx,txt,ppt,pptx,mp4|max:10240', // 10MB max per file
        ]);
        // dd($request->all());
        $topic = new topic();
        $topic->cu_id = $activity->id;
        // dd($activity);
        $topic->title = $request->title;
        $topic->type = $request->type;

        if ($request->hasFile('file_path')) {
            $filePaths = [];
            foreach ($request->file('file_path') as $file) {
                $path = $file->store('topic_files/' . $activity->id, 'public');
                $filePaths[] = ['path' => $path, 'filename' => $file->getClientOriginalName()];
            }
            $topic->file_path = json_encode($filePaths);
        }

        $topic->save();

        return redirect()->route('admin.viewActivitiesTopic', $activity->id)
            ->with('success', $topic->title . ' added successfully.');
    }

    public function deleteActivityTopic(topic $topic)
    {
        $topic->delete();
        return redirect()->route('admin.viewActivitiesTopic', $topic->cu_id)->with('success', 'Topic deleted successfully!');
    }

    public function downloadTopic(topic $topic)
    {
        $filePaths = json_decode($topic->file_path, true);
        if (is_array($filePaths) && count($filePaths) > 0) {
            $firstFilePath = $filePaths[0]['path'] ?? null;
            $fileName = $filePaths[0]['filename'] ?? null;
            if ($firstFilePath && $fileName) {
                return response()->download(public_path('storage/' . $firstFilePath), $fileName);
            }
        }
        return redirect()->back()->with('error', 'No file found for this topic.');
    }

    public function addCourseActivity(Request $request)
    {
        if ($request->isMethod('get')) {
            $course = Course::findOrFail($request->course_id);
            return view('admin.add_cu_activity', compact('course'));
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
        ]);

        $createdActivity = CUActivity::create([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('admin.courseActivities', $request->course_id)
            ->with('success', $createdActivity->title . ' added successfully.');
    }

    public function deleteAssignmentFromActivity(CUActivity $activity, assignment $assignment)
    {
        $assignment->delete();
        return redirect()->route('admin.activityAssignment.view', $activity->id)->with('success', 'Assignment deleted successfully!');
    }

    public function viewAssignmentTopics(CUActivity $assignment)
    {
        $topics = $assignment->topics;
        return view('admin.assignment_topics', compact('assignment', 'topics'));
    }

    public function addTopic(CUActivity $assignment)
    {
        return view('admin.add_topic', compact('assignment'));
    }

    public function storeTopic(Request $request, CUActivity $assignment)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:slide,document,video',
            'file_path' => 'required',
            'file_path.*' => 'file|mimes:jpg,jpeg,png,webp,gif,pptx,pdf,doc,docx,xls,xlsx,txt,mp4|max:51200',
        ]);

        $filePaths = [];
        if ($request->hasFile('file_path')) {
            foreach ($request->file('file_path') as $file) {
                $path = $file->store('topics', 'public');
                $filePaths[] = str_replace(['\\', '"'], '/', $path);
            }
        }

        topic::create([
            'cu_id' => $assignment->id,
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => json_encode($filePaths),
        ]);
        return redirect()->route('admin.assignments.view', $assignment->course_id)->with('success', 'Topic added successfully!');
    }

    public function viewActivityAssignments(CUActivity $activity)
    {
        $assignments = $activity->assignments()->orderByDesc('created_at')->get();
        return view('admin.activity_assignment', compact('activity', 'assignments'));
    }

    public function addAssignmentToActivity(Request $request, Course $course)
    {
        $request->validate([
            "assignment_name" => 'required|string|max:255',
            "description" => 'nullable|string',
            "due_date" => 'required|date',
            "attachment" => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,mp4|max:51200',
        ]);

        if ($request->has('attachment')) {
            $attachmentPath = $request->file('attachment')->store('assignments', 'public');
            $attachmentPath = str_replace(['\\', '"'], '/', $attachmentPath);
        } else {
            $attachmentPath = null;
        }
        
        $assignment = assignment::create([
            'cu_id' => $request->activity_id,
            'assignment_name' => $request->assignment_name,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'attachment' => $attachmentPath,
        ]);
        
        if ($assignment) {
            return redirect()->route('admin.activityAssignment.view', $assignment->cu_id)
                ->with('success', $assignment->assignment_name . ' added successfully.');
        }
        return redirect()->back()->with('error', 'Failed to add assignment. Please try again.');
    }
    

    public function editTopic(\App\Models\topic $topic)
    {
        $assignment = $topic->cuActivity; // This is CUActivity (activity)
        return view('admin.edit_topic', compact('assignment', 'topic'));
    }

    public function updateTopic(Request $request, CUActivity $assignment, \App\Models\topic $topic)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:slideshow,document,video',
            'file_path.*' => 'nullable|file|mimes:jpg,jpeg,png,pptx,webp,gif,pdf,doc,docx,xls,xlsx,txt,mp4|max:51200',
        ]);

        $data = [
            'title' => $request->title,
            'type' => $request->type,
        ];

        if ($request->hasFile('file_path')) {
            $filePaths = [];
            foreach ($request->file('file_path') as $file) {
                $path = $file->store('topics', 'public');
                $filePaths[] = [
                    'path' => str_replace(['\\', '"'], '/', $path),
                    'filename' => $file->getClientOriginalName(),
                ];
            }
            $data['file_path'] = json_encode($filePaths);
        }

        $topic->update($data);
        return redirect()->route('admin.viewActivitiesTopic', $assignment->id)->with('success', 'Topic updated successfully!');
    }

    public function deleteTopic(\App\Models\topic $topic)
    {
        $assignment = $topic->cuActivity;
        $topic->delete();
        return redirect()->route('admin.viewActivitiesTopic', $assignment->id)->with('success', 'Topic deleted successfully!');
    }

    public function editAssignment(Course $course, assignment $assignment)
    {
        return view('admin.edit_assignment', compact('course', 'assignment'));
    }

    public function updateAssignment(Request $request, Course $course, assignment $assignment)
    {
        $request->validate([
            'assignment_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
        ]);
        $assignment->update($request->only(['assignment_name', 'description', 'due_date']));
        return redirect()->route('admin.activityAssignment.view', $assignment->cu_id)->with('success', 'Assignment updated successfully!');
    }

    public function deleteAssignment(Course $course, assignment $assignment)
    {
        $assignment->delete();
        return redirect()->route('admin.activityAssignment.view', $assignment->cu_id)->with('success', 'Assignment deleted successfully!');
    }

    public function checkAssignments(assignment $assignment)
    {
        $submissions = assignmentSubmit::where('assignment_id', $assignment->id)->with('assignment')->get();
        return view('admin.checkassignment', compact('assignment','submissions'));
    }

    public function gradeAssignments(assignmentSubmit $assignmentsubmit, Request $request)
    {
        $request->validate([
            'grade' => 'required|integer|min:0|max:100',
        ]);

        $assignmentsubmit->grade = $request->grade;
        $assignmentsubmit->graded_at = now();
        $assignmentsubmit->save();
        return redirect()->route('admin.checkAssignments', $assignmentsubmit->assignment_id)->with('success', 'Assignment graded successfully!');
    }

    public function feedbackAssignments(assignmentSubmit $assignmentsubmit, Request $request)
    {
        $request->validate([
            "feedback" => 'required|max:500',
        ]);

        $assignmentsubmit->feedback = $request->feedback;
        $assignmentsubmit->save();
        return redirect()->route('admin.checkAssignments', $assignmentsubmit->assignment_id)->with('success', 'Assignment graded successfully!');
    }

    public function viewTopicFiles(CUActivity $assignment, \App\Models\topic $topic)
    {
        $files = is_array($topic->file_path) ? $topic->file_path : (json_decode($topic->file_path, true) ?: [$topic->file_path]);
        return view('admin.topic_files', compact('assignment', 'topic', 'files'));
    }

    public function assignmentStatusOverview()
    {
        try {
            // 获取所有CU Activities及其相关数据
            $activities = \App\Models\CUActivity::with(['course', 'assignments.assignmentSubmissions'])
                ->get()
                ->map(function ($activity) {
                    // 计算每个activity的assignment统计
                    $totalAssignments = $activity->assignments->count();
                    $totalSubmissions = 0;
                    
                    // 计算总提交数
                    foreach ($activity->assignments as $assignment) {
                        $totalSubmissions += $assignment->assignmentSubmissions->count();
                    }
                    
                    // 获取已注册的学生数量
                    $enrolledStudents = \App\Models\Enrollment::where('course_id', $activity->course_id)
                        ->where('role', 'student')
                        ->count();
                    
                    // 计算统计信息
                    $totalExpectedSubmissions = $enrolledStudents * $totalAssignments;
                    $submittedCount = $totalSubmissions;
                    $notSubmittedCount = max(0, $totalExpectedSubmissions - $submittedCount);
                    $progressPercentage = $totalExpectedSubmissions > 0 ? 
                        round(($submittedCount / $totalExpectedSubmissions) * 100, 1) : 0;
                    
                    $activity->stats = [
                        'total_assignments' => $totalAssignments,
                        'total_submissions' => $totalSubmissions,
                        'submitted_count' => $submittedCount,
                        'not_submitted_count' => $notSubmittedCount,
                        'enrolled_students' => $enrolledStudents,
                        'total_expected_submissions' => $totalExpectedSubmissions,
                        'progress_percentage' => $progressPercentage
                    ];
                    
                    return $activity;
                });
                
            return view('admin.assignment_status_overview', compact('activities'));
        } catch (\Exception $e) {
            // 如果出错，返回空数据
            return view('admin.assignment_status_overview', ['activities' => collect([])]);
        }
    }

    public function testCheckAssignments()
    {
        try {
            // 最简单的测试版本
            $activities = \App\Models\CUActivity::all();
            
            return response()->json([
                'success' => true,
                'message' => 'Test successful',
                'activities_count' => $activities->count(),
                'activities' => $activities->map(function($activity) {
                    return [
                        'id' => $activity->id,
                        'title' => $activity->title,
                        'course_id' => $activity->course_id
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function showActivityAssignmentStatus(CUActivity $activity)
    {
        try {
            // 获取该Activity的所有Assignments及其提交状态
            $assignments = $activity->assignments()->with(['assignmentSubmissions.user'])->get();
            
            // 获取已注册的学生
            $enrolledStudents = \App\Models\Enrollment::where('course_id', $activity->course_id)
                ->where('role', 'student')
                ->with('user')
                ->get();
            
            // 为每个Assignment计算详细统计
            $assignments = $assignments->map(function ($assignment) use ($enrolledStudents) {
                $submissions = $assignment->assignmentSubmissions;
                $submittedStudents = $submissions->pluck('user_id')->toArray();
                
                $assignment->stats = [
                    'total_submissions' => $submissions->count(),
                    'enrolled_students' => $enrolledStudents->count(),
                    'submitted_count' => $submissions->count(),
                    'not_submitted_count' => max(0, $enrolledStudents->count() - $submissions->count()),
                    'progress_percentage' => $enrolledStudents->count() > 0 ? 
                        round(($submissions->count() / $enrolledStudents->count()) * 100, 1) : 0
                ];
                
                // 标记哪些学生已提交，哪些未提交
                $assignment->studentStatus = $enrolledStudents->map(function ($student) use ($submittedStudents, $assignment) {
                    return [
                        'student' => $student->user,
                        'has_submitted' => in_array($student->user_id, $submittedStudents),
                        'submission' => $assignment->assignmentSubmissions()
                            ->where('user_id', $student->user_id)
                            ->first()
                    ];
                });
                
                return $assignment;
            });
            
            return view('admin.activity_assignment_status', compact('activity', 'assignments', 'enrolledStudents'));
        } catch (\Exception $e) {
            // 如果出错，返回错误页面
            return back()->with('error', 'Error loading assignment status: ' . $e->getMessage());
        }
    }

    public function getAssignmentStats()
    {
        $stats = [
            'total_activities' => \App\Models\CUActivity::count(),
            'total_assignments' => \App\Models\assignment::count(),
            'total_submissions' => \App\Models\assignmentSubmit::count(),
            'total_courses' => \App\Models\Course::count(),
            'total_students' => \App\Models\Enrollment::where('role', 'student')->count(),
        ];
        
        return response()->json($stats);
    }
}