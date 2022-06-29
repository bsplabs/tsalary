<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId("employee_id");
            $table->string("month")->nullable(false);
            $table->integer("total_work_day")->default(0);
            $table->integer("revenue_work_time")->default(0);
            $table->integer("revenue_ot_time")->default(0);
            $table->integer("total_deduction")->default(0);
            $table->integer("total_increase")->default(0);
            // $table->integer("total_income")->default(0);
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
        Schema::dropIfExists('incomes');
    }
}
