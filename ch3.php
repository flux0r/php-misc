<?php
/*----------------------------------------------------------------------------
 * Chapter 3 - Object basics
 *--------------------------------------------------------------------------*/



/*----------------------------------------------------------------------------
 * Classes and objects, properties, working with methods
 */

class ShopProduct {
	public $title			= "default title";
	public $producer_main_name	= "main name";
	public $producer_first_name	= "first name";
	public $price			= 0;

	/* Methods with no visibility qualifier are ``public''. */
	function producer()
	{
		return "{$this->producer_first_name} "
			."{$this->producer_main_name}";
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
$product1->arbitrary_prop = "treehouse";
print "Added a property to product1: {$product1->arbitrary_prop}\n";

/* Public method usage. */
$product1			= new ShopProduct();
$product1->title		= "Blah Blah";
$product1->producer_main_name	= "Hunt";
$product1->producer_first_name	= "Mike";
$product1->price		= 4.89;
print "Using a public method:\n\tauthor: {$product1->producer()}\n";



/*----------------------------------------------------------------------------
 * Constructor methods
 */

class ShopProduct1 {
	public $title;
	public $producer_main_name;
	public $producer_first_name;
	public $price;

	/* ``__construct'' is invoked at object creation time. */
	function __construct($title, $producer_first_name,
				$producer_main_name, $price)
	{
		$this->title			= $title;
		$this->producer_main_name	= $producer_main_name;
		$this->producer_first_name	= $producer_first_name;
		$this->price			= $price;
	}
 
	function producer()
	{
		return "{$this->producer_first_name} "
			."{$this->producer_main_name}";
	}
}

$product1 = new ShopProduct1("Blah Blah", "Mike", "Hunt", 4.89);
print "Used a constructor method:\n\tauthor: {$product1->producer()}\n";



/*----------------------------------------------------------------------------
 * Class type hinting
 */

class ShopProductShower {
	/*
	 * The ``ShopProduct1'' before ``$x'' is a class type hint. These 
	 * hints are only checked at run-time, however and I can't use them 
	 * on primitive types.
	 */
	public function show(ShopProduct1 $x)
	{
		return "{$x->title}: "
			.$x->producer()
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
	public $producer_main_name;
	public $producer_first_name;
	public $price;

	public function __construct($title, $producer_first_name,
					$producer_main_name, $price)
	{
		$this->title			= $title;
		$this->producer_main_name	= $producer_main_name;
		$this->producer_first_name	= $producer_first_name;
		$this->price			= $price;
	}

	public function producer()
	{
		return "{$this->producer_first_name} "
			."{$this->producer_main_name}";
	}

	public function summary()
	{
		return "{$this->title} ("
			."{$this->producer_main_name}, "
			."{$this->producer_first_name})";
	}
}

/* Use the ``extends'' keyword for inheritence. */
class CdProduct extends ShopProduct2 {
	public $play_length;

	public function __construct($title, $producer_first_name, 
					$producer_main_name, $price,
					$play_length)
	{
		/* 
		 * Use ``::'' to get a handle for a class instead of ``->'',
		 * which gets a handle for an object.
		 */
		parent::__construct($title, $producer_first_name,
					$producer_main_name, $price);
		$this->play_length = $play_length;
	}

	public function play_length()
	{
		return $this->play_length;
	}

	public function summary()
	{
		return parent::summary()
		       ." playing time - "
			."{$this->play_length()}";
	}
}

class BookProduct extends ShopProduct2 {
	public $num_pages;

	public function __construct($title, $producer_first_name,
					$producer_main_name, $price,
					$num_pages)
	{
		parent::__construct($title, $producer_first_name,
				$producer_main_name, $price);
		$this->num_pages = num_pages;
	}

	public function num_pages()
	{
		return $this->num_pages;
	}

	public function summary()
	{
		return parent::summary()
			." page count - "
			."{$this->num_pages()}";
	}
}

$cd0 = new CdProduct("Crappy Beats", "Blah Blah", "Who Cares", 49.99, 34);
print "Using ``extends'':\n\t{$cd0->summary()}\n";



/*----------------------------------------------------------------------------
 * Managing data access
 *
 *	* Public properties and methods can be accessed from any context
 *	* Private properties and methods can only be accessed in the current 
 *	  class; even children cannot access a parent class' private 
 *	  definitions
 *	* A protected definition has the same access as private definitions, 
 *	  except children can access protected definitions in any ancestor 
 *	  classes
 */
class Product {
	private		$title;
	private		$producer_main_name;
	private		$producer_first_name;
	protected	$price;
	private		$discount		= 0;
	private		$id			= 0;

	public function __construct($title, $first_nm, $main_nm, $price)
	{
		$this->title			= $title;
		$this->producer_main_name	= $main_nm;
		$this->producer_first_name	= $first_nm;
		$this->price			= $price;
	}

	public function title()
	{
		return $this->title;
	}
	
	public function producer_main_name()
	{
		return $this->producer_main_name;
	}

	public function producer_first_name() {
		return $this->producer_first_name;
	}

	public function producer()
	{
		return "{$this->producer_first_name} "
			."{$this->producer_main_name}";
	}

	public function price()
	{
		return $this->price - $this->discount;
	}

	public function discount()
	{
		return $this->discount;
	}

	public function set_discount($x)
	{
		$this->discount = $x;
	}

	public function set_id($x)
	{
		$this->id = $x;
	}

	public function summary()
	{
		return "{$this->title()} ({$this->producer()})";
	}

	public static function instance($id, PDO $d)
	{
		$q = $d->prepare("select * from products where id = ?");
		$r = $q->execute(array($id));
		$xs = $q->fetch();

		if (empty($xs)) {
			return null;
		}

		if ($xs["type"] == "book") {
			$prod = new Book($row["title"], $row["firstname"],
				$row["mainname"], $row["price"],
				$row["num_pages"]);
		} else if ($xs["type"] == "cd") {
			$prod = new Book($row["title"], $row["firstname"],
				$row["mainname"], $row["price"],
				$row["play_length"]);
		} else {
			$prod = new Product($row["title"], $row["firstname"],
				$row["mainname"], $row["price"]);
		}

		$prod->set_id($row["id"]);
		$prod->set_discount($row["discount"]);
		return $prod;
	}
}

class Cd extends Product {
	private $play_length = 0;

	public function __construct($title, $first, $main, $p, $l)
	{
		parent::__construct($title, $first, $main, $p);
		$this->play_length = $l;
	}

	public function play_length()
	{
		return $this->play_length;
	}

	public function summary()
	{
		return parent::summary()
			." playing time - "
			."{$this->play_length()}";
	}
}

class Book extends Product {
	private $num_pages = 0;

	public function __construct($title, $first, $main, $p, $n)
	{
		parent::__construct($title, $first, $main, $p);
		$this->num_pages = $n;
	}

	public function num_pages()
	{
		return $this->num_pages;
	}

	public function summary()
	{
		return parent::summary()
			." page count - "
			."{$this->num_pages()}";
	}

	/* Books can't have discounts. */
	public function price()
	{
		return $this->price;
	}
}



/*--------------------------------------------------------------------------*/
?>
