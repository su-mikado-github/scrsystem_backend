<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\BaseMigration;

class CreateCalendars extends BaseMigration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->id()->comment('カレンダーID');
            $table->date('date')->comment('日付');
            $table->integer('year')->comment('西暦年');
            $table->integer('month')->comment('月');
            $table->integer('day')->comment('日');
            $table->integer('weekday')->comment('曜日'); // 1:月 2:火 3:水 4:木 5:金 6:土 7:日
            $table->integer('week_of_month')->comment('曜日月内回数');
            $table->integer('week_of_year')->comment('曜日年内回数');
            $table->boolean('is_holiday')->comment('祝日フラグ');
            $table->text('holiday_name')->nullable()->comment('祝日名');
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
        Schema::dropIfExists('calendars');
    }
}
