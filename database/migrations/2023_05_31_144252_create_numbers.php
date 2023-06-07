<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\BaseMigration;

class CreateNumbers extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('numbers', function (Blueprint $table) {
            $table->id();
            $table->string('key', 1024)->comment('番号識別キー');
            $table->bigInteger('value')->comment('値');
            $this->build_common_columns($table);
        });

        for ($i=0; $i<10; $i++) {
            $this->insert_row('numbers', [ 'key'=>'10', 'value'=>($i+1) ]);
        }
        for ($i=0; $i<100; $i++) {
            $this->insert_row('numbers', [ 'key'=>'100', 'value'=>($i+1) ]);
        }
        for ($i=0; $i<1000; $i++) {
            $this->insert_row('numbers', [ 'key'=>'1000', 'value'=>($i+1) ]);
        }
        for ($i=0; $i<10000; $i++) {
            $this->insert_row('numbers', [ 'key'=>'10000', 'value'=>($i+1) ]);
        }
        for ($i=0; $i<60; $i++) {
            $this->insert_row('numbers', [ 'key'=>'60', 'value'=>($i+1) ]);
        }
        for ($i=0; $i<24; $i++) {
            $this->insert_row('numbers', [ 'key'=>'24', 'value'=>($i+1) ]);
        }
        for ($i=0; $i<12; $i++) {
            $this->insert_row('numbers', [ 'key'=>'12', 'value'=>($i+1) ]);
        }
        for ($i=0; $i<10; $i++) {
            $this->insert_row('numbers', [ 'key'=>'0-9', 'value'=>$i ]);
        }
        for ($i=0; $i<100; $i++) {
            $this->insert_row('numbers', [ 'key'=>'0-99', 'value'=>$i ]);
        }
        for ($i=0; $i<1000; $i++) {
            $this->insert_row('numbers', [ 'key'=>'0-999', 'value'=>$i ]);
        }
        for ($i=0; $i<10000; $i++) {
            $this->insert_row('numbers', [ 'key'=>'0-9999', 'value'=>$i ]);
        }
        for ($i=0; $i<24; $i++) {
            $this->insert_row('numbers', [ 'key'=>'hours', 'value'=>$i ]);
        }
        for ($i=0; $i<60; $i++) {
            $this->insert_row('numbers', [ 'key'=>'minutes', 'value'=>$i ]);
        }
        for ($i=0; $i<60; $i++) {
            $this->insert_row('numbers', [ 'key'=>'seconds', 'value'=>$i ]);
        }
        for ($i=0; $i<60*24; $i++) {
            $this->insert_row('numbers', [ 'key'=>'minute_of_day', 'value'=>$i ]);
        }
        for ($i=0; $i<60*60; $i++) {
            $this->insert_row('numbers', [ 'key'=>'second_of_hour', 'value'=>$i ]);
        }
        for ($i=0; $i<60*60*24; $i++) {
            $this->insert_row('numbers', [ 'key'=>'second_of_day', 'value'=>$i ]);
        }
        for ($i=0; $i<12; $i++) {
            $this->insert_row('numbers', [ 'key'=>'months', 'value'=>($i+1) ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('numbers');
    }
}
