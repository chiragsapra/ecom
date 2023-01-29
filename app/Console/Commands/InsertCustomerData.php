<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use Log;

class InsertCustomerData extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the customer data from csv file.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        if (($open = fopen(storage_path() . "/customers.csv", "r")) !== FALSE) {
            $count = 0;
            while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                if ($data[0] != 'ID') {
                    $customer = new Customer;
                    $customer->jobTitle = $data[1];
                    $customer->email = $data[2];
                    $customer->name = $data[3];
                    $customer->regSince = $data[4];
                    $customer->phone = $data[5];
                    $customer->save();
                }
                $count++;
            }
            fclose($open);
        }
        Log::channel('command')->info($count . " Numbers of data has been added to customer table");
    }

}
