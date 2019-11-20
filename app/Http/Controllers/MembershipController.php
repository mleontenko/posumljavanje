<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Validator;
use Auth;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        // Check if user is admin
        $user = Auth::user();
        $userAdmin = $user->admin;
        if ($userAdmin === false) {
            return response()->json([
                'Message'=>'Korisnik nije administrator',
                 ], 500);    
        }

        $members = User::select("*")->orderBy('id', 'asc')->get();
        return view('membership.membership')->with('members', $members);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        // Check if user is admin
        $user = Auth::user();
        $userAdmin = $user->admin;
        if ($userAdmin === false) {
            return response()->json([
                'Message'=>'Korisnik nije administrator',
                 ], 500);    
        }

        $user = User::find($id);
        $user->verified = TRUE;
        $user->save();
        
        return redirect('/membership')->with('success', 'Korisnik verificiran');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
