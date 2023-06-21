<?php

use App\Models\Exercise;
use App\Models\User;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Exercise::class)->constrained()->cascadeOnDelete();

            $table->dateTime('first_uploaded_at')->nullable();
            $table->dateTime('last_uploaded_at')->nullable();

            $table->string('test_status')->default('awaiting');
            $table->unsignedDouble('mark')->nullable();
            $table->string('file');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
