<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Tambah kolom status sebagai alias untuk payment_status
            $table->string('status', 20)->after('payment_status')->nullable();
            
            // Buat index untuk performa
            $table->index('status');
        });

        // Copy data dari payment_status ke status
        DB::statement('UPDATE payments SET status = payment_status');
        
        // Buat trigger untuk sinkronisasi otomatis
        DB::unprepared('
            CREATE TRIGGER sync_payment_status_insert 
            BEFORE INSERT ON payments 
            FOR EACH ROW 
            BEGIN 
                IF NEW.status IS NULL AND NEW.payment_status IS NOT NULL THEN
                    SET NEW.status = NEW.payment_status;
                END IF;
                
                IF NEW.payment_status IS NULL AND NEW.status IS NOT NULL THEN
                    SET NEW.payment_status = NEW.status;
                END IF;
            END
        ');
        
        DB::unprepared('
            CREATE TRIGGER sync_payment_status_update 
            BEFORE UPDATE ON payments 
            FOR EACH ROW 
            BEGIN 
                IF NEW.payment_status != OLD.payment_status THEN
                    SET NEW.status = NEW.payment_status;
                END IF;
                
                IF NEW.status != OLD.status THEN
                    SET NEW.payment_status = NEW.status;
                END IF;
            END
        ');
    }

    public function down()
    {
        // Drop triggers
        DB::unprepared('DROP TRIGGER IF EXISTS sync_payment_status_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS sync_payment_status_update');
        
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};