<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\BaseMigration;

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
            $table->enum('sex', [ 1, 2 ])->nullable()->comment('性別');
            $table->string('email', 256)->nullable()->comment('メールアドレス');
            $table->string('telephone_no', 16)->nullable()->comment('電話番号');
            $table->bigInteger('school_year_id')->nullable()->comment('学年ID');
            $table->bigInteger('affiliation_id')->nullable()->comment('所属ID');
            $table->bigInteger('line_user_id')->nullable()->comment('LINEユーザーID');
            $table->boolean('is_admin')->comment('システム管理者フラグ');
            $table->string('admin_password', 256)->comment('システム管理者パスワード')->default('*');
            $table->datetime('last_login_dt')->nullable()->comment('最終ログイン日時');
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
            'is_admin' => true,
            'admin_password' => Hash::make('password'),
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
