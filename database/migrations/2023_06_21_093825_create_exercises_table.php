<?php

use App\Models\Course;
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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Course::class)->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->integer('max_score')->default(5);
            $table->dateTime('deadline')->nullable();
            $table->unsignedDouble('deadline_multiplier')->default(1.0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
