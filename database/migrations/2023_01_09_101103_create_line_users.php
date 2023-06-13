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
            $table->text('line_owner_id')->nullable()->comment('LINE利用者ID');
            $table->string('token', 256)->nullable()->comment('識別トークン');
            $table->text('id_token')->nullable()->comment('IDトークン');
            $table->string('access_token', 256)->nullable()->comment('アクセス・トークン');
            $table->string('refresh_token', 256)->nullable()->comment('リフレッシュ・トークン');
            $table->text('profile_picture_url')->nullable()->comment('プロフィール画像URL');
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
