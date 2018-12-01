@php echo '<?php'; @endphp

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class {!!$migration['class_name']!!} extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('{!!$migration['table']!!}', function (Blueprint $table) {
            $table->increments('id');

			@foreach($migration['columns'] as $column)$table->{!! $column['type'] !!}('{!! $column['name'] !!}'){!! $column['modifier'] !!};
            @endforeach

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('{!!$migration['table']!!}');
    }
}
