<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\AffiliationDetailTypes;

use App\BaseMigration;

class CreateAffiliations extends BaseMigration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliations', function (Blueprint $table) {
            $table->id()->comment('所属ID');
            $table->text('name')->nullable()->comment('名称');
            $table->integer('display_order')->default(1)->comment('表示順');
            $table->enum('detail_type', AffiliationDetailTypes::id_list())->comment('詳細分類');
            $this->build_common_columns($table);
        });

        $rows = [
            [ 'name'=>'法学部', 'display_order'=>1, 'detail_type'=>1 ],
            [ 'name'=>'文学部', 'display_order'=>2, 'detail_type'=>1 ],
            [ 'name'=>'経済学部', 'display_order'=>3, 'detail_type'=>1 ],
            [ 'name'=>'商学部', 'display_order'=>4, 'detail_type'=>1 ],
            [ 'name'=>'社会学部', 'display_order'=>5, 'detail_type'=>1 ],
            [ 'name'=>'政策創造学部', 'display_order'=>6, 'detail_type'=>1 ],
            [ 'name'=>'外国語学部', 'display_order'=>7, 'detail_type'=>1 ],
            [ 'name'=>'人間健康学部', 'display_order'=>8, 'detail_type'=>1 ],
            [ 'name'=>'総合情報学部', 'display_order'=>9, 'detail_type'=>1 ],
            [ 'name'=>'社会安全学部', 'display_order'=>10, 'detail_type'=>1 ],
            [ 'name'=>'システム理工学部', 'display_order'=>11, 'detail_type'=>1 ],
            [ 'name'=>'環境都市工学部', 'display_order'=>12, 'detail_type'=>1 ],
            [ 'name'=>'化学生命工学部', 'display_order'=>13, 'detail_type'=>1 ],
            [ 'name'=>'その他', 'display_order'=>14, 'detail_type'=>2 ],
        ];
        $this->insert_rows('affiliations', $rows);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affiliations');
    }
}
