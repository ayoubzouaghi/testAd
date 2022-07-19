<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActiveDirectoryParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('active_directory_parameters', function (Blueprint $table) {
            $table->id();
            $table->longText('hosts');
            $table->longText('port');
            $table->longText('username');
            $table->longText('password');
            $table->longText('dc');
            $table->boolean('use_ssl')->default(false);
            $table->unsignedBigInteger('company_id');
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
        Schema::dropIfExists('active_directory_parameters');
    }
}
