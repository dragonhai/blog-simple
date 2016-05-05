<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 50)->create()->each(function($user) {
            // $password = 'secretPassword';
            // $fillable = $user->getFillable();
            // $hidden = $user->getHidden();
            // $user->setVisible(array_keys($user->getAttributes()));
            // $user->password = bcrypt($password);//\Hash::make($password);
            // $user->remember_token = str_random(100);
            // $user->fillable($fillable);
            // $user->setHidden($hidden);
            $user->save();
        });
    }
}
