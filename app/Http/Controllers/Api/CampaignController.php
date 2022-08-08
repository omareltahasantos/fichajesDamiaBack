<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;

class CampaignController extends Controller
{
   
    public function index()
    {
        $campaigns = Campaign::all();

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

        return $campaign;

    }

    
    public function destroy($id)
    {
        $campaign = Campaign::destroy($id);
        
        return $id;
    }
}
