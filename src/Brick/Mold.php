<?php namespace Livy\Cyrus\Brick;

use Livy\Cyrus\Cyrus;

/**
 * Class Mold
 * This is the class from which all Bricks must inherit.
 *
 * @package Livy\Cyrus\Brick
 */
abstract class Mold
{
    /**
     * String that is the tag for an element, i.e. `div`.
     */
    const TAG = null;

    /**
     * Whether or not this element can have children.
     * In practice, this indicates whether or not it needs a closing tag
     * (`true`) or is self-closing (`false`).
     */
    const PARENTAL = true;

    protected $uid;
    protected $parent;
    protected $class;
    protected $attributes;
    protected $order;

    protected $arguments;

    final public function __construct(...$args)
    {
        $this->arguments = $args;

        /**
         * This allows inheriting functions a way to hook into the constructor without
         * overwriting it.
         */
        $this->bricklayer();

        $this->uid = $this->generateUID();

        return $this;
    }

    /**
     * By default this does nothing; It's essentially a placeholder.
     */
    protected function bricklayer()
    {
        return;
    }

    /**
     * @param Cyrus $Cyrus
     * @return Mold
     */
    public function attach(Cyrus $Cyrus): self
    {
        $Cyrus->handleAttach($this);
        return $this;
    }

    /**
     * Generates an unique ID for a brick.
     *
     * @return string
     */
    protected function generateUID(): string
    {
        return uniqid();
    }

    /**
     * Arbitrarily set a property.
     *
     * Returns the value of the current property if no value is passed;
     * returns the new value if one is.
     *
     * @param      $name
     * @param null $value
     * @return mixed
     */
    protected function prop($name, $value = null)
    {
        if (null === $value) {
            return $this->{$name};
        } else {
            return $this->{$name} = $value;
        }
    }

    /**
     * Appends two arrays, if the first parameter is an array.
     *
     * Primarily useful for dealing with modifying Brick props that store data in arrays.
     *
     * @param mixed $maybeArray
     * @param array $base
     * @return array
     */
    protected function appendIfArray($maybeArray, array $base): array
    {
        if (is_array($maybeArray)) {
            return array_merge($base, $maybeArray);
        } else {
            return null;
        }
    }

    /**
     * Get uid.
     *
     * (uid cannot be set.)
     *
     * @see Mold::prop()
     * @return string
     */
    public function uid(): string
    {
        return $this->prop('uid');
    }

    /**
     * Get/set parent.
     *
     * @see Mold::prop()
     * @param string|null $value
     * @return string
     */
    public function parent(string $value = null): string
    {
        return $this->prop('parent', $value);
    }

    /**
     * Get/set class.
     *
     * New classes can be added as either strings or arrays.
     *
     * Unlike other Mold::prop() variants, this appends instead of overwrites.
     *
     * @see Mold::prop()
     * @param array|string|null $class
     * @return array
     */
    public function class($class = null): array
    {
        if (is_string($class)) {
            $validatedClass = [$class];
        } elseif (is_array($class)) {
            $validatedClass = $class;
        } else {
            $validatedClass = null;
        }
        return $this->prop(
            'class',
            $this->appendIfArray($validatedClass, $this->prop('class'))
        );
    }

    /**
     * Get/set HTML attributes.
     *
     * Unlike other Mold::prop() variants, this appends instead of overwrites.
     *
     * @see Mold::prop()
     * @param array|null $attributes
     * @return array
     */
    public function attributes(array $attributes = null): array
    {
        return $this->prop(
            'attributes',
            $this->appendIfArray($attributes, $this->prop('attributes'))
        );
    }

    /**
     * Get/set order.
     *
     * @see Mold::prop()
     * @param int|null $value
     * @return int
     */
    public function order(int $value = null): int
    {
        return $this->prop('order', $value);
    }
}