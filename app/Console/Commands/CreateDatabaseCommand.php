<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDO;

class CreateDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:database {dbname?} {connection?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the database for initial setup only';

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
        try{
            $dbName = $this->hasArgument('dbname') && $this->argument('dbname') ? $this->argument('dbname'): env('DB_DATABASE');
            $connection = $this->hasArgument('connection') && $this->argument('connection') ? $this->argument('connection'): env('DB_CONNECTION');
            
            if(empty($dbName) || empty($connection)){
                return $this->error("Command arguments dbname and connection can not be empty");
            }

            $hasDb = DB::connection($connection)->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "."'".$dbName."'");

            if(empty($hasDb)) {
                DB::connection($connection)->select('CREATE DATABASE '. $dbName);
                $this->info("Database '$dbName' created for '$connection' connection");
            }
            else {
                $this->info("Database $dbName already exists for $connection connection");
                if ($this->confirm("Do you wish to drop existing database: $dbName ?", false)) {
                     DB::connection($connection)->select('DROP DATABASE '. $dbName);
                     $this->warn("Database '$dbName' dropped. Create New Database with this command passing 'dbname' as command argument");
                }
                $this->info("Exited");
            }
        }
        catch (\Exception $e){
            $this->error($e->getMessage());
        }
    }
}
