<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use App\Models\CampaignsUsers;

class CampaignUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cusers = CampaignsUsers::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        $cuser = new CampaignsUsers();

        $cuser->user_id = $request->user_id;
        $cuser->campaign_id = $request->campaign_id;


        $cuser->save();

        return $cuser->id;

        
    }

    public function checkIfUserCampaignExists(Request $request)
    {
        $cuser = DB::select('select * from campaigns_users where campaign_id = ? and user_id = ?',
                [$request->campaign_id, $request->user_id]);

        return $cuser;
    }

    public function participatingUsers(Request $request){

        //$user = DB::select('select * from users where email = ? AND password = ?', [$request->email, $request->password]);
         $cusers = DB::table('campaigns_users')
             ->where([
                 ['campaign_id', $request->campaign_id]
            ])->get();
            
         return $cusers;
     }

}
