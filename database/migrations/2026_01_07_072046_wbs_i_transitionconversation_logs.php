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
        Schema::create('wbs_i_transitionconversation_logs', function (Blueprint $table) {
            $table->id('conversation_id');
            $table->bigInteger('customer_psid');
            $table->string('conversation_status');
            $table->date('conversation_updated_from');
            $table->date('conversation_updated_to');
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
