<?php

namespace Livy;

/**
     * Cyrus HTML element constructor.
     * 
     * Cyrus, named for a reasonably-well-known ancient Roman architect, builds HTML elements
     * for you using an OO approach. Using method chaining, you can build infinitely nested elements with
     * a single (albiet very long) line of code.
     */
    class Cyrus
    {
        private $content = array();
        private $attrs = array();
        private $element = 'div';
        private $parent;
        private $child;
        private $selfClosing = array(
        'img',
        'br',
        'hr',
        'source',
        'input',
        'meta',
        'embed',
        );
        public $key;

        public function __construct()
        {
            $this->key = uniqid('cyrus');
        }

    /**
     * A convenience function. Mostly just a wrapper for `join`, but passing it through
     * this method allows for additional logic (if needed).
     *
     * @param array  $array     The array we want to convert to a string.
     * @param string $delimiter Defaults to a space.
     *
     * @return string $return A string containing all of our joined array items.
     */
    private function collapse($array, $delimiter = ' ')
    {
        return implode($delimiter, $array);
    }

    /**
     * Add an item to the `content` property, to later be constructed.
     *
     * @param string|object $content A string or a Cyrus object.
     * @param string|bool   $key     An optional key to identify this in the array.
     *
     * @return object $this Returned for chaining.
     */
    public function addContent($content, $key = false)
    {
        if (is_a($content, 'Livy\Cyrus')) :
            $key = $content->key;
        $content = $content->construct();
        endif;
        if ($key === false) :
            $this->content[] = $content; else :
            $this->content[$key] = $content;
        endif;

        return $this;
    }

    /**
     * Set the child of this Cyrus, using a key to allow us to add multiple children.
     *
     * @param type $object
     *
     * @return object
     */
    public function setChild($object)
    {
        $this->child[$object->key] = $object;

        return $this;
    }

    /**
     * Set the parent of this Cyrus.
     *
     * @param object $parent The Cyrus that is the parent of this one.
     *
     * @return object
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Create and return a child of the current Cyrus.
     *
     * @return object
     */
    public function openChild($id = false)
    {
        $child = new self();
        $child->setParent($this);
        if ($id) :
            $this->{$id} = $child->key;
        endif;

        return $child;
    }
        /**
         * Re-opens a closed child, identified by $id, and returns it for chaining.
         * 
         * @param string $id 
         * 
         * @return object
         */
        public function nest($id)
        {
            if (!$id) : return $this;
            endif;

            $child = $this->{$id};

            return $this->child[$child];
        }

    /**
     * Close the child and return to the parent Cyrus.
     *
     * @return object $parent Returned for chaining.
     */
    public function closeChild()
    {
        $this->parent->addContent($this);
        $this->parent->setChild($this);

        return $this->parent;
    }

    /**
     * Get the value of the specified attribute.
     *
     * @param string $attr The attribute requested, i.e. `class`, `style`, `data-target`, etc.
     *
     * @return string|array
     */
    public function getAttr($attr)
    {
        if (isset($this->attrs[$attr])) :
            return $this->attrs[$attr]; else :
            return false;
        endif;
    }

    /**
     * Adds the value to an array of values for the attribute. 
     * If bool `true` is passed as the value, the attribute will have no value
     * when constructed.
     * If bool `false` is passed as the value, that attribute (and its content)
     * will be completely removed from the object.
     *
     * @param string      $attr
     * @param string|bool $value
     *
     * @return object
     */
    public function setAttr($attr, $value)
    {
        if ($value === false) :
            unset($this->attrs[$attr]);

        return $this; elseif ($value !== true) :
            $array = $this->getAttr($attr);
        $array[] = $value;
        $value = $array;
        endif;
        $this->attrs[$attr] = $value;

        return $this;
    }

    /**
     * `class` shortcut for setAttr.
     *
     * @param string $class
     *
     * @return object
     */
    public function setClass($class)
    {
        $this->setAttr('class', $class);

        return $this;
    }

    /**
     * `id` shortcut for setAttr.
     *
     * @param string $id
     *
     * @return object
     */
    public function setID($id)
    {
        $this->setAttr('id', $id);

        return $this;
    }

    /**
     * `a` shortcut for setAttr, for links.
     *
     * @param string $class
     *
     * @return object
     */
    public function setURL($url)
    {
        $this->setAttr('href', $url);

        return $this;
    }

    /**
     * `style` shortcut for setAttr.
     *
     * @param string $prop
     * @param string $value
     *
     * @return object
     */
    public function setStyle($prop, $value)
    {
        $style = "$prop: $value;";
        $this->setAttr('style', $style);

        return $this;
    }

    /**
     * Set the element type for this Cyrus.
     * This will overwrite any previous setting.
     *
     * @param string $el
     *
     * @return object
     */
    public function setEl($el)
    {
        $this->element = $el;

        return $this;
    }

    /**
     * Collapse down the `$attr` array property, and run a little logic as
     * we do.
     *
     * @return string
     */
    public function assembleAttrs()
    {
        $attrs = array();
        foreach ($this->attrs as $key => $value) {
            if ($value === true) :
                $attrs[] = $key; else :
                $values = $this->collapse($value);
            $attrs[] = "$key='$values'";
            endif;
        }

        return $this->collapse($attrs);
    }

    /**
     * Assemble all the parts of this Cyrus into an actual HTML element.
     *
     * @return string
     */
    public function construct()
    {
        if (in_array($this->element, $this->selfClosing)) :
            return "<{$this->element} {$this->assembleAttrs()}>"; else :
            return "<{$this->element} {$this->assembleAttrs()}>{$this->collapse($this->content)}</{$this->element}>";
        endif;
    }

    /**
     * Shortcut for echoing the `construct()` output.
     * 
     * @return void
     */
    public function display()
    {
        echo $this->construct();
    }
    }
