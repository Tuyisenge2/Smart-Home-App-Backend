<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scene extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'days_of_week',
        'start_time',
        'end_time',
        'send_notification',
        'device_states',
        'is_active',
        'user_id'
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'device_states' => 'array',
        'send_notification' => 'boolean',
        'is_active' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 