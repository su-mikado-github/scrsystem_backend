<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\BaseMigration;

class CreateTimes extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('times', function (Blueprint $table) {
            $table->id()->comment('時刻ID');
            $table->time('time')->comment('時刻');
            $table->integer('hour')->comment('時');
            $table->integer('minute')->comment('分');
            $table->integer('time_minutes')->comment('時刻(分)');
            $this->build_common_columns($table);
        });

        for ($hour=0; $hour<24; $hour++) {
            for ($minute=0; $minute<60; $minute++) {
                $this->insert_row('times', [ 'time'=>sprintf('%02d:%02d', $hour, $minute), 'hour'=>$hour, 'minute'=>$minute, 'time_minutes'=>($hour*60+$minute) ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('times');
    }
}
