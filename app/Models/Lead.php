<?php

namespace App\Models;

use App\Enums\LeadStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'company', 'subject', 'message',
        'budget', 'project_type', 'service_id', 'source', 'status',
        'ip_address', 'user_agent', 'referrer', 'meta',
    ];

    protected $casts = [
        'status' => LeadStatus::class,
        'meta' => 'array',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(LeadNote::class)->latest();
    }

    public function scopeStatus(Builder $query, LeadStatus|string $status): Builder
    {
        return $query->where('status', $status instanceof LeadStatus ? $status->value : $status);
    }

    public function scopeNotSpam(Builder $query): Builder
    {
        return $query->where('status', '!=', LeadStatus::Spam->value);
    }
}
