<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string("code")->nullable();
            $table->string("enroll_no")->nullable();
            $table->string("name_th");
            $table->string("name_en")->nullable()->default(null);
            $table->enum("type", ["permanent", "temporary"])->default("permanent");
            $table->string("bank_name")->nullable()->default(null);
            $table->string("bank_account_number")->nullable()->default(null);
            $table->string("tel")->nullable()->default(null);
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
        Schema::dropIfExists('employees');
    }
}
