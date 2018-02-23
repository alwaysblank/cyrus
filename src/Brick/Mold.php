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
    public function attach(Cyrus $Cyrus) : self
    {
        $Cyrus->handleAttach($this);
        return $this;
    }

    /**
     * Generates an unique ID for a brick.
     *
     * @return string
     */
    protected function generateUID() : string
    {
        return uniqid();
    }

    /**
     * Arbitrarily set a property.
     *
     * Returns the value of the current property if no value is passed;
     * returns the new value if one is.
     *
     * @param $name
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
     * Get uid.
     *
     * (uid cannot be set.)
     *
     * @see Mold::prop()
     * @return string
     */
    public function uid() : string
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
    public function parent(string $value = null) : string
    {
        return $this->prop('parent', $value);
    }

    /**
     * Get/set class.
     *
     * Unlike other Mold::prop() variants, this appends instead of overwrites.
     *
     * @see Mold::prop()
     * @param array|null $value
     * @return array
     */
    public function class(array $value = null) : array
    {
        return $this->prop(
            'class',
            array_merge($this->prop('class'), $value)
        );
    }

    /**
     * Get/set attributes.
     *
     * Unlike other Mold::prop() variants, this appends instead of overwrites.
     *
     * @see Mold::prop()
     * @param array|null $value
     * @return array
     */
    public function attributes(array $value = null) : array
    {
        return $this->prop(
            'attributes',
            array_merge($this->prop('attributes'), $value)
        );
    }

    /**
     * Get/set order.
     *
     * @see Mold::prop()
     * @param int|null $value
     * @return int
     */
    public function order(int $value = null) : int
    {
        return $this->prop('order', $value);
    }
}