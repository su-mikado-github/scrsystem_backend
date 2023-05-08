<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\BaseMigration;

use App\DishTypes;

class CreateDishs extends BaseMigration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dishs', function (Blueprint $table) {
            //
            $table->id()->comment('料理ID');
            $table->enum('dish_type', DishTypes::id_list())->comment('料理種類');
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
        Schema::dropIfExists('dishs');
    }
}
