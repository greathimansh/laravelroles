<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\AdminSeeder;
use Database\Seeders\RoleTableSeeder;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\ConsoleOutput;

class install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();return 0;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $output =new ConsoleOutput();
        Artisan::call('migrate');
        $output->writeln('Run Migrate Successfully');
        
        $role = new RoleTableSeeder();
        $role->run();
        $output->writeln('Role Seeder Successfully');

        $admin = new AdminSeeder();
        $admin->run();
        $output->writeln('Admin Seeder Successfully');

        // Artisan::call('serve');
        
        return Command::SUCCESS;
    }
}
