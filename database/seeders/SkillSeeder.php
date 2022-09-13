<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skills = array(
            'html',
            'css',
            'javascript',
            'php',
            'golang'
        );

        foreach($skills as $skill)
        {
            Skill::firstOrCreate([
                'name' => $skill
            ]);
        }
    }
}
