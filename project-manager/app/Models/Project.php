<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'client_name',
        'phase',
        'is_active',
    ];

    /**
     * Obtém o usuário que criou o projeto.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtém os usuários membros do projetos.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Define o escopo de uma consulta para mostrar apenas
     * projetos visíveis a um determinado usuário.
     *
     * @param Builder $query
     * @param User $user
     * 
     * @return void
     */
    public function scopeForUser(Builder $query, User $user): void
    {
        $query->where('user_id', $user->id)
            ->orWhereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
    }

    /**
     * Define uma consulta para filtrar por título.
     *
     * @param Builder $query
     * @param ?string $title
     * 
     * @return void
     */
    public function scopeWhereTitle(Builder $query, ?string $title): void
    {
        if ($title) {
            $query->where('title', 'like', '%' . $title . '%');
        }
    }

    /**
     * Define uma consulta para filtrar pelo nome do cliente
     *
     * @param Builder $query
     * @param ?string $clientName
     * 
     * @return void
     */
    public function scopeWhereClientName(Builder $query, ?string $clientName): void
    {
        if ($clientName) {
            $query->where('client_name', 'like', '%' . $clientName . '%');
        }
    }

    /**
     * Define uma consulta para buscar por fase.
     *
     * @param Builder $query
     * @param ?string $phase
     * 
     * @return void
     */
    public function scopeWherePhase(Builder $query, ?string $phase): void
    {
        if ($phase) {
            $query->where('phase', $phase);
        }
    }

    /**
     * Define uma consulta para filtrar por status (ativo/inativo)
     *
     * @param Builder $query
     * @param ?string $status
     * 
     * @return void
     */
    public function scopeWhereStatus(Builder $query, ?string $status): void
    {
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }
    }
}