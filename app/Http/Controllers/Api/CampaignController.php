<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{

    public function index()
    {
        $campaigns = Campaign::offset(0)->limit(10)->get();

        return $campaigns;
    }

    public function count()
    {
        $campaigns = Campaign::all()->count();

        return $campaigns;
    }


    public function store(Request $request)
    {
        $campaign = new Campaign();

        $campaign->user_id = $request->user_id;
        $campaign->name = $request->name;
        $campaign->description = $request->description;
        $campaign->date_start = $request->date_start;
        $campaign->date_end = $request->date_end;

        $campaign->save();

        return $campaign->id;

    }


    public function show($id)
    {
        $campaign = Campaign::find($id);

        return $campaign;
    }


    public function update(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($request->id);

        $campaign->user_id = $request->user_id;
        $campaign->name = $request->name;
        $campaign->description = $request->description;
        $campaign->date_start = $request->date_start;
        $campaign->date_end = $request->date_end;

        $campaign->save();

        return $campaign->id;

    }


    public function destroy($id)
    {
        $campaign = Campaign::destroy($id);

        return $id;
    }

    public function active(Request $request) //campañas en activo
    {

        $campaigns =
            DB::select('select * from campaigns where date_end >= ?', [$request->current_date]);

        return $campaigns;
    }


    public function search(Request $request)
    {
        $campaigns = DB::select('select * from campaigns where name LIKE ?', ['%'.$request->keyword.'%']);

        return $campaigns;
    }

    public function paginate(Request $request){
        $campaigns = DB::table('campaigns')->offset($request->offset)->limit($request->limit)->get();

        return $campaigns;
    }
}