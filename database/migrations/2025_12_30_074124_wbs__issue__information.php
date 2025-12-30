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
        Schema::create('wbs_i_issue_information', function (Blueprint $table) {
            $table->uuid('issue_id');
            $table->string('concern_title');
            $table->longText('concern_description');
            $table->unsignedBigInteger('assignee_id');
            $table->string('status_priority');
            $table->date('date_issues')->nullable();
            $table->date('date_start')->nullable();
            $table->date('date_hold')->nullable();
            $table->date('date_complete')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by');
            $table->date('date_created');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
