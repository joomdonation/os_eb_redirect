# Events Booking redirect base payment plugin skeleton

## How does it work
With a redirect base payment plugin, registrants will be redirected to your payment gateway for processing payment instead of entering credit card payment directly on your site. The typical workflow is:
  1. Registrants enter billing information on the registration form.
  2. Registrants choose your payment method, click on Process Registration button
  3. The the registration data is stored into database
  4. A message such as "Please while we redirect you to the payment gateway for processing payment is displayed". Then after few seconds, registrant is redirected to payment gateway for processing payment
  5. After payment is completed at the payment gateway:
    * Customer is being redirected to registration complete page which display a thank you message and registration information
    * The payment gateway notify Events Booking and your site about the payment. The notification (usually is a post request) is sent to the following URL : http://yoursitedomain.com/index.php?option=com_eventbooking&task=payment_gateway&name=os_payment_plugin_name. The payment gateway then verify the payment, if it is valid, send notification email (to registrant and admin), update status of the registration record....

## Payment plugin structure

## The __construct method

## The processPayment method

## The verifyPayment method

## The validate method
