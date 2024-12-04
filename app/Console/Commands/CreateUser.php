<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:create-user";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Create a new user account and send an email to notify them.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->ask("Username");
        $email = $this->ask("Email");
        $password = $this->secret("Password");

        // Confirm
        $this->info("Check details...");
        $this->line("Username: $username");
        $this->line("Email: $email");
        if (!$this->confirm("Do you wish to continue?")) {
            $this->fail("User cancelled.");
        }

        // Create user
        try {
            $user = User::create([
                "username" => $username,
                "email" => $email,
                "password" => Hash::make($password),
                "is_active" => true,
            ]);
        } catch (Exception $e) {
            $this->fail("There was an error inserting the new user.");
        }

        $this->info("User $username created.");
    }
}
