<?php

use Phinx\Seed\AbstractSeed;

class ProverbSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [];
        $faker = \Faker\Factory::create('fr_FR');
        for ($i=0; $i<50; $i++) {
            $data[] = [
                'proverb' => $faker->text(350),
                'proverb_author' => $faker->name,
            ];
        }

        $this->table('proverbs')->insert($data)->save();
    }
}
