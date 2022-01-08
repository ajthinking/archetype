<?php

/**
 * Setup any FAKE names we need
 */
use Archetype\Support\FakeName;
use Archetype\Support\FakeName as ___TARGET_CLASS___;

/**
 * This is just a placeholder class where we can add our snippets
 */
class _ extends FakeName
{
    /**
    * Get the associated ___TARGET_IN_DOC_BLOCK___
    */
    public function ___HAS_MANY_METHOD___()
    {
        return $this->hasMany(___TARGET_CLASS___::class);
    }

    /**
    * Get the associated ___TARGET_IN_DOC_BLOCK___
    */
    public function ___HAS_ONE_METHOD___()
    {
        return $this->hasOne(___TARGET_CLASS___::class);
    }

    /**
    * Get the associated ___TARGET_IN_DOC_BLOCK___
    */
    public function ___BELONGS_TO_METHOD___()
    {
        return $this->belongsTo(___TARGET_CLASS___::class);
    }

    /**
    * Get the associated ___TARGET_IN_DOC_BLOCK___
    */
    public function ___BELONGS_TO_MANY_METHOD___()
    {
        return $this->belongsToMany(___TARGET_CLASS___::class);
    }
}
