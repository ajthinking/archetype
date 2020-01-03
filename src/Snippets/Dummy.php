<?php

namespace Ajthinking\PHPFileManipulator\Snippets;

class Dummy
{
    public function test() {

        // my alias present in app->aliases:
        // "Me\Package\Facades\PHPFile" => "PHPFile"
        // dd(
        //     app()
        // );

        // // using the the full namespace works
        // dd(
        //     \Me\Package\Facades\PHPFile::load('app/User.php')
        // );

        // BUT trying to use the alias:
        // Error: Class 'PHPFile' not found
        dd(
            \PHPFile::load('app/User.php')
        );        
    }
}
