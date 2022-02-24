<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('type_id');
            $table->rememberToken();
            $table->timestamps();

            //test
            $table->boolean('verify_email')->comment('認証済:1、未認証:0')->default(0);
            $table->string('verify_token')->comment('認証用及びリマインダートークン')->nullable();
            $table->timestamp('verify_date')->comment('トークン発行日時')->nullable();
            $table->string('verify_email_address')->comment('仮登録メールアドレス')->nullable()->unique();
            //

            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
