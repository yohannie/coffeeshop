<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('user_id');
            $table->string('email')->nullable()->after('customer_name');
            $table->string('phone')->nullable()->after('email');
            $table->string('delivery_address')->nullable()->after('phone');
            $table->boolean('is_guest')->default(false)->after('notes');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name',
                'email',
                'phone',
                'delivery_address',
                'is_guest'
            ]);
        });
    }
}; 