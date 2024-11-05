<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;



class CustomerController extends Controller
{

    public function index()
    {
        $customers = Customer::orderBy('name', 'desc')->where('active', 1)->offset(0)->limit(10)->get();

        return $customers;
    }

    public function fetchAllCustomers()
    {
        $customers = Customer::all();

        return $customers;
    }

    public function fetchCustomersLinkedUser($userId)
    {
        $customers = Customer::join('rules', 'customers.id', '=', 'rules.customerId')
        ->where('rules.userId', $userId) // Asegúrate de usar el nombre correcto de la columna
        ->select('customers.*') // Selecciona las columnas de customers
        ->where('active', 1)
        ->get();

        return $customers;
    }

    public function count()
    {
        $customers = Customer::where('active', 1)->count();

        return $customers;
    }


    public function store(Request $request)
    {
        try{
                $customer = new Customer();
                $customer->name = $request->name;
                $customer->code = $request->code;

                $customer->save();

                return response()->json(['message' => 'Customer created successfully'], 201);

        }catch(QueryException $e){
              if ($e->errorInfo[1] == 1062) {  // 1062 es el código de error para "duplicate entry" en MySQL
                return response()->json(['message' => 'The email has already been taken.'], 400);
            }

            return response()->json(['message' => 'Something went wrong'], 500);



        }

    }


    public function show($id)
    {
        $customer = Customer::find($id);

        return $customer;
    }


    public function update(Request $request, $id)
    {

        try{
            $customer = Customer::findOrFail($id);

            $customer->name = $request->name;
            $customer->code = $request->code;


            $customer->save();

            return response()->json(['message' => 'Customer updated successfully'], 201);
        }catch(QueryException $e){
            if ($e->errorInfo[1] == 1062) {  // 1062 es el código de error para "duplicate entry" en MySQL
                return response()->json(['message' => 'The email has already been taken.'], 400);
            }

            return response()->json(['message' => 'Something went wrong'], 500);
        }

        return $customer;

    }


    public function destroy($id)
    {
        $customer = Customer::destroy($id);

        return $id;
    }


    public function updateActive(Request $request, $id)
    {
        $customer = Customer::findOrFail($request->id);

        $customer->active = $request->active;

        $customer->save();

        return $customer->id;

    }


    public function search(Request $request)
    {

        $customers = Customer::where('name', 'like', '%'.$request->keyword.'%')->where('active', 1)->orderBy('name', 'asc')->get();

        return $customers;
    }

    public function paginate(Request $request){

        $customers = Customer::orderBy('name', 'desc')->where('active', 1)->offset($request->offset)->limit($request->limit)->get();

        return $customers;
    }
}