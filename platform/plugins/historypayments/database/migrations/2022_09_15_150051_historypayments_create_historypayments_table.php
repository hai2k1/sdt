<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historypayments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name_bank_account', 255);
            $table->string('bank_id', 255);
            $table->string('bank_name', 255);
            $table->bigInteger('money',);
            $table->string('status', 60)->default('chưa duyệt');
            $table->timestamps();
        });

        Schema::create('historypayments_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('historypayments_id');
            $table->string('name', 255)->nullable();

            $table->primary(['lang_code', 'historypayments_id'], 'historypayments_translations_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historypayments');
        Schema::dropIfExists('historypayments_translations');
    }
};
