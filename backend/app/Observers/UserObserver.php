<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserObserver
{
    /**
     * Handle the User "saving" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function saving(User $user)
    {
        if (request()->password) {
            $user->password = Hash::make($user->password);
        }

        if (request()->new_password) {
            $user->password = Hash::make(request()->new_password);
        }
    }
}
