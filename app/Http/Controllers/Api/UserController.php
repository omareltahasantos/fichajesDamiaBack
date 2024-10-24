<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rules;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $customerId = $request->customerId;
        $users = User::join('rules', 'users.id', '=', 'rules.userId')
                ->where('rules.customerId', $customerId) // Asegúrate de usar el nombre correcto de la columna
                ->select('users.*') // Selecciona las columnas de customers
                ->orderBy('users.name', 'asc')
                ->offset(0)
                ->limit(1)
                ->get();

        return $users;
    }

    public function fetch()
    {
        $users = User::where('estado', 'Alta')->orderBy('name', 'asc')->get();

        return $users;
    }

    public function count(Request $request)
    {

        $customerId = $request->customerId;
        $users = User::join('rules', 'users.id', '=', 'rules.userId')
                ->where('rules.customerId', $customerId) // Asegúrate de usar el nombre correcto de la columna
                ->select('users.*') // Selecciona las columnas de customers
                ->count();

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

        $user = DB::table('users')
            ->where([
                ['email', $request->email],
                ['password', $request->password]
           ])->get();

        return $user;
    }

    public function contractedHours(Request $request){
        $customerId = $request->customerId;

        $contracted_hours = User::join('rules', 'users.id', '=', 'rules.userId')
                ->where('rules.customerId', $customerId)
                ->sum('users.hours_contract');

        return $contracted_hours;

    }

    public function search(Request $request)
    {
        $customerId = $request->customerId;
        $users = User::join('rules', 'users.id', '=', 'rules.userId')
                ->where('rules.customerId', $customerId)
                ->where('users.name', 'like', '%' . $request->keyword . '%')
                ->orderBy('users.name', 'asc')
                ->get();

        return $users;
    }

    public function roles(Request $request)
    {
        $roles = DB::select('select DISTINCT(rol) as rol from users order by rol ');

        return $roles;
    }

    public function paginate(Request $request){
        $customerId = $request->customerId;
        $users = DB::table('users')->orderBy('name')->offset($request->offset)->limit($request->limit)->get();

        $users = User::join('rules', 'users.id', '=', 'rules.userId')
                ->where('rules.customerId', $customerId) // Asegúrate de usar el nombre correcto de la columna
                ->select('users.*') // Selecciona las columnas de customers
                ->orderBy('users.name', 'asc')
                ->offset($request->offset)
                ->limit($request->limit)
                ->get();

        return $users;
    }
}