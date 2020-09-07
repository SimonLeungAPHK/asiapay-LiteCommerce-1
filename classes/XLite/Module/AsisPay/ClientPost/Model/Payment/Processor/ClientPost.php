<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    AsiaPay Limited
 * @copyright Copyright (c) 2012 AsiaPay Limited. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Module\AsiaPay\ClientPost\Model\Payment\Processor;

/**
 * AsiaPay ClientPost processor
 *
 * @see   ____class_see____
 * @since 1.0.11
 */
class ClientPost extends \XLite\Model\Payment\Base\WebBased
{
    /**
     * Get operation types
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getOperationTypes()
    {
		return array(
            self::OPERATION_SALE,
            self::OPERATION_AUTH,
        );
    }

    /**
     * Get settings widget or template
     *
     * @return string Widget class name or template path
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSettingsWidget()
    {
		/**
		* Path: ./skins/admin/en/modules/AsiaPay/ClientPost/config.tpl
		*/
        return 'modules/AsiaPay/ClientPost/config.tpl';
    }

    /**
     * Process return
     *
     * @param \XLite\Model\Payment\Transaction $transaction Return-owner transaction
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function processReturn(\XLite\Model\Payment\Transaction $transaction) 
	{
		parent::processReturn($transaction);
		
		$request = \XLite\Core\Request::getInstance();

        if ($request->cancel) {
			$msg = 'Payment CANCELLED.' ;
			$this->setDetail('response', $msg, 'Response');	
			$this->transaction->setNote($msg);

            $this->transaction->setStatus($transaction::STATUS_FAILED);
		}
	}
	
	/**
	* Process callback
	*
	* @param \XLite\Model\Payment\Transaction $transaction Callback-owner transaction
	*
	* @return void
	* @see ____func_see____
	* @since 1.0.0
	*/	
	public function processCallback(\XLite\Model\Payment\Transaction $transaction)
    {
        parent::processReturn($transaction);

        $request = \XLite\Core\Request::getInstance();
		
		//set data from AsiaPay to variables		
		$successCode = $request->successcode;		
		$payRef =  $request->PayRef;
		
		//message to AsiaPay that data has been recieved
		echo 'OK';
		
		//evalute the payment status
		if($successCode == '0'){
			$status = $transaction::STATUS_SUCCESS;		
			$msg = 'Payment ACCEPTED at AsiaPay. Payment Reference No: '. $payRef ;
			$this->setDetail('response', $msg, 'Response');			  
            $this->transaction->setNote($msg);
		}else{
			$status = $transaction::STATUS_FAILED;
			$msg = 'Payment REJECTED at AsiaPay. Payment Reference No: '. $payRef ;
			$this->setDetail('response', $msg, 'Response');			  
            $this->transaction->setNote($msg);
		}
		
		$this->transaction->setStatus($status);
    }
	
    /**
     * Check - payment method is configured or not
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isConfigured(\XLite\Model\Payment\Method $method)
    {
        return parent::isConfigured($method)
            && $method->getSetting('payment_url')
			&& $method->getSetting('merchant_id');
    }

    /**
     * Get return type
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getReturnType()
    {
        return self::RETURN_TYPE_HTML_REDIRECT;
    }

    /**
     * Returns the list of settings available for this payment processor
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.5
     */
    public function getAvailableSettings()
    {
        return array(
            'payment_url',
            'merchant_id',
            'curr_code',
            'pay_type',
            'pay_method',
            'language',
			'prefix',
            'secure_hash_secret',
            'transaction_type',
            'challenge_preference',
        );
    }

    /**
     * Get return request owner transaction or null
     *
     * @return \XLite\Model\Payment\Transaction|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getReturnOwnerTransaction()
    {
        $transactionId = \XLite\Core\Request::getInstance()->Ref;

        return \XLite\Core\Database::getRepo('XLite\Model\Payment\Transaction')
            ->find($transactionId);
    }

    /**
     * Get redirect form URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormURL()
    {
        return $this->getSetting('payment_url');
    }

    /**
     * Return formatted price.
     *
     * @param integer $price Price value
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function getFormattedPrice($price)
    {
        return sprintf("%.2f", round((double)($price) + 0.00000000001, 2));
    }


    /**
     * Get redirect form fields list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormFields()
    {
        $fields = array(            
            'amount'                => $this->getFormattedPrice($this->transaction->getValue()),
            'orderRef'			    => $this->getSetting('prefix') . $this->getOrder()->getOrderId(),
			'merchantId'            => $this->getSetting('merchant_id'),
			'currCode'              => $this->getSetting('curr_code'),
			'payType'	            => $this->getSetting('pay_type'),
            'payMethod'             => $this->getSetting('pay_method'),			
            'lang'                  => $this->getSetting('language'),
			'successUrl'            => $this->getReturnURL('txn_id', true), //used by processReturn
			'failUrl'               => $this->getReturnURL('txn_id', true), //used by processReturn
			'cancelUrl'             => $this->getReturnURL('txn_id', true, true), //used by processReturn
			'remark'				=> $this->transaction->getTransactionId(), //used by processCallback (datafeed)
        );
        // echo "<pre>";
		// $a = $this->getOrder();
        $b = $this->getBillAddress();

        $s = $this->getShipAddress();

        $isSameAddress = $this->isSameBillShipAddress($b,$s);
        $shipDetl = $isSameAddress ? '01' : '03';
        $arrBData['threeDSIsAddrMatch'] = $isSameAddress;

        // print_r($b);
        // print_r($s);
        // exit;

        $secureHash = $this->getSecureHash($fields);

        $fields3DS2 = array(
            'threeDSTransType' => $this->getSetting('transaction_type'),
            'threeDSChallengePreference' => $this->getSetting('challenge_preference'),
        );


        $fields = array_merge($fields,$secureHash,$fields3DS2,$b,$s);
		/*
		SAMPLE: 
		successUrl: http://localhost/litecommerce/cart.php?target=payment_return&txn_id=7&txn_id_name=txn_id		
		failUrl: http://localhost/litecommerce/cart.php?target=payment_return&txn_id=7&txn_id_name=txn_id		
		cancelUrl: http://localhost/litecommerce/cart.php?target=payment_return&cancel=1&txn_id_name=txn_id&txn_id=7
		datafeed URL: http://www.your_litecommerce_site.com/cart.php?target=callback&txn_id_name=remark
		*/

        return $fields;
    }

    /**
     * Get allowed currencies
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.9
     */
    protected function getAllowedCurrencies(\XLite\Model\Payment\Method $method)
    {
		$curr_code = $method->getSetting('curr_code');
		
		if($curr_code == '344'){
			$currency = 'HKD';
		}else if($curr_code == '840'){
			$currency = 'USD';
		}else if($curr_code == '720'){
			$currency = 'SGD';
		}else if($curr_code == '156'){
			$currency = 'CNY';
		}else if($curr_code == '392'){
			$currency = 'JPY';
		}else if($curr_code == '901'){
			$currency = 'TWD';
		}else if($curr_code == '036'){
			$currency = 'AUD';
		}else if($curr_code == '978'){
			$currency = 'EUR';
		}else if($curr_code == '826'){
			$currency = 'GBP';
		}else if($curr_code == '124'){
			$currency = 'CAD';
		}else if($curr_code == '446'){
			$currency = 'MOP';
		}else if($curr_code == '608'){
			$currency = 'PHP';
		}else if($curr_code == '764'){
			$currency = 'THB';
		}else if($curr_code == '458'){
			$currency = 'MYR';
		}else if($curr_code == '360'){
			$currency = 'IDR';
		}else if($curr_code == '410'){
			$currency = 'KRW';
		}else if($curr_code == '682'){
			$currency = 'SAR';
		}else if($curr_code == '554'){
			$currency = 'NZD';
		}else if($curr_code == '784'){
			$currency = 'AED';
		}else if($curr_code == '096'){
			$currency = 'BND';
		}else{
			$currency = 'USD';
		}
		
        return array_merge(
            parent::getAllowedCurrencies($method),
            array($currency)
        );
    }


    protected function getSecureHash($arrFields){
        foreach ($arrFields as $key => $value) {
            $$key = $value;
        }
        $data = array();

        $secureHash = $this->generatePaymentSecureHash ( $merchantId, $orderRef, $currCode, $amount, $payType, $this->getSetting('secure_hash_secret') );
        $data['secureHash'] = $secureHash;
        return $data;
    }


    protected function generatePaymentSecureHash($merchantId, $merchantReferenceNumber, $currencyCode, $amount, $paymentType, $secureHashSecret) {

        $buffer = $merchantId . '|' . $merchantReferenceNumber . '|' . $currencyCode . '|' . $amount . '|' . $paymentType . '|' . $secureHashSecret;
        //echo $buffer;
        return sha1($buffer);

    }

    protected function verifyPaymentDatafeed($src, $prc, $successCode, $merchantReferenceNumber, $paydollarReferenceNumber, $currencyCode, $amount, $payerAuthenticationStatus, $secureHashSecret, $secureHash) {

        $buffer = $src . '|' . $prc . '|' . $successCode . '|' . $merchantReferenceNumber . '|' . $paydollarReferenceNumber . '|' . $currencyCode . '|' . $amount . '|' . $payerAuthenticationStatus . '|' . $secureHashSecret;

        $verifyData = sha1($buffer);

        if ($secureHash == $verifyData) {
            return true;
        }

        return false;

    }

    protected function getBillAddress(){
        $arrBData = array();
        $b = $this->getOrder()->getProfile()->getBillingAddress();
        $arrBData['threeDSBillingLine1'] = $b->getStreet();
        // $arrBData['threeDSBillingLine2'] = $orders->fields['billing_suburb'];
        $arrBData['threeDSBillingCity'] = $b->getCity();
        $arrBData['threeDSBillingCountryCode'] = $b->getCountry()->getId();
        $arrBData['threeDSBillingPostalCode'] = $b->getZipcode();
        // $address = $profile->getBillingAddress();

        $arrBData['threeDSMobilePhoneNumber'] = $arrBData['threeDSHomePhoneNumber'] = $arrBData['threeDSWorkPhoneNumber'] = $b->getPhone();//threeDSHomePhoneNumber threeDSWorkPhoneNumber
        $country = $this->getCountryCallAPI($b->getCountry()->getCode3());
        
        if(count($country)>0)
        $phoneCountryCode = $country->callingCodes[0];

        $arrBData['threeDSMobilePhoneCountryCode'] = $arrBData['threeDSHomePhoneCountryCode'] = $arrBData['threeDSWorkPhoneCountryCode'] = $phoneCountryCode;

        // $arrBData['threeDSIsAddrMatch'] = $b->isSameAddress();

        return $arrBData;
    }

    protected function getShipAddress(){
        $arrSData = array();
        $s = $this->getOrder()->getProfile()->getBillingAddress();
        $arrSData['threeDSShippingLine1'] = $s->getStreet();
        // $arrBData['threeDSBillingLine2'] = $orders->fields['billing_suburb'];
        $arrSData['threeDSShippingCity'] = $s->getCity();
        $arrSData['threeDSShippingCountryCode'] = $s->getCountry()->getId();
        $arrSData['threeDSShippingPostalCode'] = $s->getZipcode();
        // $address = $profile->getBillingAddress();
        $arrSData['threeDSDeliveryEmail'] = $arrSData['threeDSCustomerEmail'] = $this->getOrder()->getProfile()->getLogin();
        return $arrSData;
    }

    protected function getCountryCallAPI($country){
        $method = "GET";
        $url = "https://restcountries.eu/rest/v2/alpha/$country";
        
        // $data = array('codes'=>$countryCode);
        $data = false;

        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return json_decode($result);

    }

    protected function isSameBillShipAddress($b,$s){

        $cnt = 0;

        if($b['threeDSBillingLine1'] == $s['threeDSShippingLine1'])$cnt++;
        if($b['threeDSBillingCity'] == $s['threeDSShippingCity'])$cnt++;
        if($b['threeDSBillingCountryCode'] == $s['threeDSShippingCountryCode'])$cnt++;
        if($b['threeDSBillingPostalCode'] == $s['threeDSShippingPostalCode'])$cnt++;
        
        if($cnt==4)return "T";
        else return "F";

  }

}