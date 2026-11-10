<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('enquiries', 'how_found')) {
            return;
        }

        Schema::table('enquiries', function (Blueprint $table) {
            // "How did you find us?" answer from the QR enquiry form (Poultry expo, Reference, Ads...)
            $table->string('how_found', 100)->nullable()->after('source')->index();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('enquiries', 'how_found')) {
            return;
        }

        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropIndex(['how_found']);
            $table->dropColumn('how_found');
        });
    }
};
