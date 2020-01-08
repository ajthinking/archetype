<?php

namespace Ajthinking\PHPFileManipulator\Snippets;

/**
 * Setup any REAL names we need
 */
use PHPFile;

/**
 * Setup any FAKE names we need
 */
use Ajthinking\PHPFileManipulator\Support\FakeName; 
use Ajthinking\PHPFileManipulator\Support\FakeName as ___TARGET_CLASS___;

/**
 * This is just a placeholder class where we can add our snippets
 */
class _ extends FakeName
{
    protected $___PROTECTED_ARRAY_PROPERY___ = [];
    protected $fillable = [];
    protected $hidden = [];
    protected $casts = [];


    public function ___HAS_MANY_METHOD___()
    {
        return $this->hasMany(___TARGET_CLASS___::class);
    }

    public function ___BELONGS_TO_MANY_METHOD___()
    {
        return $this->belongsToMany(___TARGET_CLASS___::class);
    }    
}