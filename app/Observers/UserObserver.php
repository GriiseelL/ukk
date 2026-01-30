<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function updated(User $user)
    {
        // ❌ HANYA USER (BUKAN ADMIN)
        if ($user->role !== 'user') {
            return;
        }

        // Ganti avatar
        if ($user->wasChanged('avatar')) {
            activity_log(
                'ganti avatar',
                'User mengganti foto profil'
            );
        }

        // Ganti cover / banner
        if ($user->wasChanged('cover')) {
            activity_log(
                'ganti cover',
                'User mengganti cover profil'
            );
        }

        // Ganti username
        if ($user->wasChanged('username')) {
            activity_log(
                'ganti username',
                'Username diubah dari "' .
                $user->getOriginal('username') .
                '" ke "' .
                $user->username . '"'
            );
        }

        // Ganti nama
        if ($user->wasChanged('name')) {
            activity_log(
                'ganti nama',
                'Nama diubah dari "' .
                $user->getOriginal('name') .
                '" ke "' .
                $user->name . '"'
            );
        }

        // ❌ password TIDAK dicatat (AMAN)
    }
}
