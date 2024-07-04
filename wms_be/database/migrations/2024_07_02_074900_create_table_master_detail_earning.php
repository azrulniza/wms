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
        Schema::create('tbl_master_detail_earning', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('employee_id')->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_acc', 100)->nullable();
            $table->double('basic_salary')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->string('created_by', 255)->nullable();
            $table->timestamp('created_on')->useCurrent();
            $table->string('changed_by', 255)->nullable();
            $table->timestamp('changed_on')->nullable()->useCurrentOnUpdate();
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->engine = 'MyISAM';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_master_detail_earning');
    }
};
