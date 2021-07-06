<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email', 45)->nullable(false);
            $table->string('password', 191)->nullable();
            // $table->string('given_name', 45)->nullable();
            // $table->string('middle_name', 45)->nullable();
            // $table->string('last_name', 45)->nullable();
            // $table->string('suffix', 45)->nullable();
            // $table->unsignedBigInteger('sys_role_id')->nullable(false);
            $table->dateTime('last_login')->nullable();
            // $table->boolean('is_verify')->nullable()->default(0)->comment('0 = unverify, 1 = verify');
            // $table->boolean('is_firsttime')->nullable()->default(1)->comment('1=firstime, 0=repeater');
            $table->boolean('status')->nullable()->default(1)->comment('1=active, 0=suspend');
            $table->rememberToken();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->boolean('enabled')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_accounts');
    }
}
