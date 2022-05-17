<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private string $description = 'Returns a user by the specified ID';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserCreateRequest  $request
     *
     * @return Response
     */
    public function store(UserCreateRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);

            return response()->api(
                is_success: true,
                message: 'User has been successfully created.',
                description: $user->name,
                data: [
                    'user' => $user
                ],
            );
        } catch (\Exception $e) {

            return response()->api(
                is_success: false,
                message: 'User has not been created.',
                description: $e->getMessage(),
            );

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if(!empty($user)) {
            return response()->api(
                is_success: true,
                message: 'User has been found by the provided ID.',
                description: $this->description,
                data: new UserResource($user)
            );
        }

        return response()->api(
            is_success: false,
            message: 'User has not been found, please check your ID.',
            description: $this->description,
        );

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     *
     * @return Response
     */
    public function update(UserCreateRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update($request->all());

            return response()->api(
                is_success: true,
                message: 'User has been updates successfully.',
                description: $user->name.' has been updated from the database.',
                data: [
                    'user' => $user
                ]
            );

        } catch(\Exception $e) {

            return response()->api(
                is_success: false,
                message: 'User could not be updated.',
                description: $e->getMessage(),
            );

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $user = User::where('id',$id)->where('user_id',auth()->id())->firstOrFail();
            return response()->api(
                is_success: true,
                message: 'User has been deleted successfully.',
                description: $user->name.' has been deleted from the database.',
                data: null
            );

        } catch (\Exception $e) {

            return response()->api(
                is_success: false,
                message: 'User could not be deleted.',
                description: $e->getMessage(),
            );

        }

    }
}
