<?php
use Illuminate\Database\Seeder;
use App\Membership;

class MembershipsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        DB::table('memberships')->insert([

        ]);
    }
}