<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_id',
        'start',
        'end',
    ];

    protected $dates = [
        'start',
        'end',
    ];

    public function getTitleAttribute()
    {
        $startTime = $this->start->format('H:i');
        $endTime = $this->end->format('H:i');
        $userName = $this->user->name;
        return "[$startTime - $endTime] $userName";
    }

    // **************************** SCOPES **************************** //

    public function scopeCollidingWith(Builder $query, Carbon $start, Carbon $end, int $deviceId, ?int $excludeId): Builder
    {
        if($excludeId) $query = $query->where('id', '!=', $excludeId);

        return $query
            ->where('device_id', $deviceId)
            ->where(function($query) use ($start, $end) {
                $query->where(function($query) use ($start, $end) {
                    $query->where('start', '<', $start)->where('end', '>', $start); // left
                })->orWhere(function($query) use ($start, $end) {
                    $query->where('start', '<', $end)->where('end', '>', $end); // right
                })->orWhere(function($query) use ($start, $end) {
                    $query->where('start', '>=', $start)->where('end', '<=', $end); // inner
                })->orWhere(function($query) use ($start, $end) {
                    $query->where('start', '<', $start)->where('end', '>', $end); // outer
                });
            });
    }

    public function scopeForDay(Builder $query, Carbon $day, ?int $excludeId): Builder
    {
        if($excludeId) $query = $query->where('id', '!=', $excludeId);

        return $query->whereDate('start', $day);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('start', Carbon::today());
    }

    // **************************** RELATIONS **************************** //

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
