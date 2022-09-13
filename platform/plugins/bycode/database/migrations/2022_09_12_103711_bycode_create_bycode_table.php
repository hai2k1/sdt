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
        Schema::create('bycodes', function (Blueprint $table) {
            $table->id();
            $table->string('name_app', 255);
            $table->integer('phone_number')->nullable();
            $table->string('code', 255)->nullable();
            $table->string('session', 255);
            $table->integer('id_user')->nullable();
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('bycodes_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('bycodes_id');
            $table->string('name', 255)->nullable();

            $table->primary(['lang_code', 'bycodes_id'], 'bycodes_translations_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bycodes');
        Schema::dropIfExists('bycodes_translations');
    }
};
