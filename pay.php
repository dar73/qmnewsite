<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://sdk.paymentshub.com/pay-now.min.js"></script>
    <script src="ph.js"></script>
    <link rel="stylesheet" href="ph.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>

<body>

</body onload="initialize_sdk('Your MID','Your Gateway Public Key')">
<label>Card Number:</label>
<div id="card-number" class="form-field" />
</div>
<label>CVV:</label>
<div id="card-cvv" class="form-field" />
</div>
<!-- comment out or remove the below section if you do not want the customer to enter their billing street and zip for AVS -->
<label>Street:</label>
<div id="address" class="form-field" />
</div>
<label>Zip:</label>
<div id="zipFirst5" class="form-field" />
</div>
<label>Zip+4:</label>
<div id="zipPlus4" class="form-field" />
</div>
<input type="hidden" id="avs-fields-data" />
<!-- end of AVS section -->
<input type="hidden" id="card-token" />
<input type="button" onclick="getToken()" name="tokenize_and_pay" value="Submit Payment" />

</html>