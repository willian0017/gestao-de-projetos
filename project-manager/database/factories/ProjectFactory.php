<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define o estado padrão da model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userId = User::inRandomOrder()->first()->id ?? User::factory()->create()->id;

        return [
            'user_id' => $userId,
            'title' => $this->faker->sentence(3),
            'client_name' => $this->faker->company(),
            'phase' => $this->faker->randomElement([
                'Planejamento',
                'Desenvolvimento',
                'Testes',
                'Concluído',
                'Em Pausa'
            ]),
            'is_active' => $this->faker->boolean(80),
        ];
    }

    /**
     * Indica que o projeto está desativado.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
