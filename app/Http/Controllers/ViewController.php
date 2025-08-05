<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\assignment;
use App\Models\CUActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ViewController extends Controller
{
    public function index()
    {
        return view('index');
    }
    public function dashboard()
    {
        $authstatus= auth()->user()->enrollments;
        $course = $authstatus->map(function ($enrollment) {
            return $enrollment->course;
        });
        
        $activities=$course->flatMap(function ($course) {
            return $course->activities;
        });
        
        $studentCount= $course->map(function ($course) {
            return $course->enrollments->count();
        });
        
        if(auth()->user()->is_admin){
            return redirect()->route('admin.dashboard');
        }
        if($authstatus->isEmpty()){
            return redirect()->route('login')->with('error', 'You are not enrolled in any courses.');
        }
        return view('student.dashboard',compact('course','activities','studentCount'));
    }

    public function CUActivity($id)
    {
        $activity = CUActivity::findOrFail($id);
        $topics = $activity->topics;

        if (request()->has('topic') && request()->get('topic') !== "") {
            $selectedTopic = $topics->where('id', request()->get('topic'))->first();
            
            if ($selectedTopic) {
                if($selectedTopic->type!="video"){
                    $selectedTopic->progress = 100;
                }
                $selectedTopic->save();
                return view('student.CUActivity_detail', compact('activity', 'topics', 'selectedTopic'));
            }
        }
        return view('student.CUActivity_detail', compact('activity', 'topics'));
    }

    public function verifyStudent(Request $request)
    {
        $student_registration = DB::connection('second_db')->table('student')->where("ic",$request->ic)->first();
        $user=User::where('email', $student_registration->s_email)->first();
        if ($user) {
            Auth::login($user);
            return redirect()->route('student.dashboard')->with('success', 'Student verified and logged in successfully.');
        }
        return redirect()->route('register.verifyForm',$student_registration->id)->with('error', 'Your are not register yet, please register following the instruction.');
    }
    public function studentVerifyForm($id)
    {
        $student_registration=DB::connection('second_db')->table('student')->find($id);
        
        return view('verifyForm',compact('student_registration'));
    }

    public function assignment()
    {   
        $cuActivities = auth()->user()->enrollments->flatMap(function ($enrollment) {
            return $enrollment->course->activities;
        });

        $assignments= $cuActivities->flatMap(function ($activity) {
            return $activity->assignments;
        });
        
        return view('student.assignment',compact('assignments'));
    }
    public function assignmentSubmit($id)
    {
        $assignment = assignment::findOrFail($id);
        $submissions = $assignment->assignmentSubmissions;

        return view('student.assignmentsubmition', compact('assignment', 'submissions'));
    }

    
    public function profile_edit()
    {   
        $user= auth()->user();
        return view('student.profile_edit',compact('user'));
    }
    
    public function profile()
    {
       return view('student.profile');
    }

    public function login()
    {
        return view('login');
    }

    public function register()
    {
        return view('register');
    }
    public function register_studentVerify()
    {
        return view('register_studentVerify');
    }

    public function adminDashboard()
    {
        return view('admin_dashboard');
    }

}
