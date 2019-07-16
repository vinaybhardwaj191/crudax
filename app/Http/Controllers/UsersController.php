<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Response;
use App\http\Requests;
use Illuminate\Support\Facades\input;
use Hash;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where( 'type' , 'member' )->orderBy( 'updated_at' , 'DESC' )->paginate(5);
        return Response::json( $users );
        // return view( 'test' ,  compact('users') );
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
        $validations = array(
            'name' => 'required',
            'email' => 'required',
            'password' => 'required | min:8'
        );

        $isValid = Validator::make( input::all() , $validations );

        if( $isValid->fails() )
            return Response::json( array( 'errors' => $isValid->getMessageBag()->toarray() ) );
        else {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make( $request->password );
            $user->save();

            return Response::json( $user );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find( $id );
        return $user;
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
        $user =  User::where( 'id' , $id )->update( [ 'name' => $request->name ] );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find( $id )->delete();
        return Response::json();
    }
}
