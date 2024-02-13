<?php

use App\Models\Group;
use App\Models\UserType;
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->foreignIdFor(Group::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(UserType::class, 'type')->nullable()->constrained('user_types')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_group_id_foreign');
            $table->dropColumn('group_id');
            $table->dropForeign('users_type_foreign');
            $table->dropColumn('type');
        });
    }
};
