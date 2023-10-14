window.onload = () => {
var stripe = Stripe(spk);
var elements = stripe.elements();
var errorElement = document.getElementById("card-errors");
var cardHolderName = document.getElementById("cardholder-name");
var verifamount = !!document.getElementById("amount");
if(verifamount == true)
{
var amount = document.getElementById("amount");	
amount.focus();
}
else
{
cardHolderName.focus();	
}

//Style
var style = {
base: {
color: '#32325d',
fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
fontSmoothing: 'antialiased',
fontSize: '16px',
'::placeholder': {
color: '#aab7c4'
}
},
invalid: {
color: '#fa755a',
iconColor: '#fa755a'
}
};

var card = elements.create('card', { hidePostalCode: true, style: style });
card.mount("#card-elements");

//Show error
card.addEventListener("change", (event) => {
let displayError = document.getElementById("card-errors")
if(event.error){
displayError.textContent = event.error.message;
}
else
{
displayError.textContent = "";
}
})
//Show error

//On submit
var cardButton = document.getElementById("card-button");
cardButton.addEventListener("click", () => {

event.preventDefault();
if(cardHolderName.value.trim() != '')
{
if(verifamount == true)
{
if(amount.value.trim() != '')
{
// Stripe Card Process	
stripe.createToken(card).then(function(result) {
if(result.error)
{
document.getElementById('spiner-container').style.display='none';
errorElement.textContent = result.error.message;
}
else
{
document.getElementById('spiner-container').style.display='block';
stripeTokenHandler(result.token);
}
});
// Stripe Card Process	
}
else
{
document.getElementById('spiner-container').style.display='none';
errorElement.textContent = "Please indicate the amount!";	
}
}
else {	
// Stripe Card Process	
stripe.createToken(card).then(function(result) {
if(result.error)
{
document.getElementById('spiner-container').style.display='none';
errorElement.textContent = result.error.message;
}
else
{
document.getElementById('spiner-container').style.display='block';
stripeTokenHandler(result.token);
}
});
// Stripe Card Process
}
}
else
{
document.getElementById('spiner-container').style.display='none';
errorElement.textContent = "Please fill in the name of the card owner!";	
}
});

function stripeTokenHandler(token)
{
var form = document.getElementById('payment-form');
var hiddenInput = document.createElement('input');
hiddenInput.setAttribute('type', 'hidden');
hiddenInput.setAttribute('name', 'stripeToken');
hiddenInput.setAttribute('value', token.id);
form.appendChild(hiddenInput);
form.submit();
cardButton.disabled = true;
cardButton.innerHTML = "Processing...";
}
}