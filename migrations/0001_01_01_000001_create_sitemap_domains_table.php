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
        Schema::create('sitemap_domains', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\MohammadZarifiyan\LaravelSitemapManager\Models\Sitemap::class)->constrained()->cascadeOnDelete();
            $table->string('host');
            $table->unsignedBigInteger('port')->nullable();
            $table->timestamps();
            $table->index(['host', 'port']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sitemap_domains');
    }
};
