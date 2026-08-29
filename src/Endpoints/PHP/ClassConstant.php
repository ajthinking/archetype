<?php

namespace Archetype\Endpoints\PHP;

use Archetype\Endpoints\EndpointProvider;
use PhpParser\BuilderHelpers;
use Archetype\Support\Types;
use Illuminate\Support\Arr;
use Exception;
use PhpParser\BuilderFactory;

class ClassConstant extends EndpointProvider
{
    /**
     * @example Get class constant
     * @source $file->classConstant('HOME')
     *
     * @example Set class constant
     * @source $file->classConstant('HOME', 'new_home')
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function classConstant(string $key, $value = Types::NO_VALUE)
    {
        // remove?
        if($this->file->directive('remove')) return $this->remove($key);

        // clear?
        if ($this->file->directive('clear')) {
            return $this->clear($key);
        }

        // empty?
        if ($this->file->directive('empty')) {
            return $this->empty($key);
        }

        // add?
        if ($this->file->directive('add')) {
            return $this->add($key, $value);
        }

        // get?
        if ($value === Types::NO_VALUE) {
            return $this->get($key);
        }

        // set!
        return $this->set($key, $value);
    }

    /**
     * @example Explicitly set class constant
     * @source $file->setClassConstant('HOME', 'value')
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function setClassConstant(string $key, $value = Types::NO_VALUE)
    {
        return $this->set($key, $value);
    }

    protected function add(string $key, $value)
    {
        // no value but has value from intermediate add directive?
        if ($value === Types::NO_VALUE && $this->file->directive('addValue')) {
            $value = $this->file->directive('addValue');
        }

        $existing = $this->get($key);

        if (is_array($existing)) {
            return $this->addToArray($key, $value, $existing);
        }

        if (is_string($existing)) {
            return $this->addToString($key, $value, $existing);
        }

        if (is_numeric($existing)) {
            return $this->addToNumeric($key, $value, $existing);
        }

        // Default
        if ($existing === null) {
            return $this->addToUnknownType($key, $value);
        }

        throw new Exception("Using 'add' on an existing type we cant handle! Current support: array/string/numeric/null");
    }

    protected function addToArray(string $key, $new, $existing = [])
    {
        $new = Arr::wrap($new);

        return $this->set(
            $key,
            array_merge($existing, Arr::wrap($new))
        );
    }

    protected function addToString(string $key, $new, $existing = '')
    {
        return $this->set(
            $key,
            $existing . $new
        );
    }

    protected function addToNumeric(string $key, $new, $existing = 0)
    {
        return $this->set(
            $key,
            $existing + $new
        );
    }

    protected function remove(string $key)
    {
		return $this->file->astQuery()
            ->class()
            ->classConst()
            ->where(function ($query) use ($key) {
                return $query->const()
					->where('name->name', $key)
					->isNotEmpty();
            })
            ->remove()
            ->commit()
            ->end()
            ->continue();			
    }

    protected function clear(string $key)
    {
        return $this->setClassConstant($key);
    }

    protected function empty(string $key)
    {
        $value = $this->get($key);

        $defaultMeaningOfEmpty = null;

        if (is_array($value)) {
            return $this->set($key, []);
        }

        if (is_string($value)) {
            return $this->set($key, '');
        }

        return $this->setClassConstant($key, $defaultMeaningOfEmpty);
    }

    protected function get(string $key)
    {
        return $this->canUseReflection() ? $this->getWithReflection($key) : $this->getWithParser($key);
    }

    protected function getWithReflection(string $name)
    {
        return $this->file->getReflection()->getDefaultProperties()[$name];
    }

    protected function getWithParser(string $key)
    {
        return $this->file->astQuery()
            ->class()
            ->classConst()->consts
            ->where('name->name', $key)
            ->value
            ->getEvaluated()
            ->first();
    }

    protected function set(string $key, $value = Types::NO_VALUE)
    {
        $value = $this->prepareValue($value);

        $propertyExists = $this->file->astQuery()
            ->class()
            ->classConst()->consts
            ->where('name->name', $key)
            ->isNotEmpty();

        return $propertyExists ? $this->update($key, $value) : $this->create($key, $value);
    }

    protected function create(string $key, $value)
    {
        return $this->file->astQuery()
            ->class()
            ->insertStmt($this->makeConstant($key, $value))
            ->commit()
            ->end()
            ->continue();
    }

    protected function update(string $key, $value)
    {
        return $this->file->astQuery()
            ->class()
            ->classConst()->consts
            ->where('name->name', $key)
            ->replaceProperty(
                'value',
                $value === Types::NO_VALUE ? null : BuilderHelpers::normalizeValue($value)
            )
            ->commit()
            ->end()
            ->continue();
    }

    protected function makeConstant(string $key, $value)
    {	
		return (new BuilderFactory)->classConst($key, $value)->getNode();
    }

    protected function addToUnknownType(string $key, $value)
    {
        $assumedType = $this->file->directive('assumeType') ?? 'array';
        $addMethod = 'addTo' . $assumedType;
        return $this->$addMethod($key, $value);
    }

    protected function prepareValue($value)
    {
        if ($this->file->directive('assumeType') === 'array') {
            return Arr::wrap($value);
        }

        return $value;
    }

    protected function flag()
    {
        return $this->file->directive('flag') ?? 'public';
    }

    protected function flagCode()
    {
        return [
            'public'    =>  1,
            'protected' =>  2,
            'private'   =>  4,
            'static'    =>  8,
            'abstract'  => 16,
            'final'     => 32,
        ][$this->flag()];
    }
}
