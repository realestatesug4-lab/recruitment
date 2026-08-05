<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\Users\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $email = config('admin.super_admin_email');
        $password = config('admin.super_admin_password');

        if (! $email || ! $password) {
            return;
        }

        $user = User::withTrashed()->where('email', $email)->first();

        if (! $user) {
            $user = User::create([
                'name' => 'Super Admin',
                'email' => $email,
                'password' => Hash::make($password),
            ]);
        } else {
            $user->restore();
            $user->update(['password' => Hash::make($password)]);
        }

        // ensure roles exist
        if (! Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin']);
        }

        $user->assignRole('admin');
    }
}
