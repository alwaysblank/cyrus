<?php 
namespace Livy;

interface CyrusInterface
{

    /**
     * Returns the values of properties. Does a simple check using
     * `safeString` to reject bad requests. Returned value is whatever
     * is in that property, so it can vary.
     * 
     * @param string $prop
     * 
     * @return mixed
     */    
    public function get($prop);

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
     * @param string|bool   $key     An optional key to identify this in the 
     * array.
     *
     * @return object $this Returned for chaining.
     */
    public function addContent($content, $key = false);

    /**
     * An alias for addContent, so that we can access it through the __call
     * magic method shortcuts.
     * 
     * @param string|object $content 
     * @param string|bool $key 
     * 
     * @return object
     * 
     * @see Cyrus::addContent()     Aliased by this method.
     */
    public function setContent($content, $key = false);

    /**
     * Return the child as identified by $key.
     * 
     * @param string $key 
     * 
     * @return $object
     */
    public function getChild($key);

    /**
     * Set the child of this Cyrus, using a key to allow us to add multiple 
     * children.
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
     * @param string $id    An optional ID to identify this child.
     * @return object       Cyrus object for chaining
     */
    public function openChild($id = false);

    /**
     * Opens a child element on the current element. 
     * 
     * Alias of Cyrus::openChild(), for convenience.
     * 
     * @param string $id    An optional ID to identify this child.
     * @return object       Cyrus object for chaining.
     * @see Cyrus::openChild()  Aliased by this method.
     */
    // public function o($id = false);

    /**
     * Close the child and return to the parent Cyrus.
     *
     * @return object $parent Returned for chaining.
     */
    public function closeChild();

    /**
     * Close the child and return to the parent Cyrus.
     * 
     * Alias of Cyrus::openChild(), for convenience.
     * 
     * @return object   Cyrus object for chaining
     * @see Cyrus::closeChild()     Aliased by this method.
     */
    // public function c();

    /**
     * Close a number of nested children equal to `$levels`. 
     * 
     * @param int $levels 
     * @return object
     */
    public function closeChildren( $levels );

    /**
     * Close all open children by looping through them until it reaches
     * an object with no `parent` property.
     * 
     * @return object
     */
    public function closeAll();

    /**
     * Closes all open children in the chain.
     * 
     * Alias of Cyrus::closeAll() for convenience.
     * 
     * @return object   Cyrus object for chaining
     * @see Cyrus::closeAll()   Aliased by this method.
     */
    // public function ca();

    /**
     * Re-opens a closed child identified by $id, and returns the child
     * object for chaining.
     * 
     * Deeply nested children can be targeted by passed a 'directory'
     * as the id, i.e. `parent/babysitter/child`.
     * 
     * @param string $id
     * 
     * @return object
     */
    public function nest($id);

    /**
     * Re-opens a closed child identified by $id, and returns the child
     * object for chaining.
     * 
     * Deeply nested children can be targeted by passed a 'directory'
     * as the id, i.e. `parent/babysitter/child`.
     * 
     * Alias of Cyrus::nest() for convenience.
     * 
     * @param string $id    The id or 'directory' that targets a child.
     * @return object       Cyrus object returned for chaining.
     * @see Cyrus::nest()   Aliased by this method.
     */
    // public function n($id);

    /**
     * Get the value of the specified attribute.
     *
     * @param string $attr The attribute requested, i.e. `class`, `style`, 
     * `data-target`, etc.
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
     * Sets multiple attributes in a single call. Attributes must be passed
     * to the method in an array, in the form of `[ $attr1 => value1, 
     * $attr2 => $value2 ]`. 
     * 
     * This method is essentially a wrapper for multiple calls to `setAttr`.
     * @param array $array 
     * @return object
     */
    public function setAttrs($array);

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
     * Shortcut for creating img tags
     * @param string $source 
     * 
     * @return object
     */
    public function setSrc($source);

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