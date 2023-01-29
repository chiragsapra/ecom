<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Log;

class InsertProductData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the product data from csv file.';

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
        if (($open = fopen(storage_path() . "/products.csv", "r")) !== FALSE) {
            $count = 0;
            while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                if ($data[0] != 'ID') {
                    $product = new Product;
                    $product->productname = $data[1];
                    $product->price = $data[2];
                    $product->save();
                }
                $count++;
            }
            fclose($open);
        }
        Log::channel('command')->info($count . " Numbers of Products has been added to products table");
    }
}
