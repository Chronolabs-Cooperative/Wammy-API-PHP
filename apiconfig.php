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

?>