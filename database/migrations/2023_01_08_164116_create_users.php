<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\BaseMigration;

use App\Flags;
use App\Genders;

use App\Models\User;

class CreateUsers extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('ユーザーID');
            $table->text('last_name')->nullable()->comment('姓');
            $table->text('first_name')->nullable()->comment('名');
            $table->text('last_name_kana')->nullable()->comment('姓かな');
            $table->text('first_name_kana')->nullable()->comment('名かな');
            $table->date('birthday')->nullable()->comment('誕生日');
            $table->enum('sex', Genders::id_list())->nullable()->comment('性別');
            $table->string('email', 256)->nullable()->comment('メールアドレス');
            $table->string('telephone_no', 16)->nullable()->comment('電話番号');
            $table->bigInteger('school_year_id')->nullable()->comment('学年ID');
            $table->bigInteger('affiliation_id')->nullable()->comment('所属ID');
            $table->bigInteger('affiliation_detail_id')->nullable()->comment('所属詳細ID');
            $table->bigInteger('line_user_id')->nullable()->comment('LINEユーザーID');
            $table->boolean('is_admin')->default(false)->comment('システム管理者フラグ');
            $table->string('admin_password', 256)->comment('システム管理者パスワード')->default('*');
            $table->string('reset_token', 256)->nullable()->comment('リセット・トークン');
            $table->datetime('last_login_dt')->nullable()->comment('最終ログイン日時');
            $table->string('checkin_token', 256)->nullable()->comment('IDトークン');
            $table->boolean('is_initial_setting')->default(false)->comment('初期設定済フラグ');
            $this->build_common_columns($table);
        });

        DB::table('users')->insert([
            'last_name' => 'システム',
            'first_name' => '管理者',
            'last_name_kana' => 'しすてむ',
            'first_name_kana' => 'かんりしゃ',
            'birthday' => '2023/04/01',
            'sex' => 1,
            'email' => config('mail.from.address'),
            'telephone_no' => '00-0000-0000',
            'school_year_id' => 0,
            'affiliation_id' => 0,
            'affiliation_detail_id' => 0,
            'is_admin' => Flags::ON,
            'admin_password' => Hash::make('password'),
            'is_initial_setting' => Flags::ON,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
