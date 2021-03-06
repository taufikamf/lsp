<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function index(){
        $users = Users::all();
        return view('master/user/index', ['users' => $users]);
    }
    public function add(){
        return view('master/user/add');
    }

    public function store(Request $req){
        Users::create([
            'name' => $req->input('name'),
            'email' => $req->input('email'),
            'password' => $req->input('password'),
            'address' => $req->input('address'),
            'phone' => $req->input('phone'),
            'level' => $req->input('level')
        ]);
        return redirect()->route('user.index');
    }

    public function edit($id){
        $users = Users::find($id);
        return view('master/user/edit',['user' => $users]);
    }

    public function update(Request $req, $id){
        $users = Users::find($id);
        $users->name = $req->input('name');
        $users->email = $req->input('email');
        $users->phone = $req->input('phone');
        $users->address = $req->input('address');
        $users->level = $req->input('level');
        $users->password = $req->input('password');
        $users->save();
        return redirect()->route('user.index');
    }
    public function delete($id){
        $users = Users::find($id);
        $users->delete();
        return redirect()->route('user.index');
    }
    public function signin(Request $req){
        $user = Users::where("email" , $req->input('email'))->first();
        $this->validate($req,[
            'email' => 'required|email',
            'password' => 'required|min:8|max:16'
        ]);
        if ($req->password == $user->password){
            Session::put('user',$user);
            return redirect("check");
        } else {
            return view('signin')->with("msg", "Invalid email or password");
        }
    }
    public function regis(Request $request){
        $messages = [
            'required' => ':attribute wajib diisi lengkap',
            'min' => ':attribute minimal :min karakter',
            'max' => ':attribute maximal :max karakter',
        ];
        $this->validate($request,[
            'name' => 'required|min:6|max:30',
            'email' => 'required|email',
            'password' => 'required|min:8|max:16',
            'address' => 'required',
            'phone' => 'required|numeric|min:11',
        ],$messages);
        Users::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'level' => 'user'
        ]);
        return redirect()->route('user.signin');
    }
}
