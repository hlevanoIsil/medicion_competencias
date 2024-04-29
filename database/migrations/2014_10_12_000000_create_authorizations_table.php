<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authorizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('role_id');
            $table->integer('menu_item_id');
            $table->tinyInteger('enabled')->default(1);
            $table->timestamps();
        });

        DB::table('authorizations')->insert(
            array(
                [
                    'role_id' => 1,
                    'menu_item_id' => 1
                ],
                [
                    'role_id' => 2,
                    'menu_item_id' => 1
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
        Schema::dropIfExists('authorizations');
    }
}
