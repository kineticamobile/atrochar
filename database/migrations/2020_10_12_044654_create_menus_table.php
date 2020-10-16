<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->default("");
            $table->string('href')->default(""); // http(s) or route name
            $table->string('icon')->default(""); // eg: laptop => <i class="fa fa-laptop"></i>
            $table->boolean('newwindow')->default(false); // open on new window
            $table->boolean('iframe')->default(false); // open inside iframe
            $table->foreignId('menu_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->string('permission')->default("");
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
        Schema::dropIfExists('menus');
    }
}
