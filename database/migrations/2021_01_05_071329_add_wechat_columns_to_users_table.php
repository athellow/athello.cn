<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWechatColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('openid')
                    ->after('email')
                    ->default('');
                    
            $table->string('wechat_name')
                    ->after('openid')
                    ->default('');

            $table->string('wechat_avatar')
                    ->after('wechat_name')
                    ->default('');
            
            $table->string('token')
                    ->after('wechat_avatar')
                    ->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('openid', 'wechat_name', 'wechat_avatar', 'token');
        });
    }
}
