<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use App\Parsers\PageParser;
use App\Parsers\HeadingParser;
use DiffMatchPatch\DiffMatchPatch;
use Log;

class Version extends Model
{
    use HasUlids;

    protected $fillable = [
        "user_id",
        "content",
        "word_count",
        "compression",
        "is_diff",
    ];

    protected $touches = ["versionable"];

    protected string $html;
    protected string $infoBoxHtml;
    protected array $headings;

    /**
     * Define the inverse polymorphic relationship to the parent model (Page, Post, Comment, etc.).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function versionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Define the relationship to the user who created the version.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function runParser(): void
    {
        $parser = new PageParser($this->content);
        $infoBoxParser = new PageParser($this->content, true);

        $this->html = $parser->parse();
        $this->headings = $parser->headings;
        $this->infoBoxHtml = $infoBoxParser->parse();
    }

    public function getHtml(): string
    {
        if (!isset($this->html)) {
            $this->runParser();
        }

        return $this->html;
    }

    public function getInfoBoxHtml(): string
    {
        if (!isset($this->infoBoxHtml)) {
            $this->runParser();
        }

        return $this->infoBoxHtml;
    }

    public function getHeadings(): array
    {
        if (!isset($this->headings)) {
            $this->runParser();
        }

        return $this->headings;
    }

    /**
     * Get/Set the reconstructed content
     */
    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->reconstructContent($value),
            set: fn(string $value) => $this->compress($value)
        )->shouldCache();
    }

    /**
     * Decompress content using the specified compression method.
     *
     * @param string $compressed
     * @return string
     */
    protected function decompressContent($compressed): string
    {
        $data = hex2bin(stream_get_contents($compressed));

        if ($this->compression === "gzip") {
            return gzuncompress($data);
        }

        // Add other decompression methods if needed
        return $data;
    }

    /**
     * Compress content using gzip.
     */
    protected function compress(string $rawText): string
    {
        if (env("COMPRESS_VERSIONS") === false) {
            return bin2hex($rawText);
        }

        // Setting compression here isn't working for some reason
        $this->compression = "gzip";
        return bin2hex(gzcompress($rawText));
    }

    /**
     * Is this version the latest version. Will be false if the parent has been rolled
     * back to this version.
     */
    protected function isLatest(): bool
    {
        $newerVersionsCount = static::query()
            ->where("versionable_id", $this->getAttribute("versionable_id"))
            ->where("versionable_type", $this->getAttribute("versionable_type"))
            ->where("id", ">", $this->getAttribute("id"))
            ->count();

        return $newerVersionsCount === 0;
    }

    /**
     * Is this version the only version on the parent.
     */
    protected function isOnly(): bool
    {
        $newerVersionsCount = static::query()
            ->where("versionable_id", $this->getAttribute("versionable_id"))
            ->where("versionable_type", $this->getAttribute("versionable_type"))
            ->where("id", "!=", $this->getAttribute("id"))
            ->count();

        return $newerVersionsCount === 0;
    }

    /**
     * Get all versions required to reconstruct this version.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getRelatedVersions(): Collection
    {
        // Get all versions for the same parent (e.g., versionable_id and versionable_type)
        // Including this one.
        $versions = static::query()
            ->where("versionable_id", $this->getAttribute("versionable_id"))
            ->where("versionable_type", $this->getAttribute("versionable_type"))
            ->where("id", "<=", $this->getAttribute("id"))
            ->orderBy("id")
            ->get();

        // Filter only versions up to the last non-diff version
        $nonDiffIndex = $versions->search(fn($v) => !$v->is_diff);
        return $versions->slice($nonDiffIndex);
    }

    /**
     * Reconstruct the full content by applying diffs.
     *
     * @return string
     */
    protected function reconstructContent($value): string
    {
        if ($this->getAttribute("is_diff") === false) {
            return $this->decompressContent($value);
        }

        $versions = $this->getRelatedVersions();

        // Start with the base version (where is_diff is false)
        $baseVersion = $versions->shift();
        $content = $this->decompressContent(
            $baseVersion->attributes["content"]
        );

        $differ = new DiffMatchPatch();

        // Apply each diff sequentially to reconstruct the content
        foreach ($versions as $version) {
            $diff = $this->decompressContent($version->attributes["content"]);
            $diffs = $differ->patch_fromText($diff);
            $content = $differ->patch_apply($diffs, $content)[0];
        }

        return $content;
    }

    /**
     * Returns either the given text or, a diff/patch string.
     * @param string $rawText
     */
    public function encodeContent(string $rawText): void
    {
        if (env("COMPRESS_VERSIONS") != false) {
            $this->compression = "gzip";
        }

        if (env("USE_DIFFS") === false) {
            $this->content = $rawText;
            return;
        }

        if ($this->isOnly() || !$this->isLatest()) {
            $this->content = $rawText;
            return;
        }

        $differ = new DiffMatchPatch();

        $parentVersion = $this->getRelatedVersions()->pop(2);
        $diffs = $differ->patch_make($parentVersion[1]->content, $rawText);

        $this->is_diff = true;
        $this->content = $differ->patch_toText($diffs);
    }
}
