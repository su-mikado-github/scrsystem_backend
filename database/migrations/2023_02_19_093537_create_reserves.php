<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\ReserveTypes;

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
            $table->enum('type', ReserveTypes::id_list())->comment('種別');
            $table->date('date')->comment('利用日');
            $table->time('time')->nullable()->comment('利用時刻');
            $table->time('end_time')->nullable()->comment('利用終了時刻');
            $table->bigInteger('user_id')->comment('利用者ID');
            $table->dateTime('reserve_dt')->comment('予約日時');
            $table->integer('reserve_count')->comment('予約数');
            $table->boolean('is_table_share')->comment('相席可フラグ');
            $table->dateTime('checkin_dt')->nullable()->comment('チェックイン日時');
            $table->dateTime('cancel_dt')->nullable()->comment('取消日時');
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
