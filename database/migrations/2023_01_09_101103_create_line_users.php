<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\BaseMigration;

class CreateLineUsers extends BaseMigration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_users', function (Blueprint $table) {
            $table->id()->comment('LINEユーザーID');
            $table->string('token', 256)->comment('識別トークン');
            $table->text('line_id_token')->nullable()->comment('LINE IDトークン');
            $table->text('line_owner_id')->nullable()->comment('LINE利用者ID');
            $table->text('profile_picture_id')->nullable()->comment('プロフィール画像URL');
            $table->text('line_name')->nullable()->comment('LINE名');
            $table->string('screen_path', 1024)->nullable()->comment('画面パス');
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
        Schema::dropIfExists('line_users');
    }
}
