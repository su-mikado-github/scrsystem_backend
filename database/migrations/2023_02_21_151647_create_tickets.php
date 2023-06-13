<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\BaseMigration;

class CreateTickets extends BaseMigration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            //
            $table->id()->comment('回数券ID');
            $table->text('name')->comment('名称');
            $table->integer('ticket_count')->comment('枚数');
            $table->integer('display_order')->default(1)->comment('表示順');
            $this->build_common_columns($table);
        });

        $rows = [
            [ 'name'=>'３枚セット', 'ticket_count'=>3, 'display_order'=>1 ],
            [ 'name'=>'５枚セット', 'ticket_count'=>5, 'display_order'=>2 ],
            [ 'name'=>'１０枚セット', 'ticket_count'=>10, 'display_order'=>3 ],
        ];
        $this->insert_rows('tickets', $rows);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
