<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'for testing purposes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Permission::create(['name' => 'view categories']);
        Permission::create(['name' => 'update categories']);
        Permission::create(['name' => 'delete categories']);
        Permission::create(['name' => 'view attributes']);
        Permission::create(['name' => 'update attributes']);
        Permission::create(['name' => 'delete attributes']);
        Permission::create(['name' => 'view products']);
        Permission::create(['name' => 'update products']);
        Permission::create(['name' => 'delete products']);
        return 0;
    }
}
