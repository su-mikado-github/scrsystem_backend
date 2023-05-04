<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\BaseMigration;

class CreateReserves extends BaseMigration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reserves', function (Blueprint $table) {
            $table->id()->comment('予約ID');
            $table->date('date')->comment('利用日');
            $table->bigInteger('time_schedule_id')->comment('利用時刻ID');
            $table->bigInteger('user_id')->comment('利用者ID');
            $table->dateTime('reserve_dt')->comment('予約日時');
            $table->integer('reserve_count')->comment('予約数');
            $table->boolean('is_table_share')->comment('相席可フラグ');
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
        Schema::dropIfExists('reserves');
    }
}
