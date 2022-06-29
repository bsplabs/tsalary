<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeductionListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deduction_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId("employee_id");
            $table->string("item_name")->nullable(false);
            $table->integer("item_value")->default(0);
            $table->enum("item_type", ["revenue", "ot", "other"])->default("other");
            $table->date("item_date");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deduction_lists');
    }
}
