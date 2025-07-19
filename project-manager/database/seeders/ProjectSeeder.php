<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = $this->findUsers();

        if ($users->isEmpty()) {
            $this->command->info('Nenhum usuário encontrado para associar aos projetos. Crie usuários primeiro.');
            return;
        }

        $this->createProjects($users);

        $this->command->info('Projetos e membros de equipe de exemplo criados!');
    }

    /**
     * Encontra os usuários.
     *
     * @return Collection
     */
    private function findUsers(): Collection
    {
        if (User::count() === 0) {
            User::factory(5)->create();
        }

        return User::all();
    }

    /**
     * Cria projetos.
     *
     * @param Collection $users
     * @return void
     */
    private function createProjects(Collection $users): void
    {
        $project1 = Project::create([
            'user_id' => $users->random()->id,
            'title' => 'Desenvolvimento Aplicativo',
            'client_name' => 'Apple',
            'phase' => 'Em Desenvolvimento',
            'is_active' => true,
        ]);

        $project1->users()->attach($users->random(rand(1, 3))->pluck('id'));

        $project2 = Project::create([
            'user_id' => $users->random()->id,
            'title' => 'Campanha de Marketing',
            'client_name' => 'NuBank',
            'phase' => 'Concluído',
            'is_active' => true,
        ]);

        $project2->users()->attach($users->random(rand(1, 2))->pluck('id'));

        $project3 = Project::create([
            'user_id' => $users->random()->id,
            'title' => 'Atualização do Sistema',
            'client_name' => 'Mercado Livre',
            'phase' => 'Em Pausa',
            'is_active' => false,
        ]);

        $project3->users()->attach($users->random(rand(2, 4))->pluck('id'));
    }
}