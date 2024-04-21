<?php

use App\Models\TestStatus;
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
        Schema::create('test_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('label')->nullable();
            $table->timestamps();
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('test_status');
            $table->foreignIdFor(TestStatus::class)->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeignIdFor(TestStatus::class);
            $table->dropColumn('test_status_id');
            $table->string('test_status')->default('awaiting');
        });
        Schema::dropIfExists('test_statuses');
    }
};
