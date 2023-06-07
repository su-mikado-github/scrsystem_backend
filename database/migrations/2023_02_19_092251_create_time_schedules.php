<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\BaseMigration;

use App\ReserveTypes;

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
            $table->enum('type', ReserveTypes::id_list())->comment('種別');
            $table->time('time')->comment('時刻');
            $this->build_common_columns($table);
        });
        $rows = [
            //お弁当予約
            [ 'type'=>strval(ReserveTypes::LUNCHBOX), 'time'=>'12:00:00' ],
            [ 'type'=>strval(ReserveTypes::LUNCHBOX), 'time'=>'12:15:00' ],
            [ 'type'=>strval(ReserveTypes::LUNCHBOX), 'time'=>'12:30:00' ],
            [ 'type'=>strval(ReserveTypes::LUNCHBOX), 'time'=>'12:45:00' ],
            [ 'type'=>strval(ReserveTypes::LUNCHBOX), 'time'=>'13:00:00' ],
            [ 'type'=>strval(ReserveTypes::LUNCHBOX), 'time'=>'13:15:00' ],
            [ 'type'=>strval(ReserveTypes::LUNCHBOX), 'time'=>'13:30:00' ],
            [ 'type'=>strval(ReserveTypes::LUNCHBOX), 'time'=>'13:45:00' ],
            [ 'type'=>strval(ReserveTypes::LUNCHBOX), 'time'=>'14:00:00' ],
            [ 'type'=>strval(ReserveTypes::LUNCHBOX), 'time'=>'14:15:00' ],
            [ 'type'=>strval(ReserveTypes::LUNCHBOX), 'time'=>'14:30:00' ],
            //来店予約（サッカー部）
            [ 'type'=>strval(ReserveTypes::VISIT_SOCCER), 'time'=>'09:50:00' ],
            [ 'type'=>strval(ReserveTypes::VISIT_SOCCER), 'time'=>'10:10:00' ],
            [ 'type'=>strval(ReserveTypes::VISIT_SOCCER), 'time'=>'10:30:00' ],
            [ 'type'=>strval(ReserveTypes::VISIT_SOCCER), 'time'=>'11:00:00' ],
            [ 'type'=>strval(ReserveTypes::VISIT_SOCCER), 'time'=>'11:30:00' ],
            [ 'type'=>strval(ReserveTypes::VISIT_SOCCER), 'time'=>'12:00:00' ],
            [ 'type'=>strval(ReserveTypes::VISIT_SOCCER), 'time'=>'12:15:00' ],
            [ 'type'=>strval(ReserveTypes::VISIT_SOCCER), 'time'=>'12:30:00' ],
            //来店予約（サッカー部以外）
            [ 'type'=>strval(ReserveTypes::VISIT_NO_SOCCER), 'time'=>'12:00:00' ],
            [ 'type'=>strval(ReserveTypes::VISIT_NO_SOCCER), 'time'=>'12:15:00' ],
            [ 'type'=>strval(ReserveTypes::VISIT_NO_SOCCER), 'time'=>'12:30:00' ],
            [ 'type'=>strval(ReserveTypes::VISIT_NO_SOCCER), 'time'=>'12:45:00' ],
            [ 'type'=>strval(ReserveTypes::VISIT_NO_SOCCER), 'time'=>'13:00:00' ],
            [ 'type'=>strval(ReserveTypes::VISIT_NO_SOCCER), 'time'=>'13:15:00' ],
            [ 'type'=>strval(ReserveTypes::VISIT_NO_SOCCER), 'time'=>'13:30:00' ],
            [ 'type'=>strval(ReserveTypes::VISIT_NO_SOCCER), 'time'=>'13:45:00' ],
            [ 'type'=>strval(ReserveTypes::VISIT_NO_SOCCER), 'time'=>'14:00:00' ],
            [ 'type'=>strval(ReserveTypes::VISIT_NO_SOCCER), 'time'=>'14:15:00' ],
            [ 'type'=>strval(ReserveTypes::VISIT_NO_SOCCER), 'time'=>'14:30:00' ],
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
