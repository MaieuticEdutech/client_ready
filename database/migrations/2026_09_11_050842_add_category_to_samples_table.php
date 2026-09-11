<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('samples', function (Blueprint $table) {
            // A sub-heading within the service, e.g. "2D Animation" under
            // Animation. Null means the sample sits directly under the service.
            $table->string('category')->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('samples', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
