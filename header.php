<?php
/**
 * Chronolabs Spam/Ham tester+training REST API
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       	Chronolabs Cooperative http://labs.coop
 * @license         	General Public License version 3 (http://labs.coop/briefs/legal/general-public-licence/13,3.html)
 * @package         	spam-apis
 * @since           	2.0.1
 * @author          	Simon Roberts <wishcraft@users.sourceforge.net>
 * @subpackage			api
 * @description			Spam/Ham tester+training REST API
 * @see					http://sourceforge.net/projects/chronolabsapis
 * @see					http://wammy.labs.coop
 * @see					http://cipher.labs.coop
 */

	global $ipid;
	set_time_limit(3600);

	if (!defined('API_DEBUG'))
		define('API_DEBUG', false);
	/** 
	 * Access Origin Via networking Route NPN
 	 */
	header('Access-Control-Allow-Origin: *');
	header('Origin: *');
	
	/**
	 * Turns of GZ Lib Compression for Document Incompatibility
	 */
	ini_set("zlib.output_compression", 'Off');
	ini_set("zlib.output_compression_level", -1);
	
	
	require_once __DIR__.'/apiconfig.php';
	
	$parts = explode(".", microtime(true));
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	$salter = ((float)(mt_rand(0,1)==1?'':'-').$parts[1].'.'.$parts[0]) / sqrt((float)$parts[1].'.'.intval(cosh($parts[0])))*tanh($parts[1]) * mt_rand(1, intval($parts[0] / $parts[1]));
	header('Blowfish-salt: '. $salter);
	

	// Sets Session for Limits if MAXIMUM_QUERIES is defined
	session_start();
	if (!in_array(whitelistGetIP(true), whitelistGetIPAddy())&&defined("MAXIMUM_QUERIES")) {
		if (isset($_SESSION['reset']) && $_SESSION['reset']<microtime(true))
			$_SESSION['hits'] = 0;
			if ($_SESSION['hits']<=MAXIMUM_QUERIES) {
				if (!isset($_SESSION['hits']) || $_SESSION['hits'] = 0)
					$_SESSION['reset'] = microtime(true) + 3600;
					$_SESSION['hits']++;
			} else {
				header("HTTP/1.0 404 Not Found");
				exit;
			}
	}
	
	
	/**
	 * URI Path Finding of API URL Source Locality
	 * @var string
	 */
	$ipid = getIPIdentity(whitelistGetIP(true), false);
	header('Internet-Protocol-Identity: '. $ipid);
	
