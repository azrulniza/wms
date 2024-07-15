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
        Schema::create('table_user_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('user_role')->nullable();
            $table->string('user_name')->nullable();
            $table->string('user_email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('user_password')->nullable();
            $table->rememberToken();
            $table->tinyInteger('active')->default(1);
            $table->string('created_by')->nullable();
            $table->dateTime('created_on')->nullable()->default(now());
            $table->string('changed_by')->nullable();
            $table->dateTime('changed_on')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_user_access');
    }
};
