# Cyrus 2.0 Specification

The goals of 2.0 are as follows:

- A simpler, easier to parse storage system for element descriptions. I'm taking some inspiration here from
    [Climber](https://github.com/alwaysblank/climber), and storing content in a large array.
- Less verbose, clearer syntax.
- [Pluggable custom elements](https://github.com/alwaysblank/cyrus/issues/2).
    
    
## Terminology

*Brick*

Basic unit. Represents (what is probably) an element definition. I'm not calling these "elements" because
they may not always *be* representative of HTML elements, and because I don't want to confuse them with
actual, rendered elements.

*Cart*

The array containing all of our bricks.

*Marble* 

A Brick that has been processed, and converted in an actual element. Not sure if this will actually be used:
Mostly I'm putting it in here as a "clever" reference to Augustus's claim to have "found Rome a city of
bricks, and left it a city of marble."

## Data Structure

### Bricks

Each brick is an object. Each brick object includes several protected properties that define it. These
properties can only be set by interacting with the brick via methods that handle most of that for you;
they *cannot* be directly set (i.e. you can't do `$Brick->parent = '1234'`; you would have to do 
`$Brick->parent('1234')`).

Methods for getting and setting (if they can be set) share the name of that property. If called with no
arguments, they return the property. If called with an argument, they set that property (and then return
the new property value). i.e:

```php
$Brick->parent();      // 1234
$Brick->parent(5678);  // 5678 
$Brick->parent();      // 5678
```

This allows us to control which properties are set, and how. It also allows us to fire other methods when
properties are changed, if necessary.

Bricks extend an abstract class `Mold`. `Mold` defines all of the properties necessary, and also constructs
the uid for each Brick instance. It will probably also provide or describe some other necessary functions 
I haven't figured out yet.

Each brick must implement the following properties:

- **uid** - *string* - This is a unique, immutable id for this brick. It will be used to identify the 
    brick in many other contexts, so we should take care to make sure it's unique. Ideally this should be
    an alphanumeric string (a numeric id might convey an inaccurate impression that it is associated with
    brick order). It is automatically set on class instantiation and should *not* be changed after that. 
- **parent** - *string* - This is equal to the uid for whatever brick is the parent of this one. Each
    brick can have only one parent. Nearly all bricks will have parents, but if they don't this slot will
    be equal to `null`.
- **tag** - *string* - The tag used for this element in HTML. i.e. `div` or `form`. It can also be `null`,
    which represents a plain string.
- **selfClosing** - *boolean* - Whether or not this element requires a closing tag.
- **class** - *array* - This is actually an array of arrays. Each internal array represents a single
    css class definition. When an internal array contains only a single entry, that single entry is the
    class. When an internal array contains multiple entries, those entires will be concatenated with each
    other in reverse order. i.e. `['class', '__', 'pre']` would yield `pre__class`.
- **attributes** - *array* - An array of arrays. Each internal array represents an html attribute, usually
    a key/value pair. i.e. `['data-target', 'menu']` would render as `data-target="menu"`. The exception is
    "boolean" attributes, which are defined as `['hidden', true]`. These will be rendered as just the
    attribute name, with no value.
- **order** - *integer* - An integer that describes how an element should be ordered. This value is
    unlikely to be used all that much, since usually things will just appear in the order in which they
    are defined, but it is not difficult to imagine situations in which a user would wish to manually 
    define the order elements appear in.
    
Anyone familiar with Cyrus 1.0 (ha ha) may notice that the 'content' property has been done away with.
The reason for this is the same reason we talk about bricks, not elements: Content is just another brick.
Specifically, it is a brick with a tag of `null` and a unique attribute of `text` which contains all of
the text content that brick should have. 