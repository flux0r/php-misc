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
	static $helloCount = 0;
	static public function hello()
	{
		/* ``Self'' is like ``$this'' for classes. */
		self::$helloCount++;
		return "hello";
	}
}

print StaticEx0::$helloCount."\n";
print StaticEx0::hello()."\n";
print StaticEx0::$helloCount."\n";



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

	public function insertProduct(Product $x)
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
		$r = "<?xml version=\"1.0\" encoding=\"UTF-8\">\n<products>\n";
		foreach ($this->products as $p) {
			$r .= "\t<product title=\"{$p->title()}\">\n"
				."\t\t<summary>{$p->summaryLine()}</summary>\n"
				."\t</product>\n";
		}
		$r .= "</products>\n";
		return $r;
	}
}



?>
