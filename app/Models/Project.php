<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'customer_id',
        'frontend_developer_id',
        'backend_developer_id',
        'server_admin_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function frontendDeveloper(): BelongsTo
    {
        return $this->belongsTo(User::class, 'frontend_developer_id');
    }

    public function backendDeveloper(): BelongsTo
    {
        return $this->belongsTo(User::class, 'backend_developer_id');
    }

    public function serverAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'server_admin_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }
}
