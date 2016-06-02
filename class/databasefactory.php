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
 * Database Class Factory
 *
 * @abstract
 * @author     Simon Roberts <wishcraft@users.sourceforge.net>
 * @package    places
 * @subpackage database
 */
class WammyDatabaseFactory
{

    /**
     * Get a reference to the only instance of database class and connects to DB
     *
     * if the class has not been instantiated yet, this will also take
     * care of that
     *
     * @static
     * @staticvar WammyDatabase The only instance of database class
     * @return WammyDatabase Reference to the only instance of database class
     */
    static function getDatabaseConnection()
    {
        static $instance;
        if (!isset($instance)) {
            if (file_exists($file = dirname(__FILE__) . '/' . DB_WAMMY_TYPE . 'database.php')) {
                require_once $file;

                if (!defined('DB_WAMMY_PROXY')) {
                    $class = 'Wammy' . ucfirst(DB_WAMMY_TYPE) . 'DatabaseSafe';
                } else {
                    $class = 'Wammy' . ucfirst(DB_WAMMY_TYPE) . 'DatabaseProxy';
                }

                /* @var $instance WammyDatabase */
                $instance = new $class();
                $instance->setPrefix(DB_WAMMY_PREF);
                if (!$instance->connect()) {
                    trigger_error('notrace:Unable to connect to database', E_USER_ERROR);
                }
            } else {
                trigger_error('notrace:Failed to load database of type: ' . DB_WAMMY_TYPE . ' in file: ' . __FILE__ . ' at line ' . __LINE__, E_USER_WARNING);
            }
        }
        return $instance;
    }

    /**
     * Gets a reference to the only instance of database class. Currently
     * only being used within the installer.
     *
     * @static
     * @staticvar WammyDatabase The only instance of database class
     * @return WammyDatabase Reference to the only instance of database class
     */
    static function getDatabase()
    {
        static $database;
        if (!isset($database)) {
            if (file_exists($file = dirname(__FILE__) . '/' . DB_WAMMY_TYPE . 'database.php')) {
                include_once $file;
                if (!defined('DB_WAMMY_PROXY')) {
                    $class = 'Wammy' . ucfirst(DB_WAMMY_TYPE) . 'DatabaseSafe';
                } else {
                    $class = 'Wammy' . ucfirst(DB_WAMMY_TYPE) . 'DatabaseProxy';
                }
                unset($database);
                $database = new $class();
            } else {
                trigger_error('notrace:Failed to load database of type: ' . DB_WAMMY_TYPE . ' in file: ' . __FILE__ . ' at line ' . __LINE__, E_USER_WARNING);
            }
        }
        return $database;
    }
}