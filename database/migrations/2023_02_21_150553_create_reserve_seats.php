<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\BaseMigration;

class CreateReserveSeats extends BaseMigration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reserve_seats', function (Blueprint $table) {
            //
            $table->id()->comment('予約座席ID');
            $table->bigInteger('reserve_id')->comment('予約ID');
            $table->bigInteger('seat_id')->comment('座席ID');
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
        Schema::dropIfExists('reserve_seats');
    }
}
