<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    public function courses()
    {
        $courses = Course::all();
        return view('admin.courses', ['courses' => $courses]);
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

        $course->update($request->all());

        return redirect()->route('admin.courses')->with('success', 'Course updated successfully.');
    }

    public function destroyCourse(Course $course)
    {
        // Before deleting the course, we must delete related enrollments
        $course->enrollments()->delete();
        $course->delete();

        return redirect()->route('admin.courses')->with('success', 'Course deleted successfully.');
    }

    public function registerStudentView()
    {
        return view('admin.register_student');
    }

    public function registerStudent(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        return redirect()->route('admin.editUser', $user)->with('success', 'Student registered successfully! You can now manage their courses.');
    }

    public function users()
    {
        $students = User::where('role', 'student')
                        ->with('enrollments.course')
                        ->paginate(10, ['*'], 'students_page');
        $lecturers = User::whereIn('role', ['lecturer', 'admin'])
                         ->with('courses')
                         ->paginate(10, ['*'], 'lecturers_page');

        return view('admin.users', compact('students', 'lecturers'));
    }

    public function editUser(User $user)
    {
        $courses = null;
        if ($user->role === 'student') {
            $courses = Course::all();
        }
        return view('admin.edit_user', compact('user', 'courses'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'courses' => 'nullable|array'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if ($user->role === 'student') {
            $user->enrollments()->delete();
            if ($request->has('courses')) {
                foreach ($request->courses as $courseId) {
                    Enrollment::create([
                        'user_id' => $user->id,
                        'course_id' => $courseId,
                        'enrollment_date' => now(),
                    ]);
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
        $assignments = $course->cuActivities()->where('type', 'assignment')->get();
        return view('admin.view_assignments', compact('course', 'assignments'));
    }

    public function addAssignmentToCourse(Course $course)
    {
        return view('admin.add_assignment', compact('course'));
    }

    public function storeAssignmentToCourse(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:slide,document,video',
            'due_date' => 'required|date',
            'file' => 'required|array',
            'file.*' => 'file|mimes:pdf,doc,docx,ppt,pptx,mp4,avi,mov,wmv,jpg,jpeg,png,gif|max:51200',
        ]);

        $filePaths = [];
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $mimeType = $file->getMimeType();
                $path = '';
                if (str_starts_with($mimeType, 'image/')) {
                    $path = $file->store('assignments/images', 'public');
                } elseif (str_starts_with($mimeType, 'video/')) {
                    $path = $file->store('assignments/videos', 'public');
                } elseif (in_array($file->extension(), ['ppt', 'pptx'])) {
                    $path = $file->store('assignments/slides', 'public');
                } else {
                    $path = $file->store('assignments/documents', 'public');
                }
                $filePaths[] = $path;
            }
        }

        \App\Models\CUActivity::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => 'assignment',
            'due_date' => $request->due_date,
            'file_paths' => $filePaths,
        ]);

        return redirect()->route('admin.assignments.view', $course)->with('success', 'Assignment created successfully!');
    }

    public function selectCourseForAssignment()
    {
        $courses = Course::withCount(['cuActivities as assignmentCount' => function ($query) {
            $query->where('type', 'assignment');
        }])->get();
        return view('admin.select_course', compact('courses'));
    }
}
