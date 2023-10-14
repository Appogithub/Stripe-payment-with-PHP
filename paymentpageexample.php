<?php
include("stripe/credentials.php");

$amountToPay=1; // Total amount to pay
$Description=""; // Description
$TransactionId=time(); // The order ID to pay
$userEmail="example@gmail.com";

//Payment By Card process
if(isset($_POST['name']) AND !empty($_POST['name']) AND isset($_POST['stripeToken']))
{
$payementSuccess=0;
$POST=filter_var_array($_POST, FILTER_SANITIZE_STRING);
$CardHolderName=trim(htmlspecialchars(strip_tags($POST['name'])));
$Token=$POST['stripeToken'];
$currency="USD";
if($CardHolderName != '' AND $Token != '')
{
require_once('stripe/vendor/autoload.php');
\Stripe\Stripe::setApiKey($stripeSecretKey);

try{
$customer = \Stripe\Customer::create(array(
'email' => $userEmail,
'source' => $Token
));
}catch(Exception $e){
$api_error=$e->getMessage();
}

if(empty($api_error) && $customer)
{
$TotalAmount = ($amountToPay * 100);
try{
$charge = \Stripe\Charge::create(array(
'customer' => $customer->id,
'amount' => $TotalAmount,
'currency' => $currency,
'description' => $Description
));
}catch(Exception $e){
$api_error=$e->getMessage();
}
}
if(empty($api_error) && $charge)
{
$chargeJson = $charge->jsonSerialize();
if($chargeJson['amount_refunded'] == 0 AND empty($chargeJson['failure_code']) AND $chargeJson['paid'] == 1 AND $chargeJson['captured'] == 1) 
{
if($chargeJson['status'] == 'succeeded')
{
$payementSuccess=1;	
}
}	
}

//Success
if($payementSuccess == 1)
{
//Update Database
echo "Payment made successfully!";
//Update Database
}
else
{
if(isset($api_error) AND $api_error != '')
{
$error=$api_error;
}
else
{
$error="Désolé, une erreur s'est produite, vérifiez votre carte puis réessayez. N'hésitez pas à nous contacter en cas de problème.";	
}	
}
}
else
{
$error="Veuillez remplir tous les champs !";
}
}
//Payment By Card process
?>

<!DOCTYPE html>
<html>
<head>
<title>Stripe Payment Test</title>

<!--Stripe-->
<script src="https://js.stripe.com/v3/"></script>
<script> const spk="<?php echo $stripePublicKey; ?>"; </script>
<script src="stripe/js/script.js?"></script>
<link rel="stylesheet" type="text/css" href="stripe/css/style.css">
<!--Stripe-->

</head>
<body>
<div class="container" style="margin-top:50px; text-align:center;">
<div class="row">
<div class="panel-body" style="float:left; width:350px; margin-left:calc(50% - 175px)">
<div class="form-group">

<!--Stripe Form-->
<div class='payment-form-container'>
<div id="spiner-container"><div class="lds-dual-ring"></div></div>
<img src='stripe/payment-icon.webp' class='payment-icon-style' />
<?php if(isset($error)) { echo "<div class='alert alert-danger'>".$error."</div>"; } ?>
<form id="payment-form" method='post' action=''>
<input type="text" name='name' id="cardholder-name" placeholder="Card holder name" value='<?php if(isset($_POST['name'])) { echo $_POST['name']; } ?>' class="form-control" required />
<div id="card-elements" style="float:left; width:100%; margin-bottom:20px;"></div>
<div id="card-errors" role="alert" style='float:left; width:100%; font-size:14px !important; color:red !important;'></div>
<button id="card-button" id="card-button" class='payment-btn' name="paybycardprocess" style='margin-top:15px;'>Pay $<?php echo number_format($amountToPay,2); ?></button>
</form>
</div>
<!--Stripe Form-->

</div>                     
</div>
</div>
</div>
</body>
</html>

