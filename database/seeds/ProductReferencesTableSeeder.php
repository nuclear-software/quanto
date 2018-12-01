<?php
use Illuminate\Database\Seeder;
use App\ProductReference;

class ProductReferencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        DB::table('product_references')->insert([

        ]);
    }
}