<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\BaseMigration;

class CreateUseTickets extends BaseMigration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('use_tickets', function (Blueprint $table) {
            //
            $table->id()->comment('利用回数券ID');
            $table->bigInteger('reserve_id')->comment('予約ID');
            $table->bigInteger('user_id')->comment('利用者ID');
            $table->bigInteger('buy_ticket_id')->comment('購入回数券ID');
            $table->dateTime('use_dt')->comment('利用日時');
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
        Schema::dropIfExists('use_tickets');
    }
}
