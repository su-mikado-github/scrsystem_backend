<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\BaseMigration;

class CreateBuyTickets extends BaseMigration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_tickets', function (Blueprint $table) {
            //
            $table->id()->comment('購入回数券ID');
            $table->bigInteger('user_id')->comment('購入者ID');
            $table->dateTime('buy_dt')->comment('購入日時');
            $table->integer('buy_year')->comment('購入年');
            $table->integer('buy_month')->comment('購入月');
            $table->integer('buy_day')->comment('購入日');
            $table->bigInteger('ticket_id')->comment('回数券ID');
            $table->integer('ticket_count')->comment('購入枚数');
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
        Schema::dropIfExists('buy_tickets');
    }
}
