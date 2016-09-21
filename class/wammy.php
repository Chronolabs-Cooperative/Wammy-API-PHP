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
 * Database Constants
 *
 * @abstract
 * @author     Simon Roberts <wishcraft@users.sourceforge.net>
 * @package    places
 * @subpackage database
 */


/**
 * @var string		Database Name (Database Source One)
 */
define('DB_WAMMY_NAME', 'wammy-labs-coop');

/**
 * @var string		Database Username (Database Source One)
 */
define('DB_WAMMY_USER', 'wammy-labs-coop');

/**
 * @var string		Database Password (Database Source One)
 */
define('DB_WAMMY_PASS', 'MAFMI9CZ9uSFOHSq');

/**
 * @var string		Database Host Address/IP (Database Source One)
 */
define('DB_WAMMY_HOST', 'localhost');

/**
 * @var string		Database Character Set (Global)
 */
define('DB_WAMMY_CHAR', 'utf8');

/**
 * @var string		Database Persistency Connection (Global)
 */
define('DB_WAMMY_PERS', false);

/**
 * @var string		Database Types (Global)
 */
define('DB_WAMMY_TYPE', 'mysql');

/**
 * @var string		Database Prefix (Global)
 */
define('DB_WAMMY_PREF', '');

require_once dirname(__FILE__) . '/database.php';
require_once dirname(__FILE__) . '/databasefactory.php';

/**
 * @var object		Database Handler Object (Globals)
 */
$GLOBALS['WammyDB'] = WammyDatabaseFactory::getDatabaseConnection();

?>
