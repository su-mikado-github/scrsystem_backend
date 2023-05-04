<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\BaseMigration;

class CreateAffiliationDetails extends BaseMigration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliation_details', function (Blueprint $table) {
            $table->id()->comment('所属詳細ID');
            $table->integer('detail_type')->comment('詳細分類');
            $table->text('name')->nullable()->comment('名称');
            $table->integer('display_order')->comment('表示順');
            $this->build_common_columns($table);
        });

        $rows = [
            //その他以外
            [ 'detail_type'=>1, 'name'=>'サッカー（トップ）', 'display_order'=>1 ],
            [ 'detail_type'=>1, 'name'=>'サッカー（ウルトラス）', 'display_order'=>2 ],
            [ 'detail_type'=>1, 'name'=>'サッカー（レッドグロウ）', 'display_order'=>3 ],
            [ 'detail_type'=>1, 'name'=>'サッカー（ソレオ）', 'display_order'=>4 ],
            [ 'detail_type'=>1, 'name'=>'サッカー（FC）', 'display_order'=>5 ],
            [ 'detail_type'=>1, 'name'=>'サッカー（女子）', 'display_order'=>6 ],
            [ 'detail_type'=>1, 'name'=>'その他部活動（球技）', 'display_order'=>7 ],
            [ 'detail_type'=>1, 'name'=>'その他部活動（陸上）', 'display_order'=>8 ],
            [ 'detail_type'=>1, 'name'=>'その他部活動（体操）', 'display_order'=>9 ],
            [ 'detail_type'=>1, 'name'=>'その他部活動（水泳）', 'display_order'=>10 ],
            [ 'detail_type'=>1, 'name'=>'その他部活動（格闘技）', 'display_order'=>11 ],
            [ 'detail_type'=>1, 'name'=>'その他部活動（武道）', 'display_order'=>12 ],
            [ 'detail_type'=>1, 'name'=>'その他部活動（自転車）', 'display_order'=>13 ],
            [ 'detail_type'=>1, 'name'=>'その他部活動（射的）', 'display_order'=>14 ],
            [ 'detail_type'=>1, 'name'=>'その他部活動（パワースポーツ）', 'display_order'=>15 ],
            [ 'detail_type'=>1, 'name'=>'その他部活動（ウインタースポーツ）', 'display_order'=>16 ],
            [ 'detail_type'=>1, 'name'=>'その他部活動（その他）', 'display_order'=>17 ],
            [ 'detail_type'=>1, 'name'=>'所属無し', 'display_order'=>18 ],

            //その他
            [ 'detail_type'=>2, 'name'=>'一般', 'display_order'=>1 ],
            [ 'detail_type'=>2, 'name'=>'小学生', 'display_order'=>2 ],
            [ 'detail_type'=>2, 'name'=>'中学生', 'display_order'=>3 ],
            [ 'detail_type'=>2, 'name'=>'高校生', 'display_order'=>4 ],
            [ 'detail_type'=>2, 'name'=>'大学生', 'display_order'=>5 ],
            [ 'detail_type'=>2, 'name'=>'ユース', 'display_order'=>6 ],
            [ 'detail_type'=>2, 'name'=>'プロ', 'display_order'=>7 ],
        ];
        $this->insert_rows('affiliation_details', $rows);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affiliation_details');
    }
}
