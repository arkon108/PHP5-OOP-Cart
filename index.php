<?php 

/**
 * Bootstrap for shopping cart demonstration
 */

define('LIB_DIR', realpath('./'));

set_include_path(get_include_path() . PATH_SEPARATOR . LIB_DIR);

include_once 'lib/Cart.php';
include_once 'lib/Product/Book.php';
include_once 'lib/Product/Game.php';
include_once 'lib/Product/Decorator/Discount.php';
include_once 'lib/Persistance/Session.php';

// $products could come from database or other data store
// for example purposes, the products are be hardcoded
$products = array();

$book1 = new Product_Book();
$book1->setDescription("The Hitchhiker's Guide to the Galaxy");
$book1->setCost(14.99);
$products[] = $book1;

$book2 = new Product_Book();
$book2->setDescription('Code Complete');
$book2->setCost(30.00);
// let's set half price discount to this product
$book2 = new Discount_Decorator($book2);
$book2->setDiscount(.5);
$products[] = $book2;

$book3 = new Product_Book();
$book3->setDescription('Godel, Escher, Bach: An Eternal Golden Braid');
$book3->setCost(25.00);
$products[] = $book3;

$game1 = new Product_Game();
$game1->setDescription('Duke Nukem Forever');
$game1->setCost(666.00);
$products[] = $game1;

$game2 = new Product_Game();
$game2->setDescription('Return to the return to the Castle Wolfenstein');
$game2->setCost(260.50);
$products[] = $game2;


// since products did not come from the database
// let's fake their id property, so they can be uniquely referenced
foreach ($products as $id => $product) {
	$product->id = $id; 
}

// initialize shopping cart
// note that Cart implements Singleton pattern
$cart = Cart::getInstance();

// inject persistance object
// so our Cart can remember it's items
$cart->setPersistance(new Persistance_Session());

// add VAT tax to Cart
$cart->addTax('PDV', .22);


// process submitted form
if (isset($_POST) && ! empty($_POST)) {
	
	// adding to cart
	if (isset($_POST['add'])) {
		if (isset($_POST['product_check'])) {
			foreach ($_POST['product_check'] as $productId) {
			$selectedProduct = $products[$productId];
			$cart->add($selectedProduct);
			}			
		}
	}
	
	// changing the cart contents
	if (isset($_POST['cart'])) {
		
		if (isset($_POST['empty'])) {
			$cart->removeAll();
		} elseif (isset($_POST['remove'])) { // removing the products
			if (isset($_POST['remove_product'])) {
				foreach ($_POST['remove_product'] as $k => $v) {
					$cart->remove($v);
				}
			}
		}
	}
}

// include display template
include 'template/default.php';