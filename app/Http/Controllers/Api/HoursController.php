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
        $hour->type = $request->type_hours;
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
        $hours = '';

        $fromDate = Carbon::now()->subDays(30)->startOfDay();
        $toDate = Carbon::now()->endOfDay();

        if($customerId !== null && $customerId !== '' && $customerId !== 'todos') {
            $hours = Hours::join('rules', 'rules.userId', '=', 'hours.user_id')
                  ->where('rules.customerId', '=', $customerId)
                  ->whereBetween('hours.register_start', [$fromDate, $toDate]);
        } else {
            $hours = Hours::whereBetween('register_start', [$fromDate, $toDate]);
        }

        $hours = $hours->where('hours.validate', 'Si')->sum('hours.hours');

        return $hours;
    }


    public function insertedHours(Request $request)
    {

        $from = $request->from;
        $to = $request->to;
        $customerId = $request->customerId;
        $hours = '';

        $fromDate = Carbon::now()->subDays(30)->startOfDay();
        $toDate = Carbon::now()->endOfDay();

        if($customerId !== null && $customerId !== '' && $customerId !== 'todos') {
            $hours = Hours::join('rules', 'rules.userId', '=', 'hours.user_id')
                  ->where('rules.customerId', '=', $customerId)
                  ->whereBetween('hours.register_start', [$fromDate, $toDate]);
        } else {
            $hours = Hours::whereBetween('register_start', [$fromDate, $toDate]);
        }

        $hours = $hours->distinct()->count();

        return $hours;
    }

    public function count(Request $request)
    {

        $filter = $request->filter;
        $firstDate = $request->firstDate ? Carbon::parse($request->firstDate) : null;
        $secondDate = $request->secondDate ? Carbon::parse($request->secondDate) : null;
        $customerId = $request->customerId;
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $keyword = $request->keyword;


        // Base query
        $hours = Hours::query()
            ->join('users', 'users.id', '=', 'hours.user_id')
            ->join('campaigns', 'campaigns.id', '=', 'hours.campaign_id')
            ->select('users.name as user', 'campaigns.name as campaign', 'hours.*');


             // Si el filtro no es "todos", se añade
        if ($filter !== 'todos' && $filter !== 'pendientes') {
            $hours = $hours->where('hours.validate', $filter);
        }

        if($filter === 'pendientes') {
            $hours = $hours->where('hours.validate', '');
        }

        // Si hay fechas, se filtra por rango
        if ($firstDate && $secondDate) {
            $hours->whereBetween('hours.register_start', [$firstDate->startOfDay(), $secondDate->endOfDay()]);
        }

        // Si el cliente no es "todos", se agregan joins y condiciones extra
        if ($customerId !== 'todos') {
            $hours->join('rules', 'rules.userId', '=', 'hours.user_id')
                  ->where('rules.customerId', $customerId)
                  ->where('campaigns.customerId', $customerId);
        }

        // Filtro por nombre de usuario o campaña
        if ($keyword) {
            $hours->where(function ($query) use ($keyword) {
                $query->where('users.name', 'like', '%' . $keyword . '%')
                      ->orWhere('campaigns.name', 'like', '%' . $keyword . '%');
            });
        }

        $hours->orderBy('hours.register_start', 'desc')->distinct();


       return $hours->count();

    }


    public function search(Request $request)
    {
            $filter = $request->filter;
            $firstDate = $request->firstDate ? Carbon::parse($request->firstDate) : null;
            $secondDate = $request->secondDate ? Carbon::parse($request->secondDate) : null;
            $customerId = $request->customerId;
            $offset = $request->offset ?? 0;
            $limit = $request->limit ?? 10;
            $keyword = $request->keyword;


            // Base query
            $hours = Hours::query()
                ->join('users', 'users.id', '=', 'hours.user_id')
                ->join('campaigns', 'campaigns.id', '=', 'hours.campaign_id')
                ->select('users.name as user', 'campaigns.name as campaign', 'hours.*');


                 // Si el filtro no es "todos", se añade
            if ($filter !== 'todos' && $filter !== 'pendientes') {
                $hours = $hours->where('hours.validate', $filter);
            }

            if($filter === 'pendientes') {
                $hours = $hours->where('hours.validate', '');
            }

            // Si hay fechas, se filtra por rango
            if ($firstDate && $secondDate) {
                $hours->whereBetween('hours.register_start', [$firstDate->startOfDay(), $secondDate->endOfDay()]);
            }

            // Si el cliente no es "todos", se agregan joins y condiciones extra
            if ($customerId !== 'todos') {
                $hours->join('rules', 'rules.userId', '=', 'hours.user_id')
                      ->where('rules.customerId', $customerId)
                      ->where('campaigns.customerId', $customerId);
            }

            // Filtro por nombre de usuario o campaña
            if ($keyword) {
                $hours->where(function ($query) use ($keyword) {
                    $query->where('users.name', 'like', '%' . $keyword . '%')
                          ->orWhere('campaigns.name', 'like', '%' . $keyword . '%');
                });
            }

            $hours->orderBy('hours.register_start', 'desc')->distinct();

            // Ejecutar consultas
            return [
                'hours' => (clone $hours)->offset($offset)->limit($limit)->get(),
                'exportTotal' => $hours->get()
            ];

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
                 ->orderby('hours.validate', 'asc')
                 ->orderby('hours.register_end', 'desc')
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
