<?php

namespace PHPFileManipulator\Commands;

use Illuminate\Console\Command;
use PHPFile;

class TypeWriterCommand extends Command
{
    protected $manusscript_path = 'packages/Ajthinking/PHPFileManipulator/docs/manusscripts';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:typewriter {file} {--delay=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used when creating the readme gif. Assumes swedish mac keyboard.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();   
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Sleep 5 seconds by default
        sleep($this->option('delay'));

        

        // Get the file passed in file parameter
        $text = file_get_contents(
            base_path(
                $this->manusscript_path . '/' . $this->argument('file')
            )
        );

        // Replace spaces with tabs (could not replace with \t :/ )
        $text = str_replace('    ', '___TAB___', $text);
    
        $delimiter = 'super_unique_delimiter';
    
        $replacements = [
            "___TAB___" => '___TAB___' ,
            ' ' => '___SPACE___' ,
            '\\' => '___BACKSLASH___',
            "'" => '___SINGLE_QUOTE___',
            '"' => '___DOUBLE_QUOTE___',
            PHP_EOL => '___NEW_LINE___',
        ];
        
        $packed = collect($replacements)->map(function($item, $key) {
            return ['old' => $key, 'new' => $item];
        });
    
        /**
         * Chop it up like this:
         * ['h','e','l','l','o','___SPACE___','w','o','r','l','d']
         */
        $parts = $packed->reduce(function($carry, $item) use($delimiter) {
            return collect($carry)->map(function($string) use($item, $delimiter) {
                return explode($delimiter, 
                    collect(explode($item['old'], $string))
                        ->join($delimiter . $item['new'] . $delimiter)
                );    
            })->flatten();
        }, [$text])->filter();
    
        $parts->each(function($keystroke) use($replacements) {
            if(collect($replacements)->contains($keystroke)) {
                return exec("osascript $this->manusscript_path/keystrokes/$keystroke.scpt");
            }
    
            return exec("osascript $this->manusscript_path/keystrokes/___NON_SPECIAL_CHAR___.scpt '$keystroke'");
        });
    }
}
