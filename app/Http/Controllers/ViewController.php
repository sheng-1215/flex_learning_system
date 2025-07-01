<?php

namespace App\Http\Controllers;

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
                return view('student.CUActivity_detail', compact('activity', 'topics', 'selectedTopic'));
            }
        }
        return view('student.CUActivity_detail', compact('activity', 'topics'));
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

}
