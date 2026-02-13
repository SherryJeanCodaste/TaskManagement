<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'priority',
        'status',
        'progress',
        'progress_image',
        'customer_id',
        'developer_id',
        'project_id',
        'assigned_developer_id',
        'completion_proof',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedDeveloper()
    {
        return $this->belongsTo(User::class, 'assigned_developer_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class)->with('user')->latest();
    }
}
