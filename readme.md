# Cyrus
### a simple object-based HTML generator

## Usage

Cyrus uses objects and method chaining to construct semantic HTML elements and then output them for you.

The basic process is as follows:

```php
$element = new Cyrus;

$element->setEl('h1')->setClass('headline-el')->addContent('This is a Headline!')->display();
```

The above code will print out the following:

```html
<h1 class="headline-el">This is a Headline!</h1>
```

It supports any tag type, even ones you made up:

```php
$fakeTag = new Cyrus;

$fakeTag->setEl('fake-tag')->setContent('This isn't a real tag, but it's rendered anyway!')->display();

// <fake-tag>
// This isn't a real tag, but it's rendered anyway!
// </fake-tag>
```

In general the order you chain methods in doesn't matter: `$element->setClass('a-class')->setEl('p')` is function equivalent to `$element->setEl('p')->setClass('a-class')`. There are, however, a few exceptions:

- Nesting (see next section) requires `openChild` at the beginning fo a child element and `closeChild` at the end: Any other order will cause Cyrus to fail.
- Methods that overwrite content (i.e. `setEl`) will overwrite the actions of previous calls in the chain (unless separated by child barriers).
- Calls to `construct` or `display` should always come last. Since they don't return the current object, they'll break the chain, and attempting to chain other things after them will cause some errors.

### Nesting

You can nest elements inside of one another using the `openChild` and `closeChild` methods:

```php
$nested = new Cyrus;

$nested->setClass('parent')
    ->openChild()->setEl('span')->setClass('child')->addContent("I'm a child!")->closeChild()
->display();

// <div class="parent">
//    <span class="child">I'm a child!</span>
// </div>
```

#### Object Nesting

If you pass a Cyrus object to `addContent`, that object will be inserted as content and automatically expanded.

```php
$parent = new Cyrus;
$child = new Cyrus;

$child->setClass('child')->setEl('span')->addContent("I'm a child");

$parent->setClass('parent')->addContent($child)->display();

// <div class="parent">
//    <span class="child">I'm a child!</span>
// </div>
```

#### Advanced Nesting

You can also nest items after a chain has been terminated by using the `nest` method an assigning an ID when calling `openChild`. This is especially useful if, say, you want to insert (or not) content based on a conditional without resorting to creating an entirely separate Cyrus instatiation:

```php
$nestedAgain = new Cyrus;

$nestedAgain->setClass('parent')->openChild('childID')->setClass('child')->closeChild();

if(true) :
    $nestedAgain->nest('childID')->addContent("I've been inserted!")->closeChild();
endif;

$nestedAgain->display();

// <div class="parent">
//    <div class="child">I've been inserted</div>
// </div>
```

### Methods

To learn how methods operate, have a look at the source files (`./src`). Each method is well documented.

The follow will cover some special functionality and edge cases.

#### Short forms

Any method that begins with "set" can be called in a shortened form, i.e. you can call `setClass` as just `class`.

```php
$element->el('blockquote');
// is equivalent to...
$element->setEl('blockquote');

$element->attr('target', 'new');
// is equivalent to...
$element->setAttr('')
```

#### Advanced Attribute Manipulation

##### Unset Attribute

If you find you want to unset an attribute, call `setAttr` on it with the `false` argument:

```php
$element->setAttr('data-target', 'menu')->display();
// <div data-target="menu"></div>
$element->setAttr('data-target', false)->display();
// <div></div>
```

##### Valueless Attributes

If you want to set an attribute that doesn't have a value--i.e. `checked`--you can do so by calling `setAttr` with the `true` argument:

```php
$element->setEl('input')->setAttr('type', 'radio')->setAttr('checked', true);
// <input type="radio" checked>
```

#### setAttr, etc
`setAttr` and all of its aliased methods (i.e. `setClass`, `setURL`, etc) stack up whatever is passed to the same attribute--they don't overwrite anything. The only exception to this is if you pass `false` as an argument to `setAttr`, as this will completely remove that attribute from the element.

This value stacking means that the following statements are equivalent:

```php
$element->setAttr('class', 'test1')->setClass('test2');
// is equivalent to...
$element->setClass('test1 test2');
```