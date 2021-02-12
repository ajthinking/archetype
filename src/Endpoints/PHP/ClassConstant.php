<?php

namespace Archetype\Endpoints\PHP;

use App\Helpers\Dev;
use Archetype\Endpoints\EndpointProvider;
use PhpParser\BuilderHelpers;
use PhpParser\BuilderFactory;
use Archetype\Support\Types;
use Illuminate\Support\Arr;
use Exception;
use PhpParser\Node\Const_;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassConst;

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
    public function classConstant($key, $value = Types::NO_VALUE)
    {
        // TODO
        // // remove?
        // if($this->file->directive('remove')) return $this->remove($key);

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
    public function setClassConstant($key, $value = Types::NO_VALUE)
    {
        return $this->set($key, $value);
    }

    protected function add($key, $value)
    {
        // no value but has value from intermidiate add directive?
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

    protected function addToArray($key, $new, $existing = [])
    {
        $new = Arr::wrap($new);

        return $this->set(
            $key,
            array_merge($existing, Arr::wrap($new))
        );
    }

    protected function addToString($key, $new, $existing = '')
    {
        return $this->set(
            $key,
            $existing . $new
        );
    }

    protected function addToNumeric($key, $new, $existing = 0)
    {
        return $this->set(
            $key,
            $existing + $new
        );
    }

    // protected function remove($key)
    // {
    //     // TODO
    // }

    protected function clear($key)
    {
        return $this->setClassConstant($key);
    }

    protected function empty($key)
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

    protected function get($key)
    {
        return $this->canUseReflection() ? $this->getWithReflection($key) : $this->getWithParser($key);
    }

    protected function getWithReflection($name)
    {
        return $this->file->getReflection()->getDefaultProperties()[$name];
    }

    protected function getWithParser($key)
    {
        return $this->file->astQuery()
            ->class()
            ->classConst()->consts
            ->where('name->name', $key)
            ->value
            ->getEvaluated()
            ->first();
    }

    protected function set($key, $value = Types::NO_VALUE)
    {
        $value = $this->prepareValue($value);

        $propertyExists = $this->file->astQuery()
            ->class()
            ->classConst()->consts
            ->where('name->name', $key)
            ->get()->isNotEmpty();

        return $propertyExists ? $this->update($key, $value) : $this->create($key, $value);
    }

    protected function create($key, $value)
    {
        return $this->file->astQuery()
            ->class()
            ->insertStmt($this->makeConstant($key, $value))
            ->commit()
            ->end()
            ->continue();
    }

    protected function update($key, $value)
    {
        return $this->file->astQuery()
            ->class()
            ->classConst()->consts
            ->where('name->name', $key)
            ->replaceProperty(
                'value',
                $value == Types::NO_VALUE ? null : BuilderHelpers::normalizeValue($value)
            )
            ->commit()
            ->end()
            ->continue();
    }

    protected function makeConstant($key, $value)
    {
        if (is_string($value)) {
            $value =  new String_($value);
        } else {
            // TODO
            dd("Can only set string");
        }



        $const = new Const_($key, $value);
        $constant = new ClassConst([$const]);
        return $constant;
    }

    protected function addToUnknownType($key, $value)
    {
        $assumedType = $this->file->directive('assumeType') ?? 'array';
        $addMethod = 'addTo' . $assumedType;
        return $this->$addMethod($key, $value);
    }

    protected function prepareValue($value)
    {
        if ($this->file->directive('assumeType') == 'array') {
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
