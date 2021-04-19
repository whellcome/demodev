<?php
require_once dirname(__FILE__) . '/http/client.inc.php';

class SmartResponder_Api {
	protected $serviceUrl = 'api.smartresponder.ru';
	protected $apiKey = 'zJiwaLMUJfbjEyLw9cGu84nzRhXbH6S7';
	protected $apiId = '861359';

	public function __construct() {
	}
	
	public function api($action, $params = array()) {
		$params['format'] = 'json';
		$params['api_key'] = $this->apiKey;
		$params['api_id'] = $this->apiId;
		$params['hash'] = $this->getHash($params);

		$url = "http://{$this->serviceUrl}/{$action}.html";

		$client = PSP_Net_Http_Client::getInstance();
		$client->submit($url, $params);

		return json_decode($client->results, TRUE);
	}

	protected function getHash($params) {
		$list = array();
		foreach ($params as $k=>$v) {
			$list[] = "$k=$v";
		}

		return md5(implode(':', $list));
	}

	public function deliveryList($ids = null) {
		return $this->api('deliveries', array('action' => 'list', 'id' => $ids));
	}

	public function fileList($ids = null) {
		return $this->api('files', array('action' => 'list', 'id' => $ids));
	}

	public function subscriberList($params = array()) {
		$params['action'] = 'list';
		return $this->api('subscribers', $params);
	}

	public function addSubscriber($params = array()) {
		$params['action'] = 'create';
		return $this->api('subscribers', $params);
	}

	public function updateSubscriber($id, $params = array()) {
		$params['action'] = 'update';
		$params['id'] = $id;
		return $this->api('subscribers', $params);
	}

	public function deleteSubscriber($id) {
		$params = array(
			'action' => 'delete'
			, 'id' => $id
		);

		return $this->api('subscribers', $params);
	}
}