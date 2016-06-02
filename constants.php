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

			
	/**
	 *
	 * @var string
	 */
	define('API_VERSION', '3.0.1');
	define('API_URL', (!isset($_SERVER["HTTP_HOST"]) ? "http://wammy.labs.coop" :
						(isset($_SERVER["HTTPS"])?'https://':'http://').$_SERVER["HTTP_HOST"]));
	define('API_URL_CALLBACK', API_URL . '/v3/%s/callback.api');
	define('API_URL_FORGET', API_URL . '/v3/forget/training.api');
	define('API_URL_SPAM', API_URL . '/v3/spam/training.api');
	define('API_URL_HAM', API_URL . '/v3/ham/training.api');
	define('API_POLINATING', (strpos(API_URL, 'localhost')||strpos(API_URL, 'labs.coop')?false:true));
	
	/**
	 *
	 * @var string
	 */
	define('DIR_SPAM_TESTING', DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'spam-testing');
	define('DIR_TRAINING_DATA', __DIR__ . DIRECTORY_SEPARATOR . 'training' . DIRECTORY_SEPARATOR . 'resource');
	define('DIR_TRAINING_FORGET', __DIR__ . DIRECTORY_SEPARATOR . 'training' . DIRECTORY_SEPARATOR . 'forget');
	define('DIR_TRAINING_SPAM', __DIR__ . DIRECTORY_SEPARATOR . 'training' . DIRECTORY_SEPARATOR . 'spam');
	define('DIR_TRAINING_HAM', __DIR__ . DIRECTORY_SEPARATOR . 'training' . DIRECTORY_SEPARATOR . 'ham');
	
	/******* DO NOT CHANGE THIS VARIABLE ****
	 * @var string
	 */
	define('API_ROOT_NODE', 'http://wammy.labs.coop');
?>