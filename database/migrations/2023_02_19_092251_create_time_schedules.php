<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\BaseMigration;

class CreateTimeSchedules extends BaseMigration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_schedules', function (Blueprint $table) {
            $table->id()->comment('タイムスケジュールID');
            $table->integer('type')->comment('種別');
            $table->time('time')->comment('時刻');
            $this->build_common_columns($table);
        });
        $rows = [
            //お弁当予約
            [ 'type'=>1, 'time'=>'12:00:00' ],
            [ 'type'=>1, 'time'=>'12:15:00' ],
            [ 'type'=>1, 'time'=>'12:30:00' ],
            [ 'type'=>1, 'time'=>'12:45:00' ],
            [ 'type'=>1, 'time'=>'13:00:00' ],
            [ 'type'=>1, 'time'=>'13:15:00' ],
            [ 'type'=>1, 'time'=>'13:30:00' ],
            [ 'type'=>1, 'time'=>'13:45:00' ],
            [ 'type'=>1, 'time'=>'14:00:00' ],
            [ 'type'=>1, 'time'=>'14:15:00' ],
            [ 'type'=>1, 'time'=>'14:30:00' ],
            //来店予約（サッカー部）
            [ 'type'=>2, 'time'=>'09:50:00' ],
            [ 'type'=>2, 'time'=>'10:10:00' ],
            [ 'type'=>2, 'time'=>'10:30:00' ],
            [ 'type'=>2, 'time'=>'11:00:00' ],
            [ 'type'=>2, 'time'=>'11:30:00' ],
            [ 'type'=>2, 'time'=>'12:00:00' ],
            [ 'type'=>2, 'time'=>'12:15:00' ],
            [ 'type'=>2, 'time'=>'12:30:00' ],
            //来店予約（サッカー部以外）
            [ 'type'=>3, 'time'=>'12:00:00' ],
            [ 'type'=>3, 'time'=>'12:15:00' ],
            [ 'type'=>3, 'time'=>'12:30:00' ],
            [ 'type'=>3, 'time'=>'12:45:00' ],
            [ 'type'=>3, 'time'=>'13:00:00' ],
            [ 'type'=>3, 'time'=>'13:15:00' ],
            [ 'type'=>3, 'time'=>'13:30:00' ],
            [ 'type'=>3, 'time'=>'13:45:00' ],
            [ 'type'=>3, 'time'=>'14:00:00' ],
            [ 'type'=>3, 'time'=>'14:15:00' ],
            [ 'type'=>3, 'time'=>'14:30:00' ],
        ];
        $this->insert_rows('time_schedules', $rows);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_schedules');
    }
}
