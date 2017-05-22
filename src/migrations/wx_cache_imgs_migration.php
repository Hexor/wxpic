<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxCacheImgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('wx_cache_imgs');
        Schema::create('wx_cache_imgs', function (Blueprint $table) {
            $table->string('local_url_md5')->comment('本地服务器图片完整 url md5');
            $table->string('wechat_url_md5')->comment('微信服务器图片完整 url md5');
            $table->string('local_path')->comment('服务器本地文件路径');
            $table->string('local_full_url')->comment('本地服务器图片完整 url');
            $table->string('wechat_full_url')->comment('微信服务器图片完整 url');
            $table->unique(['local_url_md5', 'wechat_url_md5']);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wx_cache_imgs');
    }
}
