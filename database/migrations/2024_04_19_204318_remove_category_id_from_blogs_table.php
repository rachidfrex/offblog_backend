<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCategoryIdFromBlogsTable extends Migration
{
    public function up()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropForeign(['category_id']); // drop the foreign key constraint
            $table->dropColumn('category_id'); // then drop the column
        });
    }

    public function down()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->integer('category_id')->after('user_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }
}