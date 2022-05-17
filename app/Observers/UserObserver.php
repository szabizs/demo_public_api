<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function creating(User $user)
    {
        // We have to register in a service provider
        $user->user_id = auth()->id();
    }
}
