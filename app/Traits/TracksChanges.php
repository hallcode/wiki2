<?php

namespace App\Traits;

use App\Models\Change;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
                $model->saveChange("updated");
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
