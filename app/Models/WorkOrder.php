<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // 1. Wajib untuk UUID
use Illuminate\Support\Str;

class WorkOrder extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'work_order';

    protected $fillable = [
        'requester_id',
        'ticket_num',
        'kerusakan',
        'kerusakan_detail',
        'priority',
        'work_status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->ticket_num)) {
                $model->ticket_num = 'WO-' . date('Ym') . '-' . strtoupper(Str::random(4));
            }
        });
    }
    public function getStatusColorAttribute()
    {
        return match ($this->work_status) {
            'pending' => 'yellow',
            'in_progress' => 'blue',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray'
        };
    }
    public function getPriorityColorAttribute()
    {
        return match ($this->priority) {
            'low' => 'green',
            'medium' => 'blue',
            'high' => 'yellow',
            'critical' => 'red',
            default => 'gray'
        };
    }
}
