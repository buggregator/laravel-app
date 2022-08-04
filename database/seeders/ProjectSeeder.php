<?php

namespace Database\Seeders;

use Cycle\ORM\EntityManagerInterface;
use Illuminate\Database\Seeder;
use Modules\Project\Domain\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entityManager = $this->container[EntityManagerInterface::class];
        $entityManager->persist(new Project(null, 'default'));
        $entityManager->run();
    }
}
