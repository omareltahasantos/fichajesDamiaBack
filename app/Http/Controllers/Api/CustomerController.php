<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;



class CustomerController extends Controller
{

    public function index()
    {
        $customers = Customer::orderBy('name', 'desc')->offset(0)->limit(10)->get();

        return $customers;
    }

    public function count()
    {
        $customers = Customer::all()->count();

        return $customers;
    }


    public function store(Request $request)
    {
        $customer = new Customer();

        $customer->name = $request->name;

        $customer->save();

        return $customer->id;

    }


    public function show($id)
    {
        $customer = Customer::find($id);

        return $customer;
    }


    public function update(Request $request, $id)
    {
        $customers = Customer::findOrFail($id);

        $customers->name = $request->name;

        $customers->save();

        return $customers;

    }


    public function destroy($id)
    {
        $customer = Customer::destroy($id);

        return $id;
    }


    public function search(Request $request)
    {


        $customers = DB::select('select * from customers where name LIKE ? order by customers.name asc', ['%'.$request->keyword.'%']);

        return $customers;
    }

    public function paginate(Request $request){
        $customers = DB::table('customers')->orderBy('name')->offset($request->offset)->limit($request->limit)->get();

        return $customers;
    }
}