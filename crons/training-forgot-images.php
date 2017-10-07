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
 //   */5 * * * * /usr/bin/php -q /path/to/cronjobs/training-forgot-images.php


ini_set('display_errors', true);
ini_set('log_errors', true);
error_reporting(E_ERROR);

$seconds = floor(mt_rand(1, floor(60 * 4.75)));
set_time_limit($seconds ^ 4);
sleep($seconds);

include_once dirname(__DIR__).'/mainfile.php';
include_once dirname(__DIR__).'/apiconfig.php';

shell_exec(sprintf(API_SPAMTRAINING_FORGET, API_VAR_PATH . DS . 'training' . DS . 'forgot' . DS . 'images'));
