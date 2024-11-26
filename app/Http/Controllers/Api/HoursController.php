<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hours;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class HoursController extends Controller
{

    public function index()
    {
        $hours = DB::table('hours')
                ->join('users', 'users.id', '=', 'hours.user_id')
                ->join('campaigns', 'campaigns.id', '=', 'hours.campaign_id')
                ->select('users.name as user', 'campaigns.name as campaign', 'hours.*')
                ->orderBy('hours.register_start', 'desc')
                ->get();

        return $hours;
    }

    public function count(Request $request)
    {

        $customerId = $request->customerId;

        /**
         * Hay que sacar todas las imputaciones de horas de los usuarios vinculados a un cliente
         */

        $hours = Hours::join('rules', 'rules.userId', '=', 'hours.user_id')
                ->join('users', 'users.id', '=', 'hours.user_id')
                ->join('campaigns', 'campaigns.id', '=', 'hours.campaign_id')
                ->where('rules.customerId', '=', $customerId)
                ->where('hours.validate', 'Si')
                ->select('users.name as user', 'campaigns.name as campaign', 'hours.*')
                ->count();

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
        $hour->validate = $request->state;
        $hour->validate_by = $request->validateBy;

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
        $customerId = $request->customerId;

        $hours = Hours::join('rules', 'rules.userId', '=', 'hours.user_id')
                ->where('rules.customerId', '=', $customerId)
                ->where('hours.validate', 'Si')
                ->sum('hours.hours');

        return $hours;
    }


    public function insertedHours(Request $request)
    {

        $from = $request->from;
        $to = $request->to;
        $customerId = $request->customerId;


        $hours = Hours::join('rules', 'rules.userId', '=', 'hours.user_id')
                ->where('rules.customerId', '=', $customerId)
                ->count();

        return $hours;
    }


    public function search(Request $request)
    {
        $filter = $request->filter;
        $firstDate = $request->firstDate;
        $secondDate = $request->secondDate;
        $customerId = $request->customerId;

        if($firstDate !== NULL && $secondDate !== NULL) {
            $firstDate = Carbon::parse($request->firstDate);
            $secondDate = Carbon::parse($request->secondDate);
        }



        if ($firstDate === NULL || $secondDate === NULL){
            if($filter === 'todos') {

                $hours = Hours::join('rules', 'rules.userId', '=', 'hours.user_id')
                    ->join('users', 'users.id', '=', 'hours.user_id')
                    ->join('campaigns', 'campaigns.id', '=', 'hours.campaign_id')
                    ->select('users.name as user', 'campaigns.name as campaign', 'hours.*')
                    ->where('rules.customerId', '=', $customerId)
                    ->where('campaigns.customerId', '=', $customerId)
                    ->where(function ($query) use ($request) {
                        $query->where('users.name', 'like', '%'.$request->keyword.'%')
                              ->orWhere('campaigns.name', 'like', '%'.$request->keyword.'%');
                    })->orderby('hours.register_start', 'desc')->get();

                return $hours;
            }

                $hours = Hours::join('rules', 'rules.userId', '=', 'hours.user_id')
                ->join('users', 'users.id', '=', 'hours.user_id')
                ->join('campaigns', 'campaigns.id', '=', 'hours.campaign_id')
                ->select('users.name as user', 'campaigns.name as campaign', 'hours.*')
                ->where('rules.customerId', '=', $customerId)
                ->where('campaigns.customerId', '=', $customerId)
                ->where('hours.validate', '=', $filter)
                ->where(function ($query) use ($request) {
                    $query->where('users.name', 'like', '%'.$request->keyword.'%')
                        ->orWhere('campaigns.name', 'like', '%'.$request->keyword.'%');
                })->orderby('hours.register_start', 'desc')->get();

            return $hours;
        }




        if($filter === 'todos') {
            $hours = Hours::join('rules', 'rules.userId', '=', 'hours.user_id')
                    ->join('users', 'users.id', '=', 'hours.user_id')
                    ->join('campaigns', 'campaigns.id', '=', 'hours.campaign_id')
                    ->select('users.name as user', 'campaigns.name as campaign', 'hours.*')
                    ->where('rules.customerId', '=', $customerId)
                    ->where('campaigns.customerId', '=', $customerId)
                    ->whereBetween('hours.register_start', [$firstDate->startOfDay(), $secondDate->endOfDay()])
                    ->where(function ($query) use ($request) {
                        $query->where('users.name', 'like', '%'.$request->keyword.'%')
                              ->orWhere('campaigns.name', 'like', '%'.$request->keyword.'%');
                    })->orderby('hours.register_start', 'desc')->get();
            return $hours;
        }

        $hours = Hours::join('rules', 'rules.userId', '=', 'hours.user_id')
            ->join('users', 'users.id', '=', 'hours.user_id')
            ->join('campaigns', 'campaigns.id', '=', 'hours.campaign_id')
            ->select('users.name as user', 'campaigns.name as campaign', 'hours.*')
            ->where('rules.customerId', '=', $customerId)
            ->where('campaigns.customerId', '=', $customerId)
            ->where('hours.validate', '=', $filter)
            ->whereBetween('hours.register_start', [$firstDate->startOfDay(), $secondDate->endOfDay()])
            ->where(function ($query) use ($request) {
                $query->where('users.name', 'like', '%'.$request->keyword.'%')
                    ->orWhere('campaigns.name', 'like', '%'.$request->keyword.'%');
            })->orderby('hours.register_start', 'desc')->get();

        return $hours;

    }



    public function searchByValidate(Request $request){

        $keyword = null;

        if($request->keyword !== null) {
            $keyword = $request->keyword;
        }



        if($keyword === 'todos') {
            $hour = DB::table('hours')
            ->join('users', 'users.id', '=', 'hours.user_id')
            ->join('campaigns', 'campaigns.id', '=', 'hours.campaign_id')
            ->select('users.name as user', 'campaigns.name as campaign', 'hours.*')->get();

        }else{
            $hour = DB::table('hours')
            ->join('users', 'users.id', '=', 'hours.user_id')
            ->join('campaigns', 'campaigns.id', '=', 'hours.campaign_id')
            ->select('users.name as user', 'campaigns.name as campaign', 'hours.*')
            ->where('hours.validate', '=', $keyword)->get();

        }

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