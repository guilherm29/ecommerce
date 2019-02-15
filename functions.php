<?php

use \Tagui\Model\User;
use \Tagui\Model\Cart;

function formatPrice(float $vlprice)
{
    return number_format($vlprice, 2, ",",".");
}


function formatDate($date)
{
	return date('d/m/Y', strtotime($date));
}
function checkLogin($inadmin = true)
{
	return User::checkLogin($inadmin);
}
function getUserName()
{
	$user = User::getFromSession();
	return $user->getdesperson();
}
function getCartNrQtd()
{
	$cart = Cart::getFromSession();
	$totals = $cart->getProductsTotals();
	return $totals['nrqtd'];
}
function getCartVlSubTotal()
{
	$cart = Cart::getFromSession();
	$totals = $cart->getProductsTotals();
	return formatPrice($totals['vlprice']);
}

?>