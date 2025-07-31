<?php

namespace App\Http\Controllers;

use App\Models\assignment;
use App\Models\assignmentSubmit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class FunctionController extends Controller
{
    public function login(Request $request)
    {
        $form = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($form)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role === 'student') {
                return redirect()->route('student.dashboard');
            } else if ($user->role === 'admin' || $user->role === 'lecturer') {
                return redirect()->route('admin_dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Unknown user role.'])->onlyInput('email');
            }

        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register_studentVerify(Request $request)
    {
        
        // $authCheck= Http::get("https://registration.synergycollege2u.com/api/student_api.php?ic=$request->ic");        
        // dd("https://registration.synergycollege2u.com/api/student_api.php?ic=$request->ic");
        $student_registration=DB::connection('second_db')->table('student')->where("ic",$request->ic)->first();

        
        if($student_registration){
            return redirect()->route("register.verifyForm",$student_registration->id);
        }
        return back()->withErrors([
            'ic' => 'The provided IC does not match our records.',
        ])->onlyInput('ic');
    }

    public function verifyForm(Request $request,$id)
    {
        $student=DB::connection('second_db')->table('student')->find($id);
        $student_login=DB::connection('second_db')->table('student_login')->where("student_ic",$student->ic)->first();
        
        
        // dd($student_login->password,$request->password);
        if($student_login && Hash::check($request->password,$student_login->password)){
            $User = User::where('email', $student->s_email)->first();
            if (!$User) {
                $User = User::create([
                    "name" => $student->s_name,
                    "email" => $student->s_email,
                    "password" => Hash::make($request->password),
                    "role" => "student",
                ]);
            }
            
            Auth::login($User);
            $request->session()->regenerate();
            return redirect()->route('student.dashboard');
        }
        return back()->withErrors([
            'password' => 'The provided password does not match our student portal.',
        ])->onlyInput('password');
    }

    public function register(Request $request)
    {
        $form = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:3', 'confirmed'],
            'role' => ['required', 'in:admin,lecturer,student'],
        ]);

        $form['password'] = Hash::make($form['password']);
        $user = User::create($form);
        auth()->login($user);

        if ($user->role === 'admin' || $user->role === 'lecturer') {
            return redirect()->route('admin_dashboard');
        }

        return redirect()->route('dashboard');

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');


    }
    public function assignmentSubmit(Request $request)
    {
        $form = $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:2048'],
        ]);

        $assignment=new assignmentSubmit();
        $assignment->user_id = Auth::id();
        $assignment->assignment_id = $request->id;
        $assignment->attachment = $request->file('file')->store('assignments', 'public');
        $assignment->status= 'submitted';
        $assignment->submitted_at = now();
        $assignment->save();

        return redirect()->route('student.assignment', ['id' => $assignment->id])
                         ->with('success', 'Assignment submitted successfully.');


    }
    public function assignmentDelete($id)
    {
        $assignment = assignmentSubmit::findOrFail($id);
        if ($assignment->user_id !== Auth::id()) {
            return redirect()->back()->withErrors(['error' => 'You do not have permission to delete this assignment.']);
        }

        $assignment->delete();
        return redirect()->route('student.assignment')->with('success', 'Assignment deleted successfully.');
    }

    public function downloadAssignment(Request $request, $id)
    {
        
        $assignment = assignment::findOrFail($id);

        return response()->download(asset($assignment->attachment));
    }


    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
