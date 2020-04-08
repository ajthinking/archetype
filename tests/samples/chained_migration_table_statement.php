<?php

$table->integer('id');
$table->text('name');
$table->string('description', 255)->nullable()->unique()->references('id')->on('users');