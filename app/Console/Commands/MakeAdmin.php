<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:make-admin {email}')]
#[Description('Convierte en administrador al usuario con el correo indicado.')]
class MakeAdmin extends Command
{
    public function handle(): int
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->warn("No existe un usuario con el correo {$email}. Regístrate primero en la app.");
            return self::FAILURE;
        }

        $user->role = 'admin';
        $user->save();

        $this->info("✅ {$user->name} ({$email}) ahora es administrador.");

        return self::SUCCESS;
    }
}
