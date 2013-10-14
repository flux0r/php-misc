<?php
/*----------------------------------------------------------------------------
 * Chapter 3 - Object basics
 *--------------------------------------------------------------------------*/



/*----------------------------------------------------------------------------
 * Classes and objects, properties, working with methods
 */

class ShopProduct {
	public $title			= "default title";
	public $producerMainName	= "main name";
	public $producerFirstName	= "first name";
	public $price			= 0;

	/* Methods with no visibility qualifier are ``public''. */
	function getProducer() {
		return "{$this->producerFirstName} "
			."{$this->producerMainName}";
	}
}

$product1 = new ShopProduct();
$product2 = new ShopProduct();

print "The title of product1: {$product1->title}\n";

/* I can mutate any ``public'' properties of an object. */
$product1->title = "new title";
print "Changed title of product1: {$product1->title}\n";

/*
 * I can add an arbitrary property to an object even if its class doesn't 
 * have the new property. (This seems like a bad idea to me.)
 */
$product1->arbitraryProp = "treehouse";
print "Added a property to product1: {$product1->arbitraryProp}\n";

/* Public method usage. */
$product1			= new ShopProduct();
$product1->title		= "Blah Blah";
$product1->producerMainName	= "Hunt";
$product1->producerFirstName	= "Mike";
$product1->price		= 4.89;
print "Using a public method:\n\tauthor: {$product1->getProducer()}\n";



/*----------------------------------------------------------------------------
 * Constructor methods
 */

class ShopProduct1 {
	public $title;
	public $producerMainName;
	public $producerFirstName;
	public $price;

	/* ``__construct'' is invoked at object creation time. */
	function __construct($title, $producerFirstName,
				$producerMainName, $price) {
		$this->title			= $title;
		$this->producerMainName		= $producerMainName;
		$this->producerFirstName	= $producerFirstName;
		$this->price			= $price;
	}

	function getProducer() {
		return "{$this->producerFirstName} "
			."{$this->producerMainName}";
	}
}

$product1 = new ShopProduct1("Blah Blah", "Mike", "Hunt", 4.89);
print "Used a constructor method:\n\tauthor: {$product1->getProducer()}\n";



/*----------------------------------------------------------------------------
 * Class type hinting
 */

class ShopProductShower {
	/*
	 * The ``ShopProduct1'' before ``$x'' is a class type hint. These 
	 * hints are only checked at run-time, however and I can't use them 
	 * on primitive types.
	 */
	public function show(ShopProduct1 $x) {
		return "{$x->title}: "
			.$x->getProducer()
			." ($x->price)";
	}
}

$shower = new ShopProductShower();
print "Using class hints:\n\t{$shower->show($product1)}\n";



/*----------------------------------------------------------------------------
 * Inheritence
 */

class ShopProduct2 {
	public $title;
	public $producerMainName;
	public $producerFirstName;
	public $price;

	public function __construct($title, $producerFirstName,
					$producerMainName, $price) {
		$this->title			= $title;
		$this->producerMainName		= $producerMainName;
		$this->producerFirstName	= $producerFirstName;
		$this->price			= $price;
	}

	public function getProducer() {
		return "{$this->producerFirstName} "
			."{$this->producerMainName}";
	}

	public function getSummaryLine() {
		return "{$this->title} ("
			."{$this->producerMainName}, "
			."{$this->producerFirstName})";
	}
}

/* Use the ``extends'' keyword for inheritence. */
class CdProduct extends ShopProduct2 {
	public $playLength;

	public function __construct($title, $producerFirstName, 
				$producerMainName, $price, $playLength) {
		/* 
		 * Use ``::'' to get a handle for a class instead of ``->'',
		 * which gets a handle for an object.
		 */
		parent::__construct($title, $producerFirstName,
					$producerMainName, $price);
		$this->playLength = $playLength;
	}

	public function getPlayLength() {
		return $this->playLength;
	}

	public function getSummaryLine() {
		return parent::getSummaryLine()
		       ." playing time - "
			."{$this->getPlayLength()}";
	}
}

$cd0 = new CdProduct("Crappy Beats", "Blah Blah", "Who Cares", 49.99, 34);
print "Using ``extends'':\n\t{$cd0->getSummaryLine()}\n";



/*--------------------------------------------------------------------------*/
?>
