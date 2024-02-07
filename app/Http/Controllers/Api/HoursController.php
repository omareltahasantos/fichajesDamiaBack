<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hours;
use Illuminate\Support\Facades\DB;


class HoursController extends Controller
{

    public function index()
    {
        $hours = DB::table('hours')
                ->join('users', 'users.id', '=', 'hours.user_id')
                ->join('campaigns', 'campaigns.id', '=', 'hours.campaign_id')
                ->select('users.name as user', 'campaigns.name as campaign', 'hours.*')
                ->orderBy('hours.validate', 'asc')
                ->get();

        return $hours;
    }

    public function count()
    {
        $hours = Hours::all()->count();

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

        if ($hour->save()) {
            return 'record created!';
        }else{
            return 'record failed!';
        }

    }


    public function show($id)
    {
        $hour = Hours::find($id);

        return $hour;
    }


    public function update(Request $request, $id)
    {
        $hour = Hours::findOrFail($request->id);

        //$hour->user_id = $request->user_id;
        //$hour->campaign_id = $request->campaign_id;
        //$hour->register_start = $request->register_start;
        //$hour->register_end = $request->register_end;
        //$hour->ubication_start = $request->ubication_start;
        //$hour->ubication_end = $request->ubication_end;
        //$hour->hours = $request->hours;
        //$hour->type = $request->type;
        $hour->validate = $request->state;

        $hour->save();

        return $hour;

    }

    public function updateWork(Request $request)
    {
        $hour = Hours::findOrFail($request->hour_id);
        $hour->register_end = $request->register_end;
        $hour->ubication_end = $request->ubication_end;
        $hour->hours = $request->hours;

        if ($hour->save()) {
            return 'record updated!';
        }else{
            return 'record failed!';
        }

    }



    public function destroy($id)
    {
        $hour = Hours::destroy($id);

        return $id;
    }



    public function validateHours(Request $request)
    {

        $from = $request->from;
        $to = $request->to;

        $hours = DB::table('hours')
                ->where('validate', 'Si')
                ->whereBetween('register_start', [$from, $to])
                ->get()->count();


        return $hours;
    }


    public function insertedHours(Request $request)
    {

        $from = $request->from;
        $to = $request->to;

        $hours = DB::table('hours')
                ->whereBetween('register_start', [$from, $to])
                ->get()->count();


        return $hours;
    }


    public function search(Request $request)
    {
        $hour = DB::table('hours')
                ->join('users', 'users.id', '=', 'hours.user_id')
                ->join('campaigns', 'campaigns.id', '=', 'hours.campaign_id')
                ->select('users.name as user', 'campaigns.name as campaign', 'hours.*')
                ->where('users.name', 'like', '%'.$request->keyword.'%')
                ->orWhere('campaigns.name', 'like', '%'.$request->keyword.'%')
                ->get();

        return $hour;
    }


    public function searchByValidate(Request $request){

        $keyword = '';

        if($request->keyword !== null) {
            $keyword = $request->keyword;
        }


        $hour = DB::table('hours')
        ->join('users', 'users.id', '=', 'hours.user_id')
        ->join('campaigns', 'campaigns.id', '=', 'hours.campaign_id')
        ->select('users.name as user', 'campaigns.name as campaign', 'hours.*')
        ->where('hours.validate', '=', $keyword)
        ->get();

        return $hour;
    }

    public function hoursByCampaign(Request $request){
        $hours = DB::table('hours')
                 ->join('campaigns', 'campaigns.id', '=', 'hours.campaign_id')
                 ->select('campaigns.name', 'hours.*')
                 ->where('hours.user_id', '=', $request->user_id)
                 ->where('hours.campaign_id', '=', $request->campaign_id)
                 ->orderby('hours.register_end', 'asc')
                ->get();

        return $hours;
    }

    public function paginate(Request $request){

        $hours = DB::table('hours')
        ->join('users', 'users.id', '=', 'hours.user_id')
        ->join('campaigns', 'campaigns.id', '=', 'hours.campaign_id')
        ->select('users.name as user', 'campaigns.name as campaign', 'hours.*')
        ->orderBy('hours.validate', 'asc')
        ->offset($request->offset)
        ->limit($request->limit)
        ->get();

        return $hours;


    }


}