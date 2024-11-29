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

        if($customerId == 0) {
            $users = User::orderBy('estado', 'asc')->offset(0)->limit(10)->get();
            return $users;
        }
        $users = User::join('rules', 'users.id', '=', 'rules.userId')
                ->where('rules.customerId', $customerId) // Asegúrate de usar el nombre correcto de la columna
                ->select('users.*') // Selecciona las columnas de customers
                ->orderBy('users.estado', 'asc')
                ->offset(0)
                ->limit(10)
                ->get();

        return $users;
    }

    public function fetch(Request $request)
    {
        $customerId = $request->customerId;

        if($customerId == 0) {
            $users = User::orderBy('estado', 'asc')->get();
            return $users;
        }

        $users = User::join('rules', 'users.id', '=', 'rules.userId')
        ->where('rules.customerId', $customerId) // Asegúrate de usar el nombre correcto de la columna
        ->where('users.estado', 'Alta')
        ->select('users.*') // Selecciona las columnas de customers
        ->orderBy('users.estado', 'asc')
        ->get();

        return $users;


        //Sacar todos los usuarios que estén vinculados al cliente y que estén en estado alta
    }

    public function count(Request $request)
    {

        $customerId = $request->customerId;

        if($customerId == 0) {
            $users = User::count();
            return $users;

        }
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
        $user->dni = $request->dni;
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
        $user->dni = $request->dni;
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



        $user = User::where('dni', $request->dni)->where('password', $request->password)->first();

        return $user;
    }

    public function contractedHours(Request $request){
        $customerId = $request->customerId;

       if ($customerId == 0) {
            $contracted_hours = User::sum('hours_contract');
            return $contracted_hours;
        }

        $contracted_hours = User::join('rules', 'users.id', '=', 'rules.userId')
        ->where('rules.customerId', $customerId)
        ->sum('users.hours_contract');

        return $contracted_hours;

    }

    public function search(Request $request)
    {
        $customerId = $request->customerId;

        if($customerId == 0) {
            $users = User::where('name', 'like', '%' . $request->keyword . '%')
            ->orWhere('users.dni', 'like', '%' . $request->keyword . '%')
            ->orderBy('estado', 'asc')
            ->distinct('users.dni')
            ->get();
            return $users;
        }
        $users = User::join('rules', 'users.id', '=', 'rules.userId')
        ->where('rules.customerId', $customerId)
        ->where(function($query) use ($request) {
            $query->where('users.name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('users.dni', 'like', '%' . $request->keyword . '%');
        })
        ->groupBy('users.id', 'users.name', 'users.dni') // Group by user-related fields
        ->select('users.*', \DB::raw('MAX(rules.id) as rule_id')) // Aggregate rules.id
        ->orderBy('users.estado', 'asc')
        ->get();

        return $users;
    }

    public function roles(Request $request)
    {
        $roles = DB::table('users')
                ->select('rol')
                ->distinct()
                ->where('rol', '!=', 'CONTROL')
                ->orderBy('rol', 'asc')
                ->get();
        return $roles;
    }

    public function paginate(Request $request){
        $customerId = $request->customerId;

        if($customerId == 0){
            $users = User::orderBy('estado', 'asc')->offset($request->offset)->limit($request->limit)->get();
            return $users;

        }

        $users = User::join('rules', 'users.id', '=', 'rules.userId')
        ->where('rules.customerId', $customerId) // Asegúrate de usar el nombre correcto de la columna
        ->select('users.*') // Selecciona las columnas de customers
        ->orderBy('users.estado', 'asc')
        ->offset($request->offset)
        ->limit($request->limit)
        ->get();

        return $users;
    }
}