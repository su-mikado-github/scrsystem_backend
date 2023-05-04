<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\BaseMigration;

class CreateSeats extends BaseMigration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seats', function (Blueprint $table) {
            //
            $table->id()->comment('座席ID');
            $table->integer('seat_no')->comment('座席番号');
            $table->integer('seat_group_no')->comment('座席グループ番号');
            $this->build_common_columns($table);
        });

        $rows = [
            [ 'seat_no'=>1, 'seat_group_no'=>1 ],
            [ 'seat_no'=>2, 'seat_group_no'=>1 ],
            [ 'seat_no'=>3, 'seat_group_no'=>1 ],
            [ 'seat_no'=>4, 'seat_group_no'=>1 ],
            [ 'seat_no'=>5, 'seat_group_no'=>2 ],
            [ 'seat_no'=>6, 'seat_group_no'=>2 ],
            [ 'seat_no'=>7, 'seat_group_no'=>2 ],
            [ 'seat_no'=>8, 'seat_group_no'=>2 ],
            [ 'seat_no'=>9, 'seat_group_no'=>3 ],
            [ 'seat_no'=>10, 'seat_group_no'=>3 ],
            [ 'seat_no'=>11, 'seat_group_no'=>3 ],
            [ 'seat_no'=>12, 'seat_group_no'=>3 ],
            [ 'seat_no'=>13, 'seat_group_no'=>4 ],
            [ 'seat_no'=>14, 'seat_group_no'=>4 ],
            [ 'seat_no'=>15, 'seat_group_no'=>4 ],
            [ 'seat_no'=>16, 'seat_group_no'=>4 ],
            [ 'seat_no'=>17, 'seat_group_no'=>5 ],
            [ 'seat_no'=>18, 'seat_group_no'=>6 ],
            [ 'seat_no'=>19, 'seat_group_no'=>7 ],
            [ 'seat_no'=>20, 'seat_group_no'=>8 ],
        ];
        $this->insert_rows('seats', $rows);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seats');
    }
}
