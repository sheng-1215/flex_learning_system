<?php

namespace App\Http\Controllers;

use App\Models\assignment;
use App\Models\Course;
use App\Models\CUActivity;
use Illuminate\Http\Request;


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

        
        
        if(auth()->user()->is_admin){
            return redirect()->route('admin.dashboard');
        }
        if($authstatus->isEmpty()){
            return redirect()->route('index')->with('error', 'You are not enrolled in any courses.');
        }
        return view('student.dashboard',compact('course','activities'));
    }

    public function CUActivity($id)
    {
        $activity = CUActivity::findOrFail($id);
        $topics = $activity->topics;

        if (request()->has('topic') && request()->get('topic') !== "") {
            $selectedTopic = $topics->where('id', request()->get('topic'))->first();
            
            if ($selectedTopic) {
                if($selectedTopic->type!="video"){
                    $selectedTopic->progress = 100; // Assuming 100% progress for the selected topic
                }
                $selectedTopic->save();
                return view('student.CUActivity_detail', compact('activity', 'topics', 'selectedTopic'));
            }
        }
        return view('student.CUActivity_detail', compact('activity', 'topics'));
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
        if (auth()->check()) {
            // 如果已登录，自动跳转到dashboard，防止死循环
            if (auth()->user()->role === 'student') {
                return redirect()->route('student.dashboard');
            } else if (auth()->user()->role === 'admin' || auth()->user()->role === 'lecturer') {
                return redirect()->route('admin_dashboard');
            }
        }
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
