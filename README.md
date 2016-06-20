# Events Booking redirect base payment plugin skeleton

## How does it work
With a redirect base payment plugin, registrants will be redirected to your payment gateway for processing payment instead of entering credit card payment directly on your site. The typical workflow is:
  1. Registrants enter billing information on the registration form.
  2. Registrants choose your payment method, click on Process Registration button
  3. The the registration data is stored into database
  4. A message such as "Please while we redirect you to the payment gateway for processing payment is displayed". Then after few seconds, registrant is redirected to payment gateway for processing payment
  5. After payment is completed at the payment gateway:
    * Customer is being redirected to registration complete page which display a thank you message and registration information
    * The payment gateway notify Events Booking and your site about the payment. The notification (usually is a post request) is sent to the following URL : http://yoursitedomain.com/index.php?option=com_eventbooking&task=payment_confirm&name=os_payment_plugin_name. The payment gateway then verify the payment, if it is valid, send notification email (to registrant and admin), update status of the registration record....

## Payment plugin structure
Usually, a payment plugin will contains on XML file and one PHP file :

1. The xml file (**os_payment_plugin_name.xml**):
  * It provide basic payment plugin information (name, description, author, copyright....). For example https://github.com/joomdonation/os_eb_redirect/blob/master/os_redirect.xml#L3-L12
  * It defines the payment plugin parameters such as payment plugin mode, Merchant ID... These parameters will be setup by website administrator when he edit the payment plugin in Events Booking -> Payment Plugins section. See https://github.com/joomdonation/os_eb_redirect/blob/master/os_redirect.xml#L14-L33 to understand how it is defined

2. The PHP file (**os_payment_plugin_name.php**) which handles the payment process, payment verification... This is actually a php class (the name of the class has this format **os_plugin_name**) and it extends **RADPayment** class.
3. The payment plugin might need to have extra library (which is usually provided by the payment gateway for processing payment). If your payment plugin need a library like that, add it into a folder **plugin_name** in your payment plugin package and define it in the xml file using **folder** tag like this https://github.com/joomdonation/os_eb_redirect/blob/master/os_redirect.xml#L37
  
## The __construct method
You don't have to write much code inside this method. Usually, you just need to call **parent::__construct($params, $config);**, define the payment gateway URL based on the payment mode (which is Test Mode or Live Mode) setup by admin in the payment plugin parameters. For example, with PayPal Payment Plugin code:
```php
parent::__construct($params, $config);

if ($this->mode)
{
	$this->url = 'https://www.paypal.com/cgi-bin/webscr';
}
else
{
	$this->url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
}
```

## The processPayment method
This method contains code to pass necessary data to the payment gateway for processing payment. Each payment gateway requires different set of parameters, so you will need to read the payment gateway manually to know which data needs to be passed to the payment gateway. Some notes:

1. Use **$this->setParameter** method to define the data you want to pass to the payment gateway.
2. You can access the registration information via $row object. For example, $row->id is id of the registration record, $row->first_name is billing first name, $row->last_name... is billing last name
3. The payment amount can be get via **$data['amount']** variable
4. The payment description (by default, it is EVENT_TITLE event registration) could be accessed via **$data['item_name']** variable
5. The currency of the payment (if need to be passed to the payment gateway) can be get from **$data['currency']** variable
6. Some payment gateways (such as PayPal) allows you to set the URL you want to receive notification after payment completed in the payment request. If it is applied for your payment gateway, then you can pass this URL to the payment gateway inside this method, too. The URL of the payment notification URL must has this format **http://yoursitedomain.com/index.php?option=com_eventbooking&task=payment_confirm&name=os_payment_plugin_name**. For example, with PayPal payment plugin, the code is 
  ```php
  $this->setParameter('notify_url', $siteUrl . 'index.php?option=com_eventbooking&task=payment_confirm&payment_method=os_paypal');
  ```
7. After set all necesary parameters, call **$this->renderRedirectForm();** method. Events Booking will render a form which will pass all the data which you added via **$this->setParameter** method to your payment gateway

## The verifyPayment method

This is the hardest part of develpping a redirect base payment plugin. For a redirect base payment plugin, after payment completed, the payment gateway will send a notification to your site (usually a POST request) to inform Events Booking about this payment. As mentioned before, the notification URL has this format http://yoursitedomain.com/index.php?option=com_eventbooking&task=payment_gateway&name=os_payment_plugin_name. Some payment gateway allows passing this URL within processPayment method, some other payment gateway requires you to config this URL by change a setting on your merchant account inside the payemnt gateway website

When the payment gateway sends notification to the above URL, the method **verifyPayment** in the payment plugin will be executed. This method will then need to verify the payment, if it is valid, update status of the registration record, sending emails...Below is typical code structure of that method:

```php
if ($this->validate())
{
	$id            = $this->notificationData['registrant_id_param'];
	$transactionId = $this->notificationData['transaction_id_param'];

	$row = JTable::getInstance('EventBooking', 'Registrant');

	$row->load($id);

	if (!$row->id)
	{
		return false;
	}

	if ($row->published)
	{
		return false;
	}

	$this->onPaymentSuccess($row, $transactionId);
}
```

As you can see from the block of code above, it call validate method of the payment method to verify the payment. If it is valid, then you will need to get ID of the registration record, the transaction id, create registration record object..., call **$this->onPaymentSuccess($row, $transactionId);** method (which is defined on RADPayment class) to complete the process... 
