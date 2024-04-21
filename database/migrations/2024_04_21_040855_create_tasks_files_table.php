<?php

use App\Models\Task;
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
        Schema::create('tasks_files', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Task::class);
            $table->string('original_name');
            $table->string('generated_name')->unique();
            $table->string('extension', 10);
            $table->integer('size');
            $table->timestamps();
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks_files');
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('file')->nullable();
        });
    }
};
