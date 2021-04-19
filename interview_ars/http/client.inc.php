<?php
/**
 * PrimalSite CMS Project
 *
 * @package net.http
 * @version $Id$
 * @author Peredelskiy Aleksey <casbah@yandex.ru>
 */
require_once dirname(__FILE__) . '/Snoopy.class.php';

/**
 *	Http client class
 *	This class is proxy for Snoopy http class.
 *
 *	@package core
 */
class PSP_Net_Http_Client extends Snoopy {
	public static function getInstance() {
		return new PSP_Net_Http_Client();
	}
}