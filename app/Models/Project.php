<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'client_id', 'start_date', 'end_date',
        'rate', 'rate_type', 'priority', 'leader', 'team',
        'description', 'files', 'progress', 'status'
    ];

    protected $casts = [
        'team' => 'array',
        'files' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'boolean'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function leader()
    {
        return $this->belongsTo(Employee::class,'leader');
    }

    public function employee($id)
    {
        $employee = Employee::where('id', '=', $id)->first();
        return $employee;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', '=', 'active');
    }
}
