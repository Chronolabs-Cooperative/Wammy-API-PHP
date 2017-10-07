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
     * @var integer
     */
    define('API_CACHE_SECONDS', 844);
    define('API_DELAY_SECONDS', floor(6.543 * 60));
    
	/**
	 *
	 * @var string
	 */
	define('API_URL_CALLBACK', API_URL . '/v4/%s/callback.api');
	define('API_URL_FORGET', API_URL . '/v4/forget/training.api');
	define('API_URL_SPAM', API_URL . '/v4/spam/training.api');
	define('API_URL_HAM', API_URL . '/v4/ham/training.api');
	define('API_URL_IMAGE_FORGET', API_URL . '/v4/forget/training/image.api');
	define('API_URL_IMAGE_SPAM', API_URL . '/v4/spam/training/image.api');
	define('API_URL_IMAGE_HAM', API_URL . '/v4/ham/training/image.api');
	
	/**
	 *
	 * @var string
	 */
	define('DIR_SPAM_TESTING', API_VAR_PATH . DIRECTORY_SEPARATOR . 'spam-testing');
	define('DIR_TRAINING_DATA', API_PATH . DIRECTORY_SEPARATOR . 'training' . DIRECTORY_SEPARATOR . 'resource');
	define('DIR_TRAINING_FORGET', API_PATH . DIRECTORY_SEPARATOR . 'training' . DIRECTORY_SEPARATOR . 'forget');
	define('DIR_TRAINING_SPAM', API_PATH . DIRECTORY_SEPARATOR . 'training' . DIRECTORY_SEPARATOR . 'spam');
	define('DIR_TRAINING_HAM', API_PATH . DIRECTORY_SEPARATOR . 'training' . DIRECTORY_SEPARATOR . 'ham');
	
	/******* DO NOT CHANGE THIS VARIABLE ****
	 * @var string
	 */
	define('API_ROOT_NODE', 'http://wammy.snails.email');
?>
