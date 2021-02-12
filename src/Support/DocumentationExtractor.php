<?php

namespace Archetype\Support;

use ReflectionClass;
use ReflectionMethod;

/**
 * Parse DocBlock tags of classes and methods
 */
class DocumentationExtractor
{
    /**
     * Class reflection object
     * @var object ReflectionClass
     */
    protected $class;
    
    /**
     * Class name to work on
     * @var string
     */
    protected $classname;
    
    /**
     * Contains list of annotation tags to be detected.
     * By default no tags will be detected.
     * @var array|string
     */
    protected $tags;
    
    /**
     * Setup a class to use and tags to detect
     *
     * @uses  setClass(), setTags()
     *
     * @param string $classname
     * @param mixed  $tags
     */
    public function __construct($classname = null, $tags = null)
    {
        if (isset($classname)) {
            $this->setClass($classname);
        }
        if (isset($tags)) {
            $this->setTags($tags);
        }
    }
    
    /**
     * Set the class name we want and load ReflectionClass
     *
     * @param  string $class
     * @return $this
     */
    public function setClass($classname)
    {
        $this->classname = $classname;
        $this->class = new ReflectionClass($classname);
        return $this;
    }

    /**
     * Add annotation tag(s) to be detected
     *
     * @param  array|string $tagname Accept a string or an array of tags
     *                               string '*' act as a wildcard so all tags will be detected
     * @return object       $this
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }
    
    /**
     * Get a methods annotation tags
     *
     * @param  string $method_name
     * @return array
     */
    public function getFromMethod($method_name)
    {
        try {
            $method = new ReflectionMethod($this->classname, $method_name);
        } catch (ReflectionException $e) {
            return [];
        }

        return $this->parse($method->getDocComment());
    }
    
    /**
     * Get all methods annotations tags
     *
     * @return array
     */
    public function getFromAllMethods()
    {
        $a = array();
        
        foreach ($this->class->getMethods() as $m) {
            $comment = $m->getDocComment();
            $a = array_merge($a, [$m->name => $this->parse($comment)]);
        }
        return $a;
    }
    
    /**
     * Get class annotation tags
     *
     * @return array
     */
    public function getFromClass()
    {
        return $this->parse($this->class->getDocComment());
    }
    
    /**
     * Parse a doc comment string
     * with annotions tag previously specified
     *
     * @param  string $string
     * @return array
     */
    public function parse($string)
    {
        //in case we don't have any tag to detect or an empty doc comment, we skip this method
        if (empty($this->tags) || empty($string)) {
            return [];
        }
   
        //check what is the type of $tags (array|string|wildcard)
        if (is_array($this->tags)) {
            $tags = '('.implode('|', $this->tags).')';
        } elseif ($this->tags === '*') {
            $tags = '[a-zA-Z0-9]';
        } else {
            $tags = '('.$this->tags.')';
        }
        
        //find @[tag] [params...]
        $regex = '#\* @(?P<tag>'.$tags.'+)\s+((?P<params>[\s"a-zA-Z0-9\(\)\'\,\-$\\._/-^]+)){1,}#si';
        preg_match_all($regex, $string, $matches, PREG_SET_ORDER);
        
        $final = [];
        
        if (isset($matches)) {
            $i = 0;
            foreach ($matches as $v) {
                $final[$i] = array('tag' => $v['tag'], 'params' => []);

                //detect here if we got a param with quote or not
                //since space is the separator between params, if a param need space(s),
                //it must be surrounded by " to be detected as 1 param
                $regex = '#(("(?<param>([^"]{1,}))")|(?<param2>([^"\s]{1,})))#i';
                preg_match_all($regex, trim($v['params']), $matches_params, PREG_SET_ORDER);

                if (!empty($matches_params)) {
                    foreach ($matches_params as $v) {
                        if (!empty($v['param']) && !isset($v['param2'])) {
                            $final[$i]['params'][] = $v['param'];
                        } elseif (isset($v['param2']) && !empty($v['param2'])) {
                            $final[$i]['params'][] = $v['param2'];
                        }
                    }
                }
                
                ++$i;
            }
        }
        
        return $final;
    }
}
