<?php


/**
 * @package   Zcore
 * @author      Artem Sokolovsky
 */


// ~~~~~~~~~~~~~~~~~~~~Ядро для обработки пополнений через api на smsbill.com.ua~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

class payment {
	
	private $_serviceId;
	private $_formUrl = 'http://form.smsbill.com.ua/';
	private $_apiUrl = 'http://api.smsbill.com.ua/';
	private $_useCss = false;
	private $_useEncoding = 'UTF-8';
	private $_requestResult = '';
	private $_useHeader = 'yes';
	private $_useJQuery = 'yes';
	private $_useLang = 'ru';
	
public function setServiceId($serviceId) {
	
	$this->_serviceId = $serviceId;
	
}
	
public function useCss($cssUrl) {
	
	$this->_useCss = $cssUrl;
	
}
	
public function useEncoding($encoding){
	
	$this->_useEncoding = $encoding;
}
	
public function useHeader($header) {
	
	$this->_useHeader = $header;	
	
}

public function useJQuery($jquery) {

	$this->_useJQuery = $jquery;	
	
}

public function useLang($lang) {

	$this->_useLang = $lang;
	
}

public function getForm() {

	if (empty($this->_serviceId)) {
	die('ServiceId Not Setted');
	}
	$url = $this->_formUrl . 'getform.php?id=' . $this->_serviceId;
	$url .= '&encoding=' . $this->_useEncoding;
	$url .= '&header=' . $this->_useHeader;
	$url .= '&jquery=' . $this->_useJQuery;
	if (empty($_REQUEST['lang'])){
	$url .= '&lang='.$this->_useLang;}else{
	$url .= '&lang='.$_REQUEST['lang'];}
	$url .= '&ip=' . $_SERVER['REMOTE_ADDR'];
	if ($this->_useCss) {
	$url .= '&css=' . urlencode($this->_useCss);
	}
	$result = $this->_getUrl($url);
	return $result;
	
}

public function checkPassword($password) {
	
	if (empty($this->_serviceId)) {
	die('ServiceId Not Setted');
	}
	$url = $this->_apiUrl . 'check.php?id=' . $this->_serviceId;
	$url .= '&ip=' . $_SERVER['REMOTE_ADDR'];
	$url .= '&password=' . $password;
	$result = $this->_getUrl($url);
	if (!$result) {
	return false; 
	}
	if ($result == 'ok') {
	return true;
	}
	return false;
	
}


private function _getUrl($url) {
	
	if (ini_get('allow_url_fopen')) {
	$this->_requestResult = file_get_contents($url); 
	return $this->_requestResult;
	}
	if (extension_loaded('curl')) {
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$this->_requestResult = @curl_exec($curl);
	curl_close($curl);
	return $this->_requestResult;
	}
	die('allow_url_fopen is false and cURL extension is not loaded. No way to communicate with SMSBill server.');	
	
}

}
	
?>