<?php

namespace App\Http\Controllers;

use App\Models\assignment;
use App\Models\assignmentSubmit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
                return redirect()->route('index');
            } else if ($user->role === 'admin' || $user->role === 'lecturer') {
                return redirect()->route('admin_dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Unknown user role.'])->onlyInput('email');
            }

            return redirect()->route('student.dashboard'); 

        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register_studentVerify(Request $request)
    {
        // dd($request->ic);
        $authCheck= Http::get("https://registration.synergycollege2u.com/api/student_api.php?ic=$request->ic");
        
        // dd("https://registration.synergycollege2u.com/api/student_api.php?ic=$request->ic");
        if($authCheck->json()['status'] == 200){
            $json=$authCheck->json()['data'];
            return view('register', [
                'name' => $json['name'],
                'email' => $json['email'],
            ]);
        }
        return back()->withErrors([
            'ic' => 'The provided IC does not match our records.',
        ])->onlyInput('ic');
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

        return redirect()->route('index');

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


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('index');
    }
}
