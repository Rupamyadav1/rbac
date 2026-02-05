<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('user_type', ['user', 'admin']);
            $table->unsignedBigInteger('user_id');
            $table->string('otp_code', 255);
            $table->enum('status', ['pending', 'verified', 'expired'])->default('pending');
            $table->integer('attempts')->default(0);
            $table->dateTime('expires_at');
            $table->timestamps();

            $table->index(['user_type', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};

?>