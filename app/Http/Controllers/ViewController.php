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
            return $course->student_count;
        });
        
        if(auth()->user()->is_admin){
            return redirect()->route('admin.dashboard');
        }
        if($authstatus->isEmpty()){
            return redirect()->route('login')->with('error', 'You are not enrolled in any courses.');
        }
        return view('student.dashboard',compact('course','activities','studentCount'));
    }

    public function CUActivity(CUActivity $activity)
    {
        $topics = $activity->topics;
        
        // Load topic progress for the current user
        $userId = auth()->id();
        $topics->each(function ($topic) use ($userId) {
            $topicProgress = \App\Models\TopicProgress::where('user_id', $userId)
                ->where('topic_id', $topic->id)
                ->first();
            
            $topic->progress = $topicProgress ? $topicProgress->progress : 0;
        });
        
        if (request()->has('topic')) {
            $selectedTopic = $topics->where('id', request()->get('topic'))->first();
            
            if ($selectedTopic) {
                // For non-video topics, mark as completed
                if($selectedTopic->type != "video"){
                    $userId = auth()->id();
                    $topicProgress = \App\Models\TopicProgress::firstOrNew([
                        'user_id' => $userId,
                        'topic_id' => $selectedTopic->id,
                    ]);
                    $topicProgress->progress = 100;
                    $topicProgress->last_watched_at = now();
                    $topicProgress->save();
                    
                    // Update the topic progress in the collection
                    $selectedTopic->progress = 100;
                }
                
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
        
        // Ensure each assignment only carries the current student's submissions
        $userId = auth()->id();
        $assignments->each(function ($assignment) use ($userId) {
            $assignment->setRelation(
                'assignmentSubmissions',
                $assignment->assignmentSubmissions()->where('user_id', $userId)->get()
            );
        });
        
        return view('student.assignment',compact('assignments'));
    }
    public function assignmentSubmit($id)
    {
        $assignment = assignment::findOrFail($id);
        // Only show the current student's submissions for this assignment
        $submissions = $assignment->assignmentSubmissions()->where('user_id', auth()->id())->get();

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
