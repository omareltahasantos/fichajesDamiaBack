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
        $users = User::orderBy('name', 'asc')->offset(0)->limit(10)->get();

        return $users;
    }

    public function fetch()
    {
        $users = User::where('estado', 'Alta')->orderBy('name', 'asc')->get();

        return $users;
    }

    public function count()
    {
        $users = User::all()->count();

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
        $user->estado = 'Alta';

        $user->save();

        return $user->id;

    }


    public function updateState(Request $request, $id)
    {

        $user = User::findOrFail($id);


        $user->estado = $request->state;

        $user->save();

        return $user->id;

    }


    public function show($id)
    {
        $user = User::find($id);

        return $user;
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

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

       //$user = DB::select('select * from users where email = ? AND password = ?', [$request->email, $request->password]);
        $user = DB::table('users')
            ->where([
                ['email', $request->email],
                ['password', $request->password]
           ])->get();

        return $user;
    }

    public function contractedHours(Request $request){
        $contracted_hours = DB::select('select SUM(hours_contract) as contracted_hours from users');

        return $contracted_hours;
    }

    public function search(Request $request)
    {
        $users = DB::select('select * from users where name LIKE ? order by users.name asc', ['%'.$request->keyword.'%']);

        return $users;
    }

    public function roles(Request $request)
    {
        $roles = DB::select('select DISTINCT(rol) as rol from users order by rol ');

        return $roles;
    }

    public function paginate(Request $request){
        $users = DB::table('users')->orderBy('name')->offset($request->offset)->limit($request->limit)->get();

        return $users;
    }
}