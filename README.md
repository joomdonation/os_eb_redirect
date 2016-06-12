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
Usually, a payment plugin will contains on XML file and one PHP file :

1. The xml file (os_payment_plugin_name.xml):
  * It provide basic payment plugin information (name, description, author, copyright....). For example https://github.com/joomdonation/os_eb_redirect/blob/master/os_redirect.xml#L3-L12
  * It defines the payment plugin parameters such as payment plugin mode, Merchant ID... These parameters will be setup by website administrator when he edit the payment plugin in Events Booking -> Payment Plugins section. See https://github.com/joomdonation/os_eb_redirect/blob/master/os_redirect.xml#L14-L33 to understand how it is defined

2. The PHP file which handles the payment process, payment verification...
3. The payment plugin might need to have extra library (which is usually provided by the payment gateway for processing payment). If your payment plugin need a library like that, add it into a folder plugin_name in your payment plugin package and define it in the xml file using **folder** tag like this https://github.com/joomdonation/os_eb_redirect/blob/master/os_redirect.xml#L37
  

## The __construct method

## The processPayment method

## The verifyPayment method

## The validate method
