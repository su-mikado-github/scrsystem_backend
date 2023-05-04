<?php
namespace App;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class BaseMigration extends Migration {
    public function build_common_columns(Blueprint $table) {
        $table->boolean('is_delete')->default(false)->comment('削除済フラグ');
        $table->bigInteger('created_id')->nullable()->comment('作成者ユーザーID');
        $table->timestamp('created_at')->nullable()->comment('作成タイムスタンプ');
        $table->bigInteger('updated_id')->nullable()->comment('更新者ユーザーID');
        $table->timestamp('updated_at')->nullable()->comment('更新タイムスタンプ');
        $table->bigInteger('data_version')->default(0)->comment('データ・バージョン');
    }

    public function insert_rows($table_name, array $rows) {
        collect($rows)->each(function($row) use($table_name) {
            DB::table($table_name)->insert($row);
        });
    }
}
