<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        if($request->hasFile('file')){
            $this->validate($request, [
                'opis' => 'required',
                'geom' => 'required',
                'name' => 'required',
                'file' => 'image|nullable|max:5999'
            ]);
        } else {
            $this->validate($request, [
                'opis' => 'required',
                'geom' => 'required',
                'name' => 'required'
            ]);
        }

        

        // Handle File Upload
        if($request->hasFile('file')){
            // Get filename with the extension
            $filenameWithExt = $request->file('file')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('file')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('file')->storeAs('public/photos', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        $opis = $request->input('opis');
        $geom = $request->input('geom');
        $ime = $request->input('name');
        $seedlings = $request->input('seedlings');
        if(empty($seedlings)) {
            $seedlings = 0;
        }
        $user = Auth::user();
        $userId = $user->id;
        $photo = $fileNameToStore;
        
        // Check if user is verified
        $userVerify = $user->verified;
        if ($userVerify === false) {
            return response()->json([
                'Message'=>'User not verified.',
                 ], 500);    
        }

        
        $geom = DB::raw("ST_TRANSFORM(ST_GeomFromGeoJSON('".$geom."'), 4326)");
        
        $statement = "INSERT INTO public.locations(opis, \"user\", created_at, geom, ime, photo, seedlings) VALUES ('".$opis."', ".$userId.", current_timestamp, ".$geom.", '".$ime."', '".$photo."', ".$seedlings.");";
                
        $query = DB::statement($statement);
        
        return response()->json([
            'Message'=>'Success',
            'Data' => $query,
             ], 200);                      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $location = Location::find($id);

        return response()->json([
            'name' => $location->ime,
            'opis' => $location->opis,
            'seedlings' => $location->seedlings
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'opis' => 'required',
            'name' => 'required'
        ]);

        $opis = $request->input('opis');
        $ime = $request->input('name');
        $seedlings = $request->input('seedlings');
        if(empty($seedlings)) {
            $seedlings = 0;
        }
        $user = Auth::user();
        $userId = $user->id;
        
        // Check if user is verified
        $userVerify = $user->verified;
        if ($userVerify === false) {
            return response()->json([
                'Message'=>'User not verified.',
                 ], 500);    
        }

        $location = Location::find($id);
        $location->ime = $ime;
        $location->opis = $opis;
        $location->seedlings = $seedlings;

        if ($location->user === $userId) {
            $location->save();

            return response()->json([
                'Message' => 'Updated'
            ], 200);
        } else {
            return response()->json([
                'Message' => 'Failed to update - user is not owner'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $location = Location::find($id);
        $user = Auth::user();
        $userId = $user->id;

        if ($location->user === $userId) {
            $location->delete();

            if($location->photo != 'noimage.jpg') {
                //Delete image
                Storage::delete('public/photos/'.$location->photo);
            }

            return response()->json([
                'Message' => 'Deleted'
            ], 200);
        } else {
            return response()->json([
                'Message' => 'Failed to delete - user is not owner'
            ], 500);
        }      
        
    }
}