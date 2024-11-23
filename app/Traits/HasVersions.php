<?php

namespace App\Traits;

use App\Models\Version;

trait HasVersions
{
    /**
     * Boot the HasVersions trait.
     */
    protected static function bootHasVersions()
    {
        // Automatically set the current version ID on save if not set
        static::saving(function ($model) {
            if (!$model->version_id && $model->versions()->exists()) {
                $model->version_id = $model->versions()->latest()->first()->id;
            }
        });
    }

    /**
     * Define a polymorphic one-to-many relationship with Version.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function versions()
    {
        return $this->morphMany(Version::class, "versionable");
    }

    /**
     * Define a relationship to the current Version.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentVersion()
    {
        return $this->belongsTo(Version::class, "version_id");
    }

    /**
     * Create a new version for the model.
     *
     * @param string $content
     * @return Version
     */
    public function createVersion(string $content, $setCurrent = true)
    {
        $word_count = str_word_count(strip_tags($content ?? ""));
        $version = $this->versions()->create([
            "content" => $content,
            "word_count" => $word_count,
            "user_id" => auth()->id() ?? null,
        ]);

        $version->encodeContent($content);
        $version->save();

        // Optionally set this new version as the current version
        if ($setCurrent) {
            $this->version_id = $version->id;
            $this->save();
        }

        return $version;
    }

    /**
     * Set a specific version as the current version.
     *
     * @param Version $version
     * @return bool
     */
    public function setCurrentVersion(Version $version)
    {
        if (
            $this->versions()
                ->where("id", $version->id)
                ->exists()
        ) {
            return $this->update(["version_id" => $version->id]);
        }

        return false;
    }

    /**
     * Rollback to a specific version.
     *
     * @param Version $version
     * @return bool
     */
    public function rollbackToVersion(Version $version)
    {
        if (
            $this->versions()
                ->where("id", $version->id)
                ->exists()
        ) {
            return $this->update([
                "version_id" => $version->id,
            ]);
        }

        return false;
    }

    /**
     * Check if the current version matches the given version.
     *
     * @param Version $version
     * @return bool
     */
    public function isCurrentVersion(Version $version)
    {
        return $this->version_id === $version->id;
    }
}
