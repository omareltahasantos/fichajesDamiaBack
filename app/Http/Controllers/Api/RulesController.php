<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Rules;
use Illuminate\Support\Facades\DB;

class RulesController extends Controller
{

    public function index(Request $request)
    {
        $users = Rules::where('userId', $request->userId)->get();

        return $users;

    }

    public function count()
    {

    }

    public function checkIfUserLinkedToCustomer(Request $request)
    {
        $cuser = DB::select('select * from rules where userId = ? and customerId = ?',
                [$request->userId, $request->customerId]);

        return $cuser;
    }



    public function store(Request $request)
    {

        $userId = $request->userId;
        $customerId = $request->customerId;

        $rules = new Rules();
        $rules->userId = $userId;
        $rules->customerId = $customerId;

        $rules->save();

        return $rules->id;


    }


    public function show($id)
    {

    }


    public function update(Request $request, $id)
    {


    }


    public function destroy($id)
    {

        $rules = Rules::destroy($id);

        return $rules;

    }


}