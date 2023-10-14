<?php
$productionMode=0; // 1- Production, 0- Test

//Test
$secretKeyTest="sk_test_VePHdqKTYQjKNInc7u56JBrQ";
$publicKeyTest="pk_test_oKhSR5nslBRnBZpjO6KuzZeX";
//Live
$secretKeyLive="";
$publicKeyLive="";

if($productionMode == 0) {
$stripeSecretKey=$secretKeyTest;
$stripePublicKey=$publicKeyTest;	
} else {
$stripeSecretKey=$secretKeyLive;
$stripePublicKey=$publicKeyLive;	
}
?>