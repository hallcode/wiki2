<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Page;
use PHPUnit\Exception;

class CalculateLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:calculate-links";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Recalculate all internal links between pages";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pages = Page::all();
        try {
            foreach ($pages as $page) {
                $page->calculateLinks();
            }
        } catch (Exception $e) {
            $this->fail("There was an error: $e");
        }

        $this->info("Complete.");
    }
}
