<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\BaseMigration;

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
            $table->text('name')->comment('料理名');
            $table->text('description')->comment('説明文');
            $table->text('document_path')->nullable()->comment('資料パス');
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
