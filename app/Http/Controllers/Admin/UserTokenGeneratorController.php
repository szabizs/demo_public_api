<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class UserTokenGeneratorController extends Controller
{
	public function __invoke(User $user)
	{
        try {
            $user->tokens()->delete();
            $token = $user->createToken($user->email);
            $user->update([
                'api_token' => $token->plainTextToken
            ]);
        } catch(\Exception $e) {
            dd($e->getMessage());
        }

        return Redirect::route('admin.users.show', $user)->with(['success', 'Token has been created: '.$token->plainTextToken]);
	}
}
