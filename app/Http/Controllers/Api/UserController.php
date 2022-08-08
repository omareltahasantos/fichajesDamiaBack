<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
   
    public function index()
    {
        $users = User::all();

        return $users;
    }

   
    public function store(Request $request)
    {
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->hours_contract = $request->hours_contract;
        $user->rol = $request->rol;
        $user->date_start = $request->date_start;

        $user->save();

    }

   
    public function show($id)
    {
        $user = User::find($id);

        return $user;
    }

    
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($request->id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->hours_contract = $request->hours_contract;
        $user->rol = $request->rol;
        $user->date_start = $request->date_start;

        $user->save();

        return $user;

    }

    
    public function destroy($id)
    {
        $user = User::destroy($id);
        
        return $id;
    }

    public function checkUser(Request $request){

       // $user = DB::select('select * from users where email = ? AND password = ?', [$request->email, $request->password]);
        $user = DB::table('users')
            ->where([
                ['email', $request->email],
                ['password', $request->password]
           ])->get();

                  


         return $user;
    }
}
