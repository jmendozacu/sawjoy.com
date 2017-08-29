<?php
require 'config.php';
Mage::getSingleton('core/session', array('name' => 'frontend'));
$quote = Mage::getSingleton('checkout/session')->getQuote();

$_productId = $app->getRequest()->getParam("product_id");
$_qty = $app->getRequest()->getParam("qty");
$_method = $app->getRequest()->getParam("method");

if (!$_method)
    die("Access Denied");

if ($_method == "update") :
    if (!empty($_productId)):
        echo updateCart($_productId , $_qty);
    endif;
else :
    echo addToCart($_productId , $_qty);
endif;


function addToCart($_productId , $_qty)
{
    $_product = Mage::getModel("catalog/product")->load($_productId);
    $_cart = Mage::getModel("checkout/cart");
    $_cart->addProduct($_product , array("qty"  => $_qty));

    try {
        if ($_cart->save()) {
            Mage::getSingleton("checkout/session")->setCartWasUpdated(true);
            return json_encode(array(
                "status"    => true,
                "msg"       => "success"
            ));
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function updateCart($_productId , $_qty)
{
    $quote = Mage::getSingleton('checkout/session')->getQuote();
    //get Product
    $product = Mage::getModel('catalog/product')->load($_productId);
    $item = $quote->getItemByProduct($product);
    //execute update
    $quote->updateItem(
        $_productId , array('qty'=>$_qty)
    );
    try {
        if ($quote->save()) :
            //echo "Update Quantity successfully";
            return json_encode(array(
                "status"    => true,
                "msg"       => "success"
            ));
        endif;
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}


/*if ($cartItems = $quote->getAllVisibleItems()) :
    foreach ($cartItems as $item) :
        //print_r($item->getData());exit;
        $_productId = $item->getItemId();

        //get Product
        $product = Mage::getModel('catalog/product')->load($_productId);
        $item = $quote->getItemByProduct($product);

        //execute update
        $quote->updateItem(
            $_productId , array('qty'=>$qty)
        );
        try {
            if ($quote->save()) :
                echo "Update Quantity successfully";
            endif;
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        echo $_itemId;exit;
    endforeach;
endif;*/