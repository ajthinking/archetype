<?php

$table->string('id', 255)->nullable()->unique()->references('id')->on('users');