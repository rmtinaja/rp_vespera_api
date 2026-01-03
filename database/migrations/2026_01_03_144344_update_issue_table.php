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
        Schema::table('wbs_i_issue_information', function (Blueprint $table) {
            $table->string('ticket_no')->nullable();
            $table->string('ticket_status')->nullable()->default('Open');
            $table->dateTime('date_resume')->nullable();
            $table->dateTime('date_issues')->change()->nullable();
            $table->dateTime('date_start')->change()->nullable();
            $table->dateTime('date_hold')->change()->nullable();
            $table->dateTime('date_complete')->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wbs_i_issue_information', function (Blueprint $table) {
            //
        });
    }
};
