<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Location;
use Validator;
use Auth;

class LocationController extends Controller
{
    //Protect controller from unauthorized users
    /*
    public function __construct()
    {
        $this->middleware('auth');
    }
    */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::orderBy('created_at', 'desc')->paginate(9);
        return view('locations.locations')->with('locations', $locations);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $opis = $request->input('opis');
        $geom = $request->input('geom');
        $user = Auth::user();
        $userId = $user->id;
        
        $geom = DB::raw("ST_TRANSFORM(ST_GeomFromGeoJSON('".$geom."'), 4326)");
        
        $statement = "INSERT INTO public.locations(opis, \"user\", created_at, geom) VALUES ('".$opis."', ".$userId.", current_timestamp, ".$geom.");";
                
        $query = DB::statement($statement);
        
        return response()->json([
            'Message'=>'Success',
            'Data' => $query,
             ], 200);                      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $localtion = Location::find($id);        
        $location->delete();
        return response()->json([
            'Message' => 'Deleted'
        ], 200);
    }
}