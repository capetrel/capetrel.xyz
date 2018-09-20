<?php


use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{

    public function run()
    {
        $this->table('users')
            ->insert([
                'username' => 'admin',
                'email' => 'capetrel@yahoo.fr',
                'password' => password_hash('13abo.00', PASSWORD_DEFAULT),
                'first_name' => 'Claude-Alban',
                'last_name' => 'Petrelluzzi',
                'birthday' => '1979-12-01 08:30:00',
                'tel_1' => '06 30 16 33 10',
                'tel_2' => '',
                'driver_licence' => 'permis B',
                'address' => 'Le Petit Vaudanière, 37210 ROCHECORBON',
                'description' => "J'aime le code",
            ])
            ->save();
    }
}
