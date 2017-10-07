<?php
/**
 * Chronolabs Fontages API
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://labs.coop
 * @license         General Public License version 3 (http://labs.coop/briefs/legal/general-public-licence/13,3.html)
 * @package         fonts
 * @since           1.0.2
 * @author          Simon Roberts <wishcraft@users.sourceforge.net>
 * @version         $Id: functions.php 1000 2013-06-07 01:20:22Z mynamesnot $
 * @subpackage		cronjobs
 * @description		Screening API Service REST
 */


 //   Scheduled Cron Job Details.,
 //   Execute:- 
 //   
 //   $ sudo crontab -e
 //   
 //   CronTab Entry:
 //   
 //   * * */1 * * /usr/bin/php -q /path/to/cronjobs/training.php


ini_set('display_errors', true);
ini_set('log_errors', true);
error_reporting(E_ERROR);
define('MAXIMUM_QUERIES', 25);
ini_set('memory_limit', '315M');
include_once dirname(dirname(__FILE__)).'/functions.php';

shell_exec(sprintf(API_SPAMTRAINING_FORGET, DIR_TRAINING_FORGET));
shell_exec(sprintf(API_SPAMTRAINING_HAM, DIR_TRAINING_HAM));
shell_exec(sprintf(API_SPAMTRAINING_SPAM, DIR_TRAINING_SPAM));

shell_exec(sprintf('rm -Rfv "%s"', DIR_TRAINING_FORGET));
shell_exec(sprintf('rm -Rfv "%s"', DIR_TRAINING_HAM));
shell_exec(sprintf('rm -Rfv "%s"', DIR_TRAINING_SPAM));

mkdir(DIR_TRAINING_FORGET, 0777, true);
mkdir(DIR_TRAINING_HAM, 0777, true);
mkdir(DIR_TRAINING_SPAM, 0777, true);


?>