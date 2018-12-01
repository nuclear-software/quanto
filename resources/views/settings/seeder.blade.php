@php echo '<?php'; @endphp

use Illuminate\Database\Seeder;
use {!!$seeder['model_path']!!};

class {!!$seeder['class_name']!!} extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('{!!$seeder['table']!!}')->insert([

        ]);
    }
}