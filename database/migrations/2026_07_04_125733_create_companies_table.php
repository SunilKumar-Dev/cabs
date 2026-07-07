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
          Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->uuid('company_uuid')->unique()->nullable();
                // Basic Info
                $table->string('company_name');
                $table->string('company_slug')->unique()->nullable();
                $table->text('company_description')->nullable();

                // Business Details
                $table->string('company_pan_no')->nullable()->unique();
                $table->string('company_gst_no')->nullable()->unique();
                $table->string('company_registration_no')->nullable();

                // Contact Details
                $table->string('company_email')->nullable();
                $table->string('company_phone', 20)->nullable();
                $table->string('company_website')->nullable();

                // Address
                $table->string('company_address_line_1')->nullable();
                $table->string('company_address_line_2')->nullable();
                $table->string('company_city')->nullable();
                $table->string('company_state')->nullable();
                $table->string('company_country')->default('India');
                $table->string('company_postal_code')->nullable();

                // Branding
                $table->string('company_logo')->nullable();
                $table->string('company_favicon')->nullable();
                // Ownership
                
                $table->foreignUuid('created_by_uuid')
                    ->constrained('users', 'uuid')->nullable();
               
                // Status
                $table->boolean('status')->default(true);

                $table->timestamps();
                $table->softDeletes();
            }); 

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
