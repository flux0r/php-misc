<?php
/*----------------------------------------------------------------------------
 * Chapter 4 - Advanced features
 *--------------------------------------------------------------------------*/



/*----------------------------------------------------------------------------
 * Static definitions
 */

class StaticEx0 {
	/* 
	 * If I change a static definition, the change is available to all 
	 * instances of the class.
	 */
	static $hello_cnt = 0;
	static public function hello()
	{
		/* ``Self'' is like ``$this'' for classes. */
		self::$hello_cnt++;
		return "hello";
	}
}

print StaticEx0::$hello_cnt."\n";
print StaticEx0::hello()."\n";
print StaticEx0::$hello_cnt."\n";



/*----------------------------------------------------------------------------
 * Constant definitions
 *
 * Use the ``const'' keyword in a class to make immutable definitions. These 
 * definitions can only refer to primitive values, not objects.
 */

class ConstEx0 {
	const BLAH	= 0;
	const DEBLAH	= 1;
}

print ConstEx0::BLAH."\n";
print ConstEx0::DEBLAH."\n";



/*----------------------------------------------------------------------------
 * Abstract classes
 *
 * An abstract class can't be instantiated. It defines the interface for any 
 * class that extends it. Normally an abstract class will have at least one 
 * abstract method, which can't have an implementation.
 */

abstract class ProductWriter {
	protected $products = array();

	public function insert(Product $x)
	{
		$this->products[] = $x;
	}

	abstract public function write();
}

/*
 * Classes extending an abstract class must implement all abstract methods 
 * or must themselves be abstract classes. The implementation can't have
 * stricter visibility than the abstract method and it should have the same 
 * arity and class type hinting.
 */

class XmlProductWriter extends ProductWriter {
	public function write()
	{
		$r = "<?xml version=\"1.0\" encoding=\"UTF-8\">\n"
			."<products>\n";
		foreach ($this->products as $p) {
			$r .= "\t<product title=\"{$p->title()}\">\n"
				."\t\t<summary>{$p->summary()}"
				."</summary>\n"
				."\t</product>\n";
		}
		return $r .= "</products>\n";
	}
}

class StringProductWriter extends ProductWriter {
	public function write()
	{
		$r = "PRODUCTS:\n";
		foreach ($this->products as $p) {
			$r .= "\t{$p->summary()}\n";
		}
		return $r;
	}
}



/*----------------------------------------------------------------------------
 * Interfaces
 *
 * Interfaces can't contain any implementations. Use the ``implements'' 
 * keyword to say a class implements an interface. (These seem really 
 * powerful because a class can implement any number of interfaces. It might 
 * be the closest thing to a type class in Haskell.)
 */

interface Chargeable {
	public function price();
}

class Ransom implements Chargeable {
	private $price = 0;

	public function __construct($x)
	{
		$this->price = $x;
	}

	public function price()
	{
		return $this->price;
	}

	public function set_price($x)
	{
		$this->price = $x;
	}
}



/*----------------------------------------------------------------------------
 * Late static bindings
 *
 * Earlier, I said that ``self'' is like ``this'' for classes. There is a 
 * subtle but important difference: ``self'' is evaluated in the context of 
 * the class that created it. This means that if I make an abstract class 
 * with a method that makes use of ``new self()'', any call to that method 
 * will give an error because I've tried to instantiate an abstract class. 
 * This happens even if I call the method with a handle to a concrete class 
 * that is a descendent of the abstract class.
 *
 * To fix this, I can make the method a ``static'' method. When a static 
 * method is called using a handle to a descendent class of an abstract 
 * class, the call is evaluated in the context of the invoking class instead 
 * of the containing class.
 *
 * I can also use the static keyword to get a handle to the invoking class 
 * for other methods.
 */

abstract class DomainObject {
	private $group;

	/* Using ``static'' to get a handle to the invoking class. */
	public function __construct()
	{
		$this->group = static::group();
	}

	/* Using ``static'' to make a new object of the invoking class. */
	public static function create()
	{
		return new static();
	}

	public static function group()
	{
		return "default";
	}
}

class User extends DomainObject {
}

class Document extends DomainObject {
	public static function group()
	{
		return "document";
	}
}

class SpreadSheet extends Document {
}

print_r(User::create());	/* Has group == "default" */
print_r(SpreadSheet::create());	/* Has group == "document" */



/*--------------------------------------------------------------------------*/
?>
