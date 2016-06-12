<?php
/**
 * @version            2.7.0
 * @package            Joomla
 * @subpackage         Event Booking
 * @author             Tuan Pham Ngoc
 * @copyright          Copyright (C) 2010 - 2016 Ossolution Team
 * @license            GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;

class os_redirect extends RADPayment
{
	/**
	 * Constructor functions, init some parameter
	 *
	 * @param object $params
	 */
	public function __construct($params, $config = array())
	{
		parent::__construct($params, $config);


		/*if ($params->get('mode'))
		{
			$this->url = 'the_payment_gateway_url_in_live_mode';
		}
		else
		{
			$this->url = 'the_payment_gateway_url_in_test_mode';
		}

		$this->setParameter('merchant_id', $params->get('merchant_id'));*/

		// Additional constructor code goes here
	}

	/**
	 * Process Payment
	 *
	 * @param object $row
	 * @param array  $data
	 */
	public function processPayment($row, $data)
	{
		/**
		 * Call $this->setParameter method to pass the data to your payment gateway. Each payment gateway requires
		 * different parameters, so please read your payment gateway manual for to see the data you have to pass to the payment gateway.
		 *
		 * Below are sample code:
		 */

		/*$this->setParameter('amount', round($data['amount'], 2));
		$this->setParameter('description', $data['item_name']);
		$this->setParameter('currency_code', $data['currency']);*/

		/**
		 * Then call $this->renderRedirectForm() method, Events Booking will render a form with hidden parameters to submit
		 * the data (which you passed using setParameter method to your payment gateway.
		 */

		//$this->renderRedirectForm();
	}

	/**
	 * Verify payment
	 *
	 * @return bool
	 */
	public function verifyPayment()
	{
		if ($this->validate())
		{
			/*$id            = $this->notificationData['registrant_id_param'];
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

			$this->onPaymentSuccess($row, $transactionId);*/
		}
	}


	/**
	 * Validate the post data from Payment gateway to our server
	 *
	 * @return string
	 */
	protected function validate()
	{
		$this->notificationData = $_REQUEST;

		// Validate the callback data, return true if it is valid and false otherwise
		return true;
	}
}