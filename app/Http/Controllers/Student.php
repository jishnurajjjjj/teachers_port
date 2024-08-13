<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StudenDtetails;


class Student extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('name', 'password');

        if (Auth::attempt($credentials)) {
            $userId = Auth::id(); 
            $request->session()->put('user_id', $userId);
            $userName = Auth::user()->name;
            $request->session()->put('user_name', $userName);
            return redirect()->intended('/dashbord'); 
        }

       
        return back()->withErrors([
            'name' => 'The provided credentials do not match our records.',
        ]);
    }

    
    public function index()
    {
       
       
    // dd($studentDetails);
    $users = User::with('studentDetails')->where('user_type', 0)->get();
   // dd($users);
        return view('pages.index',compact('users'));
    }
    public function addStudent(Request $request)
    {
        $t_id=session('user_id');
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'mark' => 'required|integer',
        ]);

       $checkUser=User::where('name',$request->name)->first();
       if(!empty($checkUser))
       {
        $studenDtetails = StudenDtetails::updateOrCreate(
            [
                'user_id' => $checkUser->id,
                'subject' => $request->subject
            ], 
            [
                'mark' => $request->mark,
                'updated_by' =>$t_id,
                'is_delete' => 0,
            ] 
        );

       }
       else
       {
        $newUser = User::create([
            'name' => $request->name,
        ]);

        $studenDtetails = StudenDtetails::create([
            'user_id' => $newUser->id,
            'subject' => $request->subject,
            'mark' => $request->mark,
            'updated_by' => $t_id
        ]);

       }
       $users = User::with('studentDetails')->where('user_type', 0)->get();
        
       return response()->json($users);
    }

    public function editStudent($id)
    {
        $eusers = StudenDtetails::with('user')->where('id', $id)->where('is_delete', 0)->first();
        return response()->json($eusers);
    }
    public function deleteStudent($id)
    {
        $t_id=session('user_id');
        $delete=StudenDtetails::where('id',$id)->update(['is_delete'=>1,'updated_by'=>$t_id]);
        if($delete)
        {
            $users = User::with('studentDetails')->where('user_type', 0)->get();
        
            return response()->json([
                'success' => true,
                'users' => $users
            ]);
        }
        else
        {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the item.'
            ]);
        }
    }
    public function search(Request $request)
    {
        $search = $request->input('query');
        $users = User::with('studentDetails')
        ->where('user_type', 0)
        ->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhereHas('studentDetails', function ($query) use ($search) {
                      $query->where('subject', 'like', "%{$search}%")
                            ->orWhere('mark', 'like', "%{$search}%");
                  });
        })
        ->get();
        return response()->json($users);
    }


    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->flush();
    
        return redirect('/'); 
    }
}
