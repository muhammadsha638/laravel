<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FirstController extends Controller
{
    //
    public function index()
    {
       // $users = User::find(4);
            
        return view('welcome');
    }
    public function user_login()
    {
        return view('auth.login');
    }
    public function user_register()
    {
        return view('auth.register');
    }
    public function user_submit_login()
    {
       if(request('remember')=='on'){ $cookie='true'; } else { $cookie='false'; }
       $input = ['email' => request('email'),'password' => request('pwd')];
        if(auth()->attempt($input,$cookie)){
            return redirect()->route('user.transcribtion.form')->with('message','login successfully');
        }
        else
        {
            return redirect()->route('user.login')->with('message','Invalid details');
        }
    }
    public function save()
    {

        request()->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email'), // Check uniqueness in the 'email' column of the 'users' table
            ],
            // 'pswd' => 'required|string|min:8|confirmed',
            // 'cpswd' =>'required|string|min:8|confirmed',
        ]);

        
        $name= request('name');
        $email= request('email');
        $password= request('pswd');
        
        // User::create([
        //     'name' =>$name,
        //     'email' =>$email,
        //     'password' =>$password,
        // ]);

        //check email exist or not if(exist not insert)else(insert); 
        $input=['name'=>request('name'),'password' => bcrypt(request('pswd'))];
        if(request()->hasFile('image'))
        {
            $extension = request('image')->extension();
            $filename ='user_pic'.time().'.'.$extension;
            request('image')->storeAs('images',$filename);
            $input['image'] = $filename;

        }
       
        $user= User::firstOrCreate([
            'email' =>request('email'),
        ],$input);

        // [
        //     'name' => request('name'),
        //     'password' => bcrypt(request('pswd')),
        //     'image' =>$filename,
        // ]

        return redirect()->route('index')->with('message','user created successfully');
    }
    public function all_user()
    {
        $users = User::all();
        return view('allusers',compact('users'));
    }
    public function user_logout()
    {
        auth()->logout();
        return redirect()->route('index');
    }
}
