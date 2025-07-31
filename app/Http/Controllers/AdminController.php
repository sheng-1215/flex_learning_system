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
        $courses = Course::with(['enrollments.user'])->get();
        return view('admin.courses', ['courses' => $courses]);
    }

    public function importStudent($id,Request $request)
    {
        $student = DB::connection('second_db')->table('student')->where('id', $id)->first();
        if (!$student->s_email || !$student->ic) {
            return redirect()->back()->with('error', 'Student email not found.');
        }
        // Check if the student already exists in the main database
        $existingUser = User::where('email', $student->s_email)->first();
        if ($existingUser) {
            return redirect()->back()->with('error', 'Student already exists in the system.');
        }
        // Create a new user in the main database
        $student_login= DB::connection('second_db')->table('student_login')->where('ic', $student->ic)->first();
        if($student_login && Hash::check($request->password, $student_login->password)){
            $user = User::create([
                'name' => $student->s_name,
                'email' => $student->s_email,
                'password' => Hash::make($request->password),
                'role' => 'student',
            ]);
            return redirect()->route('admin.users')->with('success', 'Student imported successfully.');
        }
        
        return redirect()->back()->with('error', 'Invalid password. Please try again.');
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

        Course::create([
            'title' => $request->title,
            'cover_image' => $coverPath,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.courses')->with('success', 'Course added successfully.');
    }

    public function editCourse(Course $course)
    {
        return view('admin.edit_course', compact('course'));
    }

    public function updateCourse(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $course->update($request->only(['title', 'start_date', 'end_date']));

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

        return redirect()->route('admin.courses')->with('success', 'Course updated successfully.');
    }
    

    public function destroyCourse(Course $course)
    {
        $course->enrollments()->delete();
        $course->activities()->delete();
        $course->delete();

        return redirect()->route('admin.courses')->with('success', 'Course deleted successfully.');
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
        return view('admin.addUserToCourse', compact('course', 'users', 'enrollments'));
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
            return redirect()->route('admin.addUserToCourse', $course->id)->with('success', 'User enrolled successfully.');
        }

        return back()->with("error", "User added failed, please try again later");
    }

    public function removeUserFromCourse(Enrollment $enrollment)
    {
        $enrollment->delete();
        return redirect()->route('admin.addUserToCourse')->with('success', 'User removed successfully.');
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

        return redirect()->route('admin.editUser', $user)->with('success', 'Account registered successfully!');
    }

    public function users()
    {
        $admins = User::where('role', 'admin')->get();
        $lecturers = User::where('role', 'lecturer')
            ->get();
        
        $students = User::where('role', 'student')
            ->with('enrollments.course')
            ->paginate(10, ['*'], 'students_page');
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

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user)
    {
        $user->enrollments()->delete();
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
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
        $activities = $course->cuActivities()->with('topics')->get();
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

    public function addTopicToActivity(Request $request)
    {
        if ($request->isMethod('get')) {
            $activity = CUActivity::findOrFail($request->cu_id);
            return view('admin.add_topic_to_activity', compact('activity'));
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:slideshow,document,video',
            'file_path' => 'required',
            'file_path.*' => 'file|mimes:jpg,jpeg,png,webp,gif,pdf,doc,docx,xls,xlsx,txt,mp4|max:51200',
        ]);
       
        $filePaths = [];
        if ($request->hasFile('file_path')) {
            foreach ($request->file('file_path') as $file) {
                $path = $file->store('topics', 'public');
                $filePaths[] = [
                    'path' => str_replace(['\\', '"'], '/', $path),
                    'filename' => $file->getClientOriginalName(),
                ];
            }
        }
        $filePaths = json_encode($filePaths);

        topic::create([
            'cu_id' => $request->cu_id,
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => $filePaths,
        ]);
        return redirect()->route('admin.viewActivitiesTopic', $request->cu_id)->with('success', 'Topic added successfully!');
    }

    public function deleteActivityTopic(topic $topic)
    {
        $topic->delete();
        return redirect()->route('admin.viewActivitiesTopic', $topic->cu_id)->with('success', 'Topic deleted successfully!');
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

        CUActivity::create([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('admin.courseActivities', $request->course_id)->with('success', 'CU Activity created successfully!');
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
            'file_path.*' => 'file|mimes:jpg,jpeg,png,webp,gif,pdf,doc,docx,xls,xlsx,txt,mp4|max:51200',
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
        $assignments = $activity->assignments;
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
            return redirect()->route('admin.selectActiviryForAssignment', $course->id)->with('success', 'Assignment added successfully!');
        }
        return redirect()->back()->with('error', 'Failed to add assignment. Please try again.');
    }
    

    public function editTopic(\App\Models\topic $topic)
    {
        $assignment = $topic->cuActivity;
        return view('admin.edit_topic', compact('assignment', 'topic'));
    }

    public function updateTopic(Request $request, \App\Models\topic $topic)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:slide,document,video',
            'file_path.*' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,pdf,doc,docx,xls,xlsx,txt,mp4|max:51200',
        ]);

        $data = [
            'title' => $request->title,
            'type' => $request->type,
        ];
        if ($request->hasFile('file_path')) {
            $filePaths = [];
            foreach ($request->file('file_path') as $file) {
                $path = $file->store('topics', 'public');
                $filePaths[] = str_replace(['\\', '"'], '/', $path);
            }
            $data['file_path'] = $filePaths;
        }
        $topic->update($data);
        $assignment = $topic->cuActivity;
        return redirect()->route('admin.assignments.view', $assignment->course_id)->with('success', 'Topic updated successfully!');
    }

    public function deleteTopic(\App\Models\topic $topic)
    {
        $assignment = $topic->cuActivity;
        $topic->delete();
        return redirect()->route('admin.assignments.view', $assignment->course_id)->with('success', 'Topic deleted successfully!');
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

    public function showCheckAssignments()
    {
        $activities = \App\Models\CUActivity::with('course')->get();
        return view('admin.check_assignments_activities', compact('activities'));
    }
}