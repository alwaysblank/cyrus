<?php
namespace Livy;
use \Hipparchus\Pocketknife as Util;

require_once( 'CyrusInterface.php' );


    /**
     * Cyrus HTML element constructor.
     * 
     * Cyrus, named for a reasonably-well-known ancient Roman architect, builds HTML elements
     * for you using an OO approach. Using method chaining, you can build infinitely nested elements with
     * a single (albiet very long) line of code.
     */
    class Cyrus implements CyrusInterface
    {
        protected $content = array();
        protected $attrs = array();
        protected $element = 'div';
        protected $parent;
        protected $child;
        protected $selfClosing = array(
        'img',
        'br',
        'hr',
        'source',
        'input',
        'meta',
        'embed',
        );
        public $key;

        public function __construct($name = null)
        {
            $this->key = uniqid('cyrus');
            if($this->safeString($name)) :
                $this->setClass($this->safeString($name));
            endif;
        }

        public function __call($name, $arguments)
        {
            $possibleNames = [];
            $possibleNames[] = $this->safeString('set' . ucfirst($name));
            $possibleNames[] = $this->safeString('set' . strtoupper($name));

            foreach ($possibleNames as $possibleName) :
                if (method_exists($this, $possibleName)) :
                    $this->$possibleName(...$arguments);
                endif;
                return $this;
            endforeach;
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
        protected function collapse($array, $delimiter = ' ', $trim = false)
        {
            $return = null;
            $i = count($array);

            if($i < 1) : return ''; endif;

            if($i === 1 && array_values($array)[0] === false) : return false; endif;

            foreach ($array as $key => $item) :
              $i = --$i;
              if(is_int($item) || is_float($item)) : $item = strval($item); endif;
              if(!is_string($item)) : continue; endif;
              $return .= ($i > 0 ? $item.$delimiter : $item);
            endforeach;

            return ($trim ? trim($return) : $return);
        }


        public static function open($name = null)
        {
            return new self($name);
        }

        
        public function get($prop)
        {
          return $this->{$this->safeString($prop)};
        }

        
        public function safeString($string)
        {
            if (Util::safeString($string, "/^[\w\-]+$/")) :
                    return $string; else:
                    return false;
            endif;
        }


        public function addContent($content, $key = false)
        {
            if (is_a($content, 'Livy\Cyrus')) :
                $key = $content->key;
                $content = $content->construct();
            endif;
            if ($key === false) :
                $this->content[] = $content; 
            else :
                $this->content[$key] = $content;
            endif;

            return $this;
        }

        public function setContent($content, $key = false)
        {
            $this->addContent($content, $key);

            return $this;
        }

        public function getChild($key)
        {
            return $this->child[$key];
        }


        public function setChild($object)
        {
            $this->child[$object->key] = $object;

            return $this;
        }


        public function getParent($object = null)
        {
            if ($object == null) : $object = $this;
            endif;
            if ($object->parent) :
                return $object->parent; else :
                throw new \Exception("I couldn't find a parent. You probably forgot to close a child somewhere.");

            return $object;
            endif;
        }


        public function setParent($parent)
        {
            $this->parent = $parent;

            return $this;
        }


        public function openChild($id = false)
        {
            $child = new self();
            $child->setParent($this);
            $id = $this->safeString($id);

            if ($id) :
                $this->{$id} = $child->key;
            endif;

            return $child;
        }

        public function o($id = false)
        {
            return $this->openChild($id);
        }


        public function closeChild()
        {
            try {
                $parent = $this->getParent();
            } catch (\Exception $e) {
                echo $e->getMessage();

                return $this;
            }
            $parent->addContent($this);
            $parent->setChild($this);

            return $parent;
        }

        public function c($id = false)
        {
            return $this->closeChild($id);
        }


        public function closeChildren( $levels )
        {
            $i = 0;
            $obj = $this;
            while ( $i < $levels) {
                $obj = $obj->closeChild();
                $i++;
            }
            return $obj;
        }

        public function closeAll()
        {
            $obj = $this;
            while(is_object($obj->parent)) :
                $obj = $obj->closeChild();
            endwhile;
            return $obj;
        }

        public function ca()
        {
            return $this->closeAll();
        }

        public function nest($id)
        {
            if (!$id) : return $this;
            endif;

            $levels = explode('/', $id);

            if(count($levels) > 1) :
                $obj = $this;
                $obj->level = 0;
                $i = 1;
                foreach ($levels as $level) {
                    $obj = $obj->child[$obj->$level];
                    $obj->level = $i;
                    $i++;
                }
                return $obj;
            else :
                $child = $this->{$levels[0]};

                return $this->child[$child];
            endif;
        }

        public function n($id)
        {
            return $this->nest($id);
        }


        public function getAttr($attr)
        {
            if (isset($this->attrs[$attr])) :
                return $this->attrs[$attr]; else :
                return false;
            endif;
        }


        public function setAttr($attr, $value)
        {
            if ($value === false) :
                unset($this->attrs[$attr]);
                return $this; 
            elseif ($value !== true) :
                $array = $this->getAttr($attr);
                $array[] = $value;
                $value = $array;
            endif;
            $this->attrs[$attr] = $value;

            return $this;
        }

        public function setAttrs($array)
        {
            if(!is_array($array)) :
                return $this;
            endif;

            foreach ($array as $attr => $value) :
                $this->setAttr($attr, $value);
            endforeach;

            return $this;
        }


        public function setClass($class)
        {
            $this->setAttr('class', $class);

            return $this;
        }


        public function setID($id)
        {
            $this->setAttr('id', $id);

            return $this;
        }


        public function setURL($url)
        {
            $this->setAttr('href', $url)->setEl('a');

            return $this;
        }


        public function setStyle($prop, $value)
        {
            $style = "$prop: $value;";
            $this->setAttr('style', $style);

            return $this;
        }

        public function setSrc($source)
        {
            $this->setAttr('src', $source)->setEl('img');

            return $this;
        }


        public function setEl($el)
        {
            $this->element = $el;

            return $this;
        }


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


        public function construct()
        {
            if (in_array($this->element, $this->selfClosing)) :
                return "<{$this->element} {$this->assembleAttrs()}>"; 
            elseif($this->collapse($this->content) !== false) :
                return "<{$this->element} {$this->assembleAttrs()}>{$this->collapse($this->content, ' ', true)}</{$this->element}>";
            endif;
        }


        public function display()
        {
            echo $this->construct();
        }
    }