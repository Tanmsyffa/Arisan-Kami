<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArisanGroupMembersTable extends Migration
{
    public function up(): void
    {
        Schema::create('arisan_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arisan_group_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arisan_group_members');
    }
}
