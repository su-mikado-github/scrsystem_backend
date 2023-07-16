<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\BaseMigration;

use App\MenuItemTypes;
use App\DishTypes;

class CreateMenus extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id()->comment('メニューID');
            $table->string('category', 1024)->comment('メニュー分類');
            $table->string('item_key', 1024)->comment('メニューアイテム識別キー');
            $table->enum('item_type', MenuItemTypes::id_list())->comment('メニューアイテム種類');
            $table->text('name')->nullable()->comment('名称');
            $table->string('path', 1024)->nullable()->comment('パス');
            $table->text('url')->nullable()->comment('URL');
            $table->text('action')->nullable()->comment('実行アクション');
            $table->string('sub_menu_category', 1024)->nullable()->comment('メニュー分類');
            $table->integer('display_order')->default(1)->comment('表示順');
            $this->build_common_columns($table);
        });

        $rows = [
            // 左サイドメニュー
            [ 'category'=>'left.side.menu', 'item_key'=>'root', 'item_type'=>strval(MenuItemTypes::INSIDE_LINK), 'name'=>'トップページ', 'path'=>'/admin', 'display_order'=>1 ],
            [ 'category'=>'left.side.menu', 'item_key'=>'separator1', 'item_type'=>strval(MenuItemTypes::SEPARATER), 'display_order'=>2 ],
            [ 'category'=>'left.side.menu', 'item_key'=>'dish_menu', 'item_type'=>strval(MenuItemTypes::INSIDE_LINK), 'name'=>'メニュー編集', 'path'=>'/admin/dish_menus', 'display_order'=>3 ],
            [ 'category'=>'left.side.menu', 'item_key'=>'status', 'item_type'=>strval(MenuItemTypes::INSIDE_LINK), 'name'=>'利用状況', 'path'=>'/admin/status', 'display_order'=>4 ],
            [ 'category'=>'left.side.menu', 'item_key'=>'users', 'item_type'=>strval(MenuItemTypes::INSIDE_LINK), 'name'=>'登録者一覧', 'path'=>'/admin/users', 'display_order'=>5 ],
            [ 'category'=>'left.side.menu', 'item_key'=>'tickets', 'item_type'=>strval(MenuItemTypes::INSIDE_LINK), 'name'=>'食券購入一覧', 'path'=>'/admin/tickets', 'display_order'=>6 ],
            [ 'category'=>'left.side.menu', 'item_key'=>'separator2', 'item_type'=>strval(MenuItemTypes::SEPARATER), 'display_order'=>76 ],
            [ 'category'=>'left.side.menu', 'item_key'=>'password_change', 'item_type'=>strval(MenuItemTypes::INSIDE_LINK), 'name'=>'管理パスワード変更', 'path'=>'/admin/password_change', 'display_order'=>8 ],
            [ 'category'=>'left.side.menu', 'item_key'=>'admin_users', 'item_type'=>strval(MenuItemTypes::INSIDE_LINK), 'name'=>'管理者一覧', 'path'=>'/admin/admin_users', 'display_order'=>9 ],
        ];

        // メニュー編集タブ
        foreach (DishTypes::values() as $i => $dish_type) {
            $rows[] = [ 'category'=>'dish_menus.tab', 'item_key'=>$dish_type->key, 'item_type'=>strval(MenuItemTypes::INSIDE_LINK), 'name'=>$dish_type->ja, 'path'=>sprintf('/admin/dish_menus/%s', $dish_type->key), 'display_order'=>($i+1) ];
        }

        $this->insert_rows('menus', $rows);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
