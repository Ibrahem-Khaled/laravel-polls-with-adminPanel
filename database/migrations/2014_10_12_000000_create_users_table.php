<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable();
            $table->float('balance')->default(0);
            $table->string('password');
            $table->text('image')->nullable();
            $table->text('identity')->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('is_verified', ['verified', 'unverified'])->default('unverified');
            $table->string('role')->default('user');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert([
            [
                'name' => 'admin',
                'phone' => '1234567890',
                'email' => 'admin@admin.com',
                'password' => bcrypt('12345678'),
                'role' => 'admin',
                'is_verified' => 'verified',
            ],
            [
                'name' => 'Noor qnebi',
                'phone' => '0503889420',
                'email' => 'noorqnebi@gmail.com',
                'password' => bcrypt('12345678'),
                'role' => 'company',
                'is_verified' => 'verified',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
