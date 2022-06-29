<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('working_details', function (Blueprint $table) {
            $table->id();
            $table->string("working_date")->nullable(false);
            $table->integer("total")->default(0);
            $table->integer("work_time")->default(0);
            $table->integer("ot_time")->default(0);
            $table->integer("revenue_work_time")->default(0);
            $table->integer("revenue_ot_time")->default(0);
            $table->string("shift_start")->nullable(false);
            $table->string("shift_end")->nullable(false);
            $table->foreignId("employee_id");
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
        Schema::dropIfExists('working_details');
    }
}
