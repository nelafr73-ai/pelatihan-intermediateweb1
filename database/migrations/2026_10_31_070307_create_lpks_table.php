
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lpks', function (Blueprint $table) {
            $table->id();

            // Informasi utama
            $table->string('name')->unique();
            $table->string('domain')->nullable()->unique();
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);

            // Identitas LPK
            $table->string('lpk_name');
            $table->string('legal_name')->nullable();
            $table->string('nib')->nullable()->unique();
            $table->text('description')->nullable();

            // Kontak
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('tiktok')->nullable();

            // Lokasi
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Media dan fasilitas
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->json('facilities')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lpks');
    }
};
