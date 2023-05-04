<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\BaseMigration;

class CreateSchoolYears extends BaseMigration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_years', function (Blueprint $table) {
            $table->id()->comment('学年ID');
            $table->text('name')->nullable()->comment('学年名');
            $table->integer('display_order')->comment('表示順');
            $this->build_common_columns($table);
        });

        $rows = [
            [ 'name'=>'1学年', 'display_order'=>1 ],
            [ 'name'=>'2学年', 'display_order'=>2 ],
            [ 'name'=>'3学年', 'display_order'=>3 ],
            [ 'name'=>'4学年', 'display_order'=>4 ],
        ];
        $this->insert_rows('school_years', $rows);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_years');
    }
}
