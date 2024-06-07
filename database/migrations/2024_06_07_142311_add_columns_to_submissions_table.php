<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->enum('role', ['Akademisi', 'Peneliti', 'Sektor Swasta', 'Pemerintahan', 'Masyarakat Umum'])->after('user_id');
            $table->text('description')->after('role');
            $table->string('document_path')->after('description');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('document_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('description');
            $table->dropColumn('document_path');
            $table->dropColumn('status');
        });
    }
}

