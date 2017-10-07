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

	define("API_SPAMAPP", "spamassassin");
	define("API_SPAMTESTER", "/usr/bin/spamc --full < \"%s\"");
	define("API_SPAMTRAINING_HAM", "/usr/bin/sa-learn --ham --sync --folders='%s'");
	define("API_SPAMTRAINING_SPAM", "/usr/bin/sa-learn --spam --sync --folders='%s'");
	define("API_SPAMTRAINING_FORGET", "/usr/bin/sa-learn --forget --sync --folders='%s'");

	// Image Functions
	define("API_IMAGE_CONVERT_JPG", "/usr/bin/convert -antialias -flatten -auto-gamma -auto-level -auto-orient \"%s\"  \"%s.normal.jpg\"");
	define("API_IMAGE_CONTRAST_JPG", "/usr/bin/convert -contrast -antialias -flatten -auto-gamma -auto-level -auto-orient \"%s\"  \"%s.contrast.jpg\"");
	define("API_IMAGE_TINTRED_JPG", "/usr/bin/convert -tint red -antialias -flatten -auto-gamma -auto-level -auto-orient \"%s\"  \"%s.red.jpg\"");
	define("API_IMAGE_TINTBLUE_JPG", "/usr/bin/convert -tint blue -antialias -flatten -auto-gamma -auto-level -auto-orient \"%s\"  \"%s.blue.jpg\"");
	define("API_IMAGE_TINTGREEN_JPG", "/usr/bin/convert -tint green -antialias -flatten -auto-gamma -auto-level -auto-orient \"%s\"  \"%s.green.jpg\"");
	define("API_IMAGE_TINTGREY_JPG", "/usr/bin/convert -tint grey -antialias -flatten -auto-gamma -auto-level -auto-orient \"%s\"  \"%s.grey.jpg\"");
	define("API_IMAGE_TINTBLACK_JPG", "/usr/bin/convert -tint black -antialias -flatten -auto-gamma -auto-level -auto-orient \"%s\"  \"%s.black.jpg\"");
	define("API_IMAGE_JPGTOPNM", "/usr/bin/jpegtopnm \"%s\"  \"%s.pnm\"");
	define("API_IMAGE_OCR", "/usr/bin/gocr -i \"%s\" -f UTF8");
	
	if (!is_file(__DIR__ . DIRECTORY_SEPARATOR . 'mainfile.php') || !is_file(__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'license.php'))
	{
	    header('Location: ' . "./install");
	    exit(0);
	}
	
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'constants.php';
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'mainfile.php';
	
?>