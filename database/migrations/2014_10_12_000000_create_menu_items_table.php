<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('path')->default(null);
            $table->string('to')->default(null);
            $table->string('subheader')->nullable()->default(null);
            $table->string('icon')->nullable()->default(null);
            $table->string('class')->nullable()->default(null);
            $table->integer('sortNum')->nullable();
            $table->integer('fatherId')->default(0);
            $table->integer('isItem')->default(1);
            $table->integer('enabled')->default(1);
            $table->string('description')->nullable();
            $table->tinyInteger('dashShow')->default(0);
            $table->timestamps();
        });


        DB::table('menu_items')->insert(
            array(
                [
                    'title' => 'Inicio',
                    'path' => '/home',
                    'to' => 'home',
                    'subheader' => null,
                    'icon' => 'mdiHomeOutline',
                    'sortNum' => 1,
                    'fatherId' => 0,
                    'isItem' => 1
                ]
            )
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
}
