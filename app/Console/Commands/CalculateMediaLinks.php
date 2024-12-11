<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Page;
use PHPUnit\Exception;

class CalculateMediaLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:calculate-media-links";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Calculate and store the links between pages and media.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pages = Page::all();
        try {
            foreach ($pages as $page) {
                $page->calculateImages();
            }
        } catch (Exception $e) {
            $this->fail("There was an error: $e");
        }

        $this->info("Complete.");
    }
}
