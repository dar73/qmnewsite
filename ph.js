const PayNowSdk = PayNow.default;

// call this function on page load
function initialize_sdk(mid, gateway_public_key)
{
  const options = {
    cardFieldId: 'card-number',
    cvvFieldId: 'card-cvv',
    //comment out the next three lines if you do not want the customer to enter their billing street and zip for AVS
    addressFieldId: 'address',
    zipFieldId: 'zipFirst5',
    zipPlusFourFieldId: 'zipPlus4'
  };

  // triggered when credentials are validated and iframes loaded:
  PayNowSdk().on('ready', () => {
    // set styling for the iframe fields
    const numberStyling = 'border: 1px solid black; width: 160px; height: 20px;';
    const cvvStyling = 'border: 1px solid black; width: 38px; height: 20px;';
    //comment out the next three lines if you do not want the customer to enter their billing street and zip for AVS
    const streetStyling = 'border: 1px solid black; width: 200px; height: 20px;';
    const zipStyling = 'border: 1px solid black; width: 90px; height: 20px;';
    const zip4Styling = 'border: 1px solid black; width: 80px; height: 20px;';

    PayNowSdk().setStyle('number', numberStyling);
    PayNowSdk().setStyle('cvv', cvvStyling);
    //comment out the next three lines if you do not want the customer to enter their billing street and zip for AVS
    PayNowSdk().setStyle('address', streetStyling);
    PayNowSdk().setStyle('zip', zipStyling);
    PayNowSdk().setStyle('zipPlusFour', zip4Styling);

    // set number format for card (optional)
    // can be 'plainFormat', 'prettyFormat', or 'maskedFormat'
    PayNowSdk().setNumberFormat('maskedFormat');

    console.log('ready!');
  });

  // triggered after addCard, checks valid field entries only
  PayNowSdk().on('validation', (inputProperties) => {
    console.log('validation', inputProperties);
  });

  // triggered after getCardToken
  // can be type: "validation" for missing fields or type: "server"
  PayNowSdk().on('errors', (errors) => {
    console.log('error', errors);
    // add your preferred error handling here
  });

  PayNowSdk().init(gateway_public_key, mid, options);
}

// call this function before submitting the payment form
const getToken = async () => {
  document.getElementById('card-token').value = "";

  //document.getElementById('alert_message').innerHTML = "Verifying...";

  //in addition to the fields listed here, the addCard method will also validate the credit card number, CVV, and AVS fields if present
  await PayNowSdk().addCard({
      month: document.getElementById('card-month').value,
      year: document.getElementById('card-year').value,    
  });

  const cardToken = PayNowSdk().getCardToken();

  if (cardToken == null)
  {
    console.log('unable to tokenize the card');
    //document.getElementById('alert_message').innerHTML = "Please verify the card information or use a different card.";
  }
  else
  {
    document.getElementById('card-token').value = cardToken;
    console.log('cardToken', cardToken);

    //document.getElementById('alert_message').innerHTML = "Processing...";
    
    //optional return of AVS fields
    //comment out the next three lines if you do not want the customer to enter their billing street and zip for AVS
    const avsFields = PayNowSdk().getAVSFields();
    const json_string = JSON.stringify(avsFields);
    document.getElementById('avs-fields-data').value = json_string;

    //replace 'pay' below with the id of your form
    document.getElementById('pay').submit();
  }
}
