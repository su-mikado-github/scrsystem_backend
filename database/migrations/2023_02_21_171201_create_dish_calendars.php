<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\BaseMigration;

class CreateDishCalendars extends BaseMigration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dish_calendars', function (Blueprint $table) {
            //
            $table->id()->comment('料理カレンダーID');
            $table->bigInteger('calendar_id')->comment('カレンダーID');
            $table->bigInteger('dish_id')->comment('料理ID');
            $table->integer('display_order')->default(0)->comment('表示順');
            $this->build_common_columns($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dish_calendars');
    }
}
