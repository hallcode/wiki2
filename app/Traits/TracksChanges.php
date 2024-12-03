<?php

namespace App\Traits;

use App\Models\Change;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Carbon\Carbon;

trait TracksChanges
{
    /**
     * Track changes in the database.
     */
    public static function bootTracksChanges()
    {
        if (
            !isset(static::$dontTrack) ||
            !in_array("created", static::$dontTrack)
        ) {
            static::created(function ($model) {
                $model->saveChange("created");
            });
        }

        if (
            !isset(static::$dontTrack) ||
            !in_array("updated", static::$dontTrack)
        ) {
            static::updated(function ($model) {
                if (!$model->isRecentlyCreated()) {
                    $model->saveChange("updated");
                }
            });
        }

        if (
            !isset(static::$dontTrack) ||
            !in_array("deleted", static::$dontTrack)
        ) {
            static::deleted(function ($model) {
                $model->saveChange("deleted");
            });
        }
    }

    public function isRecentlyCreated(): bool
    {
        $cuttoff = Carbon::now()->subMinutes(5);
        return $this->created_at->greaterThan($cuttoff);
    }

    /**
     * Define a polymorphic one-to-many relationship with Change.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function changes(): MorphMany
    {
        return $this->morphMany(Change::class, "changeable");
    }

    public function saveChange(string $type, string $text = null)
    {
        $this->changes()->create([
            "type" => $type,
            "description" => $text,
            "user_id" => auth()->id(),
        ]);
    }
}
