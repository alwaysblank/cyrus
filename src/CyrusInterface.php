<?php 
namespace Livy;

interface CyrusInterface
{

    /**
     * Checks whether or not this string looks the way it should.
     * Returns the string if it passes, and bool false if it doesn't.
     *
     * @param string $string
     * 
     * @return string|bool
     */
    public function safeString($string);

    /**
     * Add an item to the `content` property, to later be constructed.
     *
     * @param string|object $content A string or a Cyrus object.
     * @param string|bool   $key     An optional key to identify this in the array.
     *
     * @return object $this Returned for chaining.
     */
    public function addContent($content, $key = false);

    /**
     * Set the child of this Cyrus, using a key to allow us to add multiple children.
     *
     * @param type $object
     *
     * @return object
     */
    public function setChild($object);

    /**
     * Get the parent of the passed Cyrus.
     *
     * @param type|null $object
     *
     * @return type
     */
    public function getParent($object = null);

    /**
     * Set the parent of this Cyrus.
     *
     * @param object $parent The Cyrus that is the parent of this one.
     *
     * @return object
     */
    public function setParent($parent);

    /**
     * Create and return a child of the current Cyrus.
     *
     * @return object
     */
    public function openChild($id = false);

    /**
     * Re-opens a closed child, identified by $id, and returns it for chaining.
     * 
     * @param string $id
     * 
     * @return object
     */
    public function nest($id);

    /**
     * Close the child and return to the parent Cyrus.
     *
     * @return object $parent Returned for chaining.
     */
    public function closeChild();

    /**
     * Get the value of the specified attribute.
     *
     * @param string $attr The attribute requested, i.e. `class`, `style`, `data-target`, etc.
     *
     * @return string|array
     */
    public function getAttr($attr);

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
    public function setAttr($attr, $value);

    /**
     * `class` shortcut for setAttr.
     *
     * @param string $class
     *
     * @return object
     */
    public function setClass($class);

    /**
     * `id` shortcut for setAttr.
     *
     * @param string $id
     *
     * @return object
     */
    public function setID($id);

    /**
     * `a` shortcut for setAttr, for links.
     *
     * @param string $class
     *
     * @return object
     */
    public function setURL($url);

    /**
     * `style` shortcut for setAttr.
     *
     * @param string $prop
     * @param string $value
     *
     * @return object
     */
    public function setStyle($prop, $value);

    /**
     * Set the element type for this Cyrus.
     * This will overwrite any previous setting.
     *
     * @param string $el
     *
     * @return object
     */
    public function setEl($el);

    /**
     * Collapse down the `$attr` array property, and run a little logic as
     * we do.
     *
     * @return string
     */
    public function assembleAttrs();

    /**
     * Assemble all the parts of this Cyrus into an actual HTML element.
     *
     * @return string
     */
    public function construct();

    /**
     * Shortcut for echoing the `construct()` output.
     * 
     * @return void
     */
    public function display();
}