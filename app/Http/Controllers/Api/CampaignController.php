<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{

    public function index(Request $request)
    {

        $customerId = $request->customerId;

        $campaigns = Campaign::where('customerId' , $customerId)->where('active', 1)->offset(0)->limit(10)->get();

        return $campaigns;
    }

    public function count(Request $request)
    {
        $customerId = $request->customerId;

        $campaigns = Campaign::where('customerId', '=', $customerId)->where('active', 1)->count();

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
        $campaign->customerId = $request->customerId;

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
        $campaign->customerId = $request->customerId;

        $campaign->save();

        return $campaign->id;

    }

    public function updateActive(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($request->id);

        $campaign->active = $request->active;

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

        $customerId = $request->customerId;
        $currentDate = $request->currentDate;

        $campaigns = Campaign::where('date_end', '>=', $currentDate)
                    ->where('customerId', $customerId)
                    ->where('active', 1)
                    ->count();

        return $campaigns;
    }


    public function search(Request $request)
    {
        $customerId = $request->customerId;
        $campaigns = Campaign::where('name', 'like', '%' . $request->keyword . '%')->where('customerId', $customerId)->where('active', 1)->get();

        return $campaigns;
    }

    public function paginate(Request $request){
        $customerId = $request->customerId;

        $campaigns = Campaign::where('customerId', $customerId)->where('active', 1)->offset($request->offset)->limit($request->limit)->get();

        return $campaigns;
    }
}