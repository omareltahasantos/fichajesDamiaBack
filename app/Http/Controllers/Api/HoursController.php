<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hours;

class HoursController extends Controller
{
   
    public function index()
    {
        $hours = Hours::all();

        return $hours;
    }

   
    public function store(Request $request)
    {
        $hour = new Hours();

        $hour->user_id = $request->user_id;
        $hour->campaign_id = $request->campaign_id;
        $hour->register_start = $request->register_start;
        $hour->register_end = $request->register_end;
        $hour->ubication_start = $request->ubication_start;
        $hour->ubication_end = $request->ubication_end;
        $hour->hours = $request->hours;
        $hour->type = $request->type;
        $hour->validate = $request->validate;

        $hour->save();

    }

   
    public function show($id)
    {
        $hour = Hours::find($id);

        return $hour;
    }

    
    public function update(Request $request, $id)
    {
        $hour = Hours::findOrFail($request->id);

        $hour->user_id = $request->user_id;
        $hour->campaign_id = $request->campaign_id;
        $hour->register_start = $request->register_start;
        $hour->register_end = $request->register_end;
        $hour->ubication_start = $request->ubication_start;
        $hour->ubication_end = $request->ubication_end;
        $hour->hours = $request->hours;
        $hour->type = $request->type;
        $hour->validate = $request->validate;

        $hour->save();

        return $hour;

    }

    
    public function destroy($id)
    {
        $hour = Hours::destroy($id);
        
        return $id;
    }
}
