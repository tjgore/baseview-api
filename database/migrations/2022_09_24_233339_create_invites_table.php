<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\School;
use App\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invites', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(School::class);
            $table->foreign('school_id')->references('id')->on('schools')->constrained()->onDelete('cascade');
            $table->foreignIdFor(Role::class);
            $table->foreign('role_id')->references('id')->on('roles')->constrained()->onDelete('cascade');
            $table->foreignId('created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->string('token')->unique();
            $table->boolean('accepted')->default(false);
            $table->timestamp('expires_at', 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invites');
    }
};
