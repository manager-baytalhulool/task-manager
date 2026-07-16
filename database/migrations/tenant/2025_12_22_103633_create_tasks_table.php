<?php

use App\Enums\TaskStatusEnum;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string("description", 1024);
            $table->foreignId("created_by")->constrained("users");
            $table->foreignId("assignee_id")->constrained("users");
            $table->foreignId("project_id")->constrained("projects");
            $table->string("status")->default(TaskStatusEnum::CREATED->value);
            $table->boolean("is_paid")->nullable();
            $table->timestamp("started_at")->nullable();
            $table->timestamp("completed_at")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
