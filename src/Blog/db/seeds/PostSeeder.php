<?php

use Phinx\Seed\AbstractSeed;

class PostSeeder extends AbstractSeed
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
        //seeding des catégories
        $data = [];
        $faker = \Faker\Factory::create('fr_FR');
        for ($i=0; $i<10; $i++) {
            $data[] = [
                'name' => $faker->catchPhrase,
                'slug' => $faker->slug,
            ];
        }
        $this->table('categories')->insert($data)->save();

        // seeding des articles
        $data = [];
        $faker = \Faker\Factory::create('fr_FR');
        for ($i=0; $i<100; $i++) {
            $date = $faker->unixTime('now');
            $data[] = [
                'name' => $faker->catchPhrase,
                'slug' => $faker->slug,
                'category_id' => rand(1, 10),
                'content' => $faker->text(350),
                'updated_at' => date('Y-m-d H:i:s', $date),
                'created_at' => date('Y-m-d H:i:s', $date),
                'published' => 1
            ];
        }

        $this->table('posts')->insert($data)->save();
    }
}
