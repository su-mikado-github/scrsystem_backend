<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\DishTypes;

use App\BaseMigration;

class CreateDishMenus extends BaseMigration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dish_menus', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('calendar_id')->comment('カレンダーID');
            $table->enum('dish_type', DishTypes::id_list())->comment('料理種類');
            $table->integer('display_order')->comment('表示順');
            $table->text('name')->nullable()->comment('名称');
            $table->double('energy')->comment('エネルギー');
            $table->double('carbohydrates')->comment('炭水化物');
            $table->double('protein')->comment('たんぱく質');
            $table->double('lipid')->comment('脂質');
            $table->double('dietary_fiber')->comment('食物繊維');
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
        Schema::dropIfExists('dish_menus');
    }
}
