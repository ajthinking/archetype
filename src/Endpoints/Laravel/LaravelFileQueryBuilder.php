<?php

namespace Archetype\Endpoints\Laravel;

use Archetype\Endpoints\PHP\PHPFileQueryBuilder;
use Archetype\Facades\LaravelFile;

class LaravelFileQueryBuilder extends PHPFileQueryBuilder
{
    /**
     * @example Get the User file
     * @source LaravelFile::user()
     */
    public function user()
    {
        // check if app/Models/User exists
        if ($this->file->inputDriver()->fileExists('app/Models/User.php')) {
            return LaravelFile::load('app/Models/User.php');
        };

        // check if app/User exists
        if ($this->file->inputDriver()->fileExists('app/User.php')) {
            return LaravelFile::load('app/User.php');
        };

        // else look anywhere in app
        return $this->in('app')->where('className', 'User')->get()->first();
    }

    /**
     * @example Query migrations
     * @source LaravelFile::migrations()
     */
    public function migrations(): self
    {
        return $this->in('database/migrations');
    }

    /**
     * @example Query models
     * @source LaravelFile::models()
     */
    public function models(): self
    {
        $this->instanceof('Illuminate\Database\Eloquent\Model');
        $this->isNotAbstract();

        return $this;
    }

    /**
     * @example Query models
     * @source LaravelFile::models()
     */
    public function exceptUser(): self
    {
        $this->isNotUser();

        return $this;
    }

    /**
     * @example Query controllers
     * @source LaravelFile::controllers()
     */
    public function controllers(): self
    {
        return $this->instanceof('Illuminate\Routing\Controller');
    }

    /**
     * @example Query serviceProviders
     * @source LaravelFile::serviceProviders()
     */
    public function serviceProviders(): self
    {
        return $this->instanceof('Illuminate\Support\ServiceProvider');
    }

    protected function instanceof($class): self
    {
        // Ensure we are in a directory context - default to base path
        if (!isset($this->baseDir)) {
            $this->in('');
        }

        $this->result = $this->result->filter(function ($file) use ($class) {
            $reflection = $file->getReflection();
            return $reflection && $reflection->isSubclassOf($class);
        });

        return $this;
    }

    protected function isNotAbstract(): self
    {
        $this->result = $this->result->filter(function ($file) {
            $reflection = $file->getReflection();
            return $reflection && !$reflection->isAbstract();
        });

        return $this;
    }

    protected function isNotUser(): self
    {
        $this->result = $this->result->filter(function ($file) {
            return $file->className() != 'User';
        });

        return $this;
    }	
}
