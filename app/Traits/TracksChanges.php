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
        static::created(function ($model) {
            $model->saveChange("created");
        });

        static::updated(function ($model) {
            $model->saveChange("updated");
        });

        static::deleted(function ($model) {
            $model->saveChange("deleted");
        });
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
