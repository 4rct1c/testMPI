<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_records', function (Blueprint $table)
        {
            $table->bigIncrements('id');

            $table->timestampsTz();

            $table->string('channel');

            $table->string('level');

            $table->text('message');

            $table->jsonb('context')->index()->nullable();

            $table->text('extra')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_records');
    }
};
