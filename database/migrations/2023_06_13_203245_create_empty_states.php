<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Weekdays;

use App\BaseMigration;

class CreateEmptyStates extends BaseMigration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empty_states', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('calendar_id')->comment('カレンダーID');
            $table->date('date')->comment('日付');
            $table->integer('year')->comment('西暦年');
            $table->integer('month')->comment('月');
            $table->integer('day')->comment('日');
            $table->enum('weekday', Weekdays::id_list())->comment('曜日'); // 1:月 2:火 3:水 4:木 5:金 6:土 7:日
            $table->bigInteger('time_id')->comment('時刻ID');
            $table->time('time')->comment('時刻');
            $table->integer('hour')->comment('時');
            $table->integer('minute')->comment('分');
            $table->integer('time_minutes')->comment('分数');
            $table->integer('seat_count')->comment('座席数');
            $table->integer('empty_seat_count')->comment('空き座席数');
            $table->integer('empty_seat_rate')->comment('空き座席率');
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
        Schema::dropIfExists('empty_states');
    }
}
