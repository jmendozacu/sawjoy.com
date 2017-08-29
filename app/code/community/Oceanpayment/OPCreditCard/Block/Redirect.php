<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category	Oceanpayment
 * @package 	Oceanpayment_CreditCard
 * @copyright	Copyright (c) 2009 Oceanpayment,LLC. (http://www.oceanpayment.com)
 */
class Oceanpayment_OPCreditCard_Block_Redirect extends Mage_Core_Block_Template
{
	/**
	 * Order instance
	 */
	protected $_order;
	
	protected $_processingArray = array('pending', 'processing', 'complete');
	/**
	 *  Get order
	 *
	 *  @param    none
	 *  @return	  Mage_Sales_Model_Order
	 */
	public function getOrder()
	{
		
		if ($this->_order == null) {
			$session = Mage::getSingleton('checkout/session');
			$this->_order = Mage::getModel('sales/order');
			$this->_order->loadByIncrementId($session->getLastRealOrderId());
		}
		return $this->_order;
	}
	
	/**
	 * toHtml
	 *
	 * @return string
	 * @deprecated after 1.4.0.1
	 */
	protected function _toHtml()
	{
		//获取配置的pay mode
		$standard = Mage::getModel('opcreditcard/payment');
		$pay_mode = $standard->getConfigData('pay_mode');

		//是否开启内嵌支付
		if($pay_mode == 1){
			$this->setTemplate('op_creditcard/redirect.phtml');
		}else{
			$this->setTemplate('op_creditcard/jumpredirect.phtml');
		}
		
		return parent::_toHtml();
	
	}

	protected function creditCardForm()
	{
		
		$standard = Mage::getModel('opcreditcard/payment');
        foreach ($standard->setOrder($this->getOrder())->getStandardCheckoutFormFields() as $field => $value) {
            $data[$field] = $value;
        }
       
        
        Mage::getSingleton('checkout/session')->unsetData('payment_details');
        
        //请求地址
        $url = $standard->getCreditCardUrl();

        //发送请求
        $post_function = $standard->getConfigData('post_function');
        if($post_function == 'socketPost'){
        	$result_data = $this->socketPost($url, $data);
        }elseif($post_function == 'Curl'){
        	$result_data = $this->curl_https($url, $data);
        }else{
        	echo 'Fucntion Error';
        	exit;
        }
       
        
        if($this->xml_parser($result_data)){

            $xml = simplexml_load_string($result_data);

            //解析xml结果
            $back_data = array(
                'account'        => (string)$xml->account,
                'terminal'       => (string)$xml->terminal,
                'signValue'      => (string)$xml->signValue,
                'order_number'   => (string)$xml->order_number,
                'order_currency' => (string)$xml->order_currency,
                'order_amount'   => (string)$xml->order_amount,
                'order_notes'    => (string)$xml->order_notes,
                'payment_id'     => (string)$xml->payment_id,
                'pay_url'        => (string)$xml->pay_url,
                'pay_results'    => (string)$xml->pay_results,
                'pay_details'    => (string)$xml->pay_details
            );

            
            if($back_data['pay_results'] == 1){

                $pay_mode = $standard->getConfigData('pay_mode');
                
                if($pay_mode == 1){
                    //内嵌
                    return $back_data['pay_url'];
                    exit;
                }else{
                    //跳转
                    $this->getJsLocationReplace($back_data['pay_url']);
                }
            }else{
            	//创建失败
                $order = $this->getOrder();

                //在网站中已经是支付成功
                if(in_array($order->getStatus(), $this->_processingArray)){
                	$order->addStatusToHistory($standard->getConfigData('order_status_payment_accepted'),
                			Mage::helper('opcreditcard')->__('[Browser Return]Payment Success!(Send Code:'.$back_data['pay_details'].')'));
                }else{
                	$order->addStatusToHistory($standard->getConfigData('order_status'),
                			Mage::helper('opcreditcard')->__('[Browser Return]Payment failed!(Send Error:'.$back_data['pay_details'].')'));
                }

                $order->save();

                Mage::getSingleton('checkout/session')->setData('payment_details', 'Send Error:'.$back_data['pay_details']);

                $this->getJsLocationReplace(Mage::getUrl('opcreditcard/payment/failure')); 
            }

        }else{
        	//请求超时
            $order = $this->getOrder();
             
            //在网站中已经是支付成功
            if(in_array($order->getStatus(), $this->_processingArray)){
            	$order->addStatusToHistory($standard->getConfigData('order_status_payment_accepted'),
            			Mage::helper('opcreditcard')->__('[Browser Return]Payment Success!'));
            }else{
            	$order->addStatusToHistory($standard->getConfigData('order_status'),
						Mage::helper('opcreditcard')->__('[Browser Return]Payment failed!(Times out)'));
            }
            

            $order->save();

            Mage::getSingleton('checkout/session')->setData('payment_details', 'Times out.');
            
            $this->getJsLocationReplace(Mage::getUrl('opcreditcard/payment/failure'));
        }
        
        
        exit;
    }
    
    
    public function iframeHeight()
    {
    	//移动端则固定540px
    	if(Mage::getSingleton('checkout/session')->getData('pages') == 1){
    		return '540';
    	}
    	
    	$model			= Mage::getModel('opcreditcard/payment');
    	$iframeHeight	= $model->getConfigData('iframe_height');
    
    	return $iframeHeight;
    
    }


    /**
     *  Socket函数
     *
     */
    function socketPost($url, $data){
        $url = parse_url($url);

        if (!$url) return "couldn't parse url";
        if (!isset($url['port'])) {
            $url['port'] = "";
        }
        if (!isset($url['query'])) {
            $url['query'] = "";
        }
        $encoded = "";
        while (list($k,$v) = each($data)) {
            $encoded .= ($encoded ? "&" : "");
            $encoded .= rawurlencode($k)."=".rawurlencode($v);
        }

        $fp = fsockopen('ssl://'.$url['host'], 443, $errno, $errstr, 120);
        if (!$fp) return false;

        fputs($fp, sprintf("POST %s%s%s HTTP/1.0\n", $url['path'], $url['query'] ? "?" : "", $url['query']));
        fputs($fp, "Host: $url[host]\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
        fputs($fp, "Content-length: " . strlen($encoded) . "\n");
        fputs($fp, "Connection: close\n\n");

        fputs($fp, "$encoded\n");

        $line = fgets($fp,1024);
        if (!preg_match("/HTTP\/1\.\d (\d{3}) ([\w\d\s+]+)/", $line)) return;

        $results = ""; $inheader = 1;
        while(!feof($fp)) {
            $line = fgets($fp,1024);
            if ($inheader && ($line == "\n" || $line == "\r\n")) {
                $inheader = 0;
            }elseif (!$inheader) {
                $results .= $line;
            }
        }
        fclose($fp);
        return $results;

    }

    /**
     *  Curl函数
     *
     */
    function curl_https($url, $data){
    	
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true); // 从证书中检查SSL加密算法是否存在
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_POST, true);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    	$response = curl_exec($ch);
    
    	if (curl_errno($ch)) {
    		echo 'Curl Error:'.curl_error($ch);//捕抓异常
    		exit;
    	}
    
    	curl_close($ch);
    
    	return $response;
    
    }
    
    
    /**
     *  判断是否为xml
     *
     */
    function xml_parser($str){
    	$xml_parser = xml_parser_create();
    	if(!xml_parse($xml_parser,$str,true)){
    		xml_parser_free($xml_parser);
    		return false;
    	}else {
    		return true;
    	}
    }
    
    /**
     *  JS
     *
     */
    public function getJsLocationReplace($url)
    {
        echo '<script type="text/javascript">parent.location.replace("'.$url.'");</script>';

    }
}