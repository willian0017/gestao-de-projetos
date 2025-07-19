<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $otherUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->otherUser = User::factory()->create();
    }

    /**
     * Usuário autenticado pode ver a dashboard.
     *
     * @return void
     */
    public function test_authenticated_user_can_view_projects_index(): void
    {
        $this->actingAs($this->user)
            ->get(route('projects.index'))
            ->assertStatus(200);
    }

    /**
     * Usuário não autenticado não pode ver a dashboard.
     *
     * @return void
     */
    public function test_guest_cannot_view_projects_index(): void
    {
        $this->get(route('projects.index'))
            ->assertRedirect(route('login'));
    }

    /**
     * Usuário autenticado pode criar um projeto.
     *
     * @return void
     */
    public function test_authenticated_user_can_create_a_project(): void
    {
        $projectData = [
            'title' => 'Novo projeto de teste',
            'client_name' => 'Cliente Novo',
            'phase' => 'Em desenvolvimento',
            'team_members' => [$this->otherUser->id],
        ];

        $response = $this->actingAs($this->user)
            ->post(route('projects.store'), $projectData);

        $response->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects', [
            'user_id' => $this->user->id,
            'title' => 'Novo projeto de teste',
            'client_name' => 'Cliente Novo',
            'phase' => 'Em desenvolvimento',
        ]);

        $project = Project::where('title', 'Novo projeto de teste')->first();
        $this->assertTrue($project->users->contains($this->otherUser));
    }

    /**
     * Um usuário pode ver apenas projetos que ele criou 
     * ou que ele faz parte.
     *
     * @return void
     */
    public function test_a_user_can_only_view_projects_they_created_or_are_allocated_to(): void
    {
        $projectByUser = Project::factory()->create(['user_id' => $this->user->id]);

        $projectByOtherUser = Project::factory()->create(['user_id' => $this->otherUser->id]);

        $projectAllocated = Project::factory()->create(['user_id' => $this->otherUser->id]);
        $projectAllocated->users()->attach($this->user->id);


        $response = $this->actingAs($this->user)
            ->get(route('projects.index'));

        $response->assertStatus(200);

        $response->assertSee($projectByUser->title);

        $response->assertDontSee($projectByOtherUser->title);

        $response->assertSee($projectAllocated->title);
    }

    /**
     * Usuário autenticado pode atualizar seus projetos.
     *
     * @return void
     */
    public function test_authenticated_user_can_update_their_own_project(): void
    {
        $project = Project::factory()->create(['user_id' => $this->user->id]);

        $updatedData = [
            'title' => 'Atualizar o título',
            'client_name' => 'Atualizar o cliente',
            'phase' => 'Testes',
            'is_active' => true,
            'team_members' => [$this->otherUser->id],
        ];

        $response = $this->actingAs($this->user)
            ->put(route('projects.update', $project), $updatedData);

        $response->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'Atualizar o título',
            'client_name' => 'Atualizar o cliente',
            'phase' => 'Testes',
        ]);

        $project->refresh();
        $this->assertEquals([$this->otherUser->id], $project->users->pluck('id')->toArray());
    }

    /**
     * Usuário não pode atualizar um projeto que ele não for um criador
     * ou que ele não tem vínculo.
     *
     * @return void
     */
    public function test_user_cannot_update_project_they_are_not_creator_or_allocated_to(): void
    {
        $project = Project::factory()->create(['user_id' => $this->otherUser->id]);

        $response = $this->actingAs($this->user)
            ->put(route('projects.update', $project), [
                'title' => 'Atualização inválida',
                'client_name' => 'Cliente inválido',
                'phase' => 'Inválido',
                'is_active' => true,
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('projects', ['title' => 'Atualização inválida']);
    }

    /**
     * Usuário autenticado pode desativar seu próprio projeto.
     *
     * @return void
     */
    public function test_authenticated_user_can_deactivate_their_own_project(): void
    {
        $project = Project::factory()->create(['user_id' => $this->user->id, 'is_active' => true]);

        $response = $this->actingAs($this->user)
            ->delete(route('projects.destroy', $project));

        $response->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'is_active' => false,
        ]);
    }

    /**
     * O usuário não pode desativar um projeto que ele não criou.
     *
     * @return void
     */
    public function test_user_cannot_deactivate_project_they_did_not_create(): void
    {
        $project = Project::factory()->create(['user_id' => $this->otherUser->id, 'is_active' => true]);

        $response = $this->actingAs($this->user)
            ->delete(route('projects.destroy', $project));

        $response->assertStatus(403);
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'is_active' => true,
        ]);
    }
}
