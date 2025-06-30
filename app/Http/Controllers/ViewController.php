<?php

namespace App\Http\Controllers;

use App\Models\Course;
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
        
        if(auth()->user()->is_admin){
            return redirect()->route('admin.dashboard');
        }
        if($authstatus->isEmpty()){
            return redirect()->route('index')->with('error', 'You are not enrolled in any courses.');
        }
        return view('student.dashboard',compact('course'));
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
