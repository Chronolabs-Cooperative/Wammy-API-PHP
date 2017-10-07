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

require_once __DIR__ . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'wammy.php';
require_once __DIR__ . '/constants.php';

if (!function_exists("setCallBackURI")) {

	/* function getURIData()
	 *
	 * 	cURL Routine
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 *
	 * @return 		float()
	 */
	function setCallBackURI($uri = '', $timeout = 65, $connectout = 65, $data = array(), $queries = array())
	{
		list($when) = $GLOBALS['APIDB']->fetchRow($GLOBALS['trackerDB']->queryF("SELECT `when` from `callbacks` ORDER BY `when` DESC LIMIT 1"));
		if ($when<time())
			$when = $time();
		$when = $when + mt_rand(3, 14);
		return $GLOBALS['APIDB']->queryF("INSERT INTO `callbacks` (`when`, `uri`, `timeout`, `connection`, `data`, `queries`) VALUES(\"$when\", \"$uri\", \"$timeout\", \"$connectout\", \"" . mysqli_real_escape_string(json_encode($data)) . "\",\"" . mysqli_real_escape_string(json_encode($queries)) . "\")");
	}
}

if (!function_exists("putRawFile")) {
	/**
	 *
	 * @param string $file
	 * @param string $data
	 */
	function writeRawFile($file = '', $data = '')
	{
		$lineBreak = "\n";
		if (substr(PHP_OS, 0, 3) == 'WIN') {
			$lineBreak = "\r\n";
		}
		if (!is_dir(dirname($file)))
			mkdir(dirname($file), 0777, true);
		if (is_file($file))
			unlink($file);
		$data = str_replace(array(PHP_EOL, "\n"), $lineBreak, $data);
		$ff = fopen($file, 'w');
		fwrite($ff, $data, strlen($data));
		fclose($ff);
	}
}


if (!function_exists("getHTMLForm")) {
	/**
	 *
	 * @param unknown_type $mode
	 * @param unknown_type $clause
	 * @param unknown_type $output
	 * @param unknown_type $version
	 * @return string
	 */
	function getHTMLForm($mode = '', $clause = '', $callback = '', $output = '', $version = 'v2')
	{
		$ua = substr(sha1($_SERVER['HTTP_USER_AGENT']), mt_rand(0,30), 11);
		$form = array();
		switch ($mode)
		{
			case "test":
				$form[] = "<form name=\"" . $ua . "\" method=\"POST\" action=\"" . API_URL . '/v4/' .$ua . "/test.api\">";
				$form[] = "\t<table class='spam-tester' id='spam-tester' style='vertical-align: top !important; min-width: 98%;'>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='sender-username'>Sender's Username:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<input type='textbox' name='usernames[sender]' id='sender-username' maxlen='64' size='29' value='".(isset($_REQUEST['usernames']['sender'])?$_REQUEST['usernames']['sender']:"")."'/>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='sender-email'>Sender's Email:</label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<input type='textbox' name='emails[sender]' id='sender-email' maxlen='198' size='34' value='".(isset($_REQUEST['emails']['sender'])?$_REQUEST['emails']['sender']:"")."'/>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='sender-name'>Sender's Name:</label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<input type='textbox' name='names[sender]' id='sender-name' maxlen='100' size='29' value='".(isset($_REQUEST['names']['sender'])?$_REQUEST['names']['sender']:"")."'/>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='sender-ip'>Sender's IP Address:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<input type='textbox' name='sender-ip' id='sender-ip' maxlen='128' size='42' value='".(isset($_REQUEST['sender-ip'])?$_REQUEST['sender-ip']:whitelistGetIP(true))."'/>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='recipient-username'>Recipient's Username:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<input type='textbox' name='usernames[recipient]' id='recipient-username' maxlen='64' size='29' value='".(isset($_REQUEST['usernames']['recipient'])?$_REQUEST['usernames']['recipient']:"")."'/>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='recipient-email'>Recipient's Email:</label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<input type='textbox' name='emails[recipient]' id='recipient-email' maxlen='198' size='34' value='".(isset($_REQUEST['emails']['recipient'])?$_REQUEST['emails']['recipient']:"")."'/>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='recipient-name'>Recipient's Name:</label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<input type='textbox' name='names[recipient]' id='recipient-name' maxlen='100' size='29' value='".(isset($_REQUEST['names']['recipient'])?$_REQUEST['names']['recipient']:"")."'/>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='subject'>Tested Subject:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<input type='textbox' name='subject' id='subject' maxlen='500' size='51' value='".(isset($_REQUEST['subject'])?$_REQUEST['subject']:"")."'/>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<label for='message'>Tested HTML/Textual:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<textarea name='message' id='message' cols='64' rows='11'>".(isset($_REQUEST['message'])?$_REQUEST['message']:"")."</textarea>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='mimetype'>MimeType:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<select name='mimetype' id='mimetype'>";
				$form[] = "\t\t\t\t\t<option value='text/html'".(isset($_REQUEST['mimetype'])&&$_REQUEST['mode']=='text/html'?' selected':'').">HTML Data (text/html)</option>";
				$form[] = "\t\t\t\t\t<option value='text/plain'".(!isset($_REQUEST['mimetype'])||$_REQUEST['mode']=='text/plain'?' selected':'').">Plain Text Data (text/plain)</option>";
				$form[] = "\t\t\t\t</select>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='mode'>API Output Mode:&nbsp;</label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<select name='mode' id='mode'>";
				$form[] = "\t\t\t\t\t<option value='return'".(isset($_REQUEST['mode'])&&$_REQUEST['mode']=='return'?' selected':'').">Send to Return URL (assume callback specified)</option>";
				$form[] = "\t\t\t\t\t<option value='xml'".(isset($_REQUEST['mode'])&&$_REQUEST['mode']=='xml'?' selected':'').">XML Output Data</option>";
				$form[] = "\t\t\t\t\t<option value='serial'".(isset($_REQUEST['mode'])&&$_REQUEST['mode']=='serial'?' selected':'').">PHP Serialisation</option>";
				$form[] = "\t\t\t\t\t<option value='json'".(!isset($_REQUEST['mode'])||$_REQUEST['mode']=='json'?' selected':'').">JSON Data (default)</option>";
				$form[] = "\t\t\t\t</select>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td colspan='3' style='padding-left:64px;'>";
				$form[] = "\t\t\t\t<input type='hidden' name='return' value='" . (isset($_REQUEST['return'])?$_REQUEST['return']:API_URL)."'>";
				$form[] = "\t\t\t\t<input type='hidden' name='callback' value='" . (isset($_REQUEST['callback'])?$_REQUEST['callback']:"") ."'>";
				$form[] = "\t\t\t\t<div style='clear: none; width: auto; margin-right: 59px; float: right;'><font style='color: rgb(250,0,0); font-size: 169%; font-weight: bold'>*</font>&nbsp;<font style='color: rgb(0,0,0); font-size: 152%; font-weight: bold'><em>Required/Essential Fields</em></font></div>";
				$form[] = "\t\t\t\t<input type='submit' value='Run Spam Test' name='submit' style='padding:11px; font-size:122%;'>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t</table>";
				$form[] = "</form>";
				break;
			case "training":
				$form[] = "<form name=\"" . $ua . "\" method=\"POST\" action=\"" . API_URL . '/v4/' .$ua . "/training.api\">";
				$form[] = "\t<table class='spam-training' id='spam-training' style='vertical-align: top !important; min-width: 98%;'>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='sender-username'>Sender Username:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<input type='textbox' name='usernames[sender]' id='sender-username' maxlen='64' size='29' value='".(isset($_REQUEST['usernames']['sender'])?$_REQUEST['usernames']['sender']:"")."'/>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='sender-email'>Sender's Email:</label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<input type='textbox' name='emails[sender]' id='sender-email' maxlen='198' size='34' value='".(isset($_REQUEST['emails']['sender'])?$_REQUEST['emails']['sender']:"")."'/>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='sender-name'>Sender's Name:</label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<input type='textbox' name='names[sender]' id='sender-name' maxlen='100' size='29' value='".(isset($_REQUEST['names']['sender'])?$_REQUEST['names']['sender']:"")."'/>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='sender-ip'>Sender's IP Address:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<input type='textbox' name='sender-ip' id='sender-ip' maxlen='128' size='42' value='".(isset($_REQUEST['sender-ip'])?$_REQUEST['sender-ip']:whitelistGetIP(true))."'/>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='recipient-username'>Recipient's Username:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<input type='textbox' name='usernames[recipient]' id='recipient-username' maxlen='64' size='29' value='".(isset($_REQUEST['usernames']['sender'])?$_REQUEST['usernames']['sender']:"")."'/>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='recipient-email'>Recipient's Email:</label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<input type='textbox' name='emails[recipient]' id='recipient-email' maxlen='198' size='34' value='".(isset($_REQUEST['emails']['recipient'])?$_REQUEST['emails']['recipient']:"")."'/>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='recipient-name'>Recipient's Name:</label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<input type='textbox' name='names[recipient]' id='recipient-name' maxlen='100' size='29' value='".(isset($_REQUEST['names']['recipient'])?$_REQUEST['names']['recipient']:"")."'/>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='subject'>Trained Subject:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<input type='textbox' name='subject' id='subject' maxlen='500' size='51' value='".(isset($_REQUEST['subject'])?$_REQUEST['subject']:"")."'/>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<label for='message'>Trained HTML/Textual:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<textarea name='message' id='message' cols='64' rows='11'>".(isset($_REQUEST['message'])?$_REQUEST['message']:"")."</textarea>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='mimetype'>MimeType:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<select name='mimetype' id='mimetype'>";
				$form[] = "\t\t\t\t\t<option value='text/html'".(isset($_REQUEST['mimetype'])&&$_REQUEST['mode']=='text/html'?' selected':'').">HTML Data (text/html)</option>";
				$form[] = "\t\t\t\t\t<option value='text/plain'".(!isset($_REQUEST['mimetype'])||$_REQUEST['mode']=='text/plain'?' selected':'').">Plain Text Data (text/plain)</option>";
				$form[] = "\t\t\t\t</select>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td style='width: 320px;'>";
				$form[] = "\t\t\t\t<label for='mode'>Training Data Action:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t<select name='mode' id='mode'>";
				$form[] = "\t\t\t\t\t<option value='ham'>None Spam HTML/Textual</option>";
				$form[] = "\t\t\t\t\t<option value='spam'>Spam HTML/Textual</option>";
				$form[] = "\t\t\t\t\t<option value='forget'>Forget Training with HTML/Textual</option>";
				$form[] = "\t\t\t\t</select>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t\t&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td colspan='3' style='padding-left:64px;'>";
				$form[] = "\t\t\t\t<input type='hidden' name='return' value='" . (isset($_REQUEST['return'])?$_REQUEST['return']:API_URL)."'>";
				$form[] = "\t\t\t\t<input type='hidden' name='callback' value='" . (isset($_REQUEST['callback'])?$_REQUEST['callback']:"") ."'>";
				$form[] = "\t\t\t\t<div style='clear: none; width: auto; margin-right: 59px; float: right;'><font style='color: rgb(250,0,0); font-size: 169%; font-weight: bold'>*</font>&nbsp;<font style='color: rgb(0,0,0); font-size: 152%; font-weight: bold'><em>Required/Essential Fields</em></font></div>";
				$form[] = "\t\t\t\t<input type='submit' value='Spam/Ham/Forget Training' name='submit' style='padding:11px; font-size:122%;'>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t</table>";
				$form[] = "</form>";
				break;
				
			case "image-test":
			    $form[] = "<form name=\"" . $ua . "\" method=\"POST\" action=\"" . API_URL . '/v4/' .$ua . "/test/image.api\">";
			    $form[] = "\t<table class='spam-tester' id='spam-tester' style='vertical-align: top !important; min-width: 98%;'>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td style='width: 320px;'>";
			    $form[] = "\t\t\t\t<label for='sender-username'>Sender's Username:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<input type='textbox' name='usernames[sender]' id='sender-username' maxlen='64' size='29' value='".(isset($_REQUEST['usernames']['sender'])?$_REQUEST['usernames']['sender']:"")."'/>&nbsp;&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t</tr>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td style='width: 320px;'>";
			    $form[] = "\t\t\t\t<label for='sender-email'>Sender's Email:</label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<input type='textbox' name='emails[sender]' id='sender-email' maxlen='198' size='34' value='".(isset($_REQUEST['emails']['sender'])?$_REQUEST['emails']['sender']:"")."'/>&nbsp;&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t</tr>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td style='width: 320px;'>";
			    $form[] = "\t\t\t\t<label for='sender-name'>Sender's Name:</label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<input type='textbox' name='names[sender]' id='sender-name' maxlen='100' size='29' value='".(isset($_REQUEST['names']['sender'])?$_REQUEST['names']['sender']:"")."'/>&nbsp;&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t</tr>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td style='width: 320px;'>";
			    $form[] = "\t\t\t\t<label for='sender-ip'>Sender's IP Address:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<input type='textbox' name='sender-ip' id='sender-ip' maxlen='128' size='42' value='".(isset($_REQUEST['sender-ip'])?$_REQUEST['sender-ip']:whitelistGetIP(true))."'/>&nbsp;&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td style='width: 320px;'>";
			    $form[] = "\t\t\t\t<label for='recipient-username'>Recipient's Username:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<input type='textbox' name='usernames[recipient]' id='recipient-username' maxlen='64' size='29' value='".(isset($_REQUEST['usernames']['recipient'])?$_REQUEST['usernames']['recipient']:"")."'/>&nbsp;&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t</tr>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td style='width: 320px;'>";
			    $form[] = "\t\t\t\t<label for='recipient-email'>Recipient's Email:</label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<input type='textbox' name='emails[recipient]' id='recipient-email' maxlen='198' size='34' value='".(isset($_REQUEST['emails']['recipient'])?$_REQUEST['emails']['recipient']:"")."'/>&nbsp;&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t</tr>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td style='width: 320px;'>";
			    $form[] = "\t\t\t\t<label for='recipient-name'>Recipient's Name:</label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<input type='textbox' name='names[recipient]' id='recipient-name' maxlen='100' size='29' value='".(isset($_REQUEST['names']['recipient'])?$_REQUEST['names']['recipient']:"")."'/>&nbsp;&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t</tr>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td style='width: 320px;'>";
			    $form[] = "\t\t\t\t<label for='subject'>Tested Subject:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<input type='textbox' name='subject' id='subject' maxlen='500' size='51' value='".(isset($_REQUEST['subject'])?$_REQUEST['subject']:"")."'/>&nbsp;&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<label for='image'>Image File:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<file name='image' id='image' MAXFILESIZE='" . ( 1024 * 1024 * 1024 * 41.99 ) . ">&nbsp;&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t</tr>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td style='width: 320px;'>";
			    $form[] = "\t\t\t\t<label for='mode'>API Output Mode:&nbsp;</label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<select name='mode' id='mode'>";
			    $form[] = "\t\t\t\t\t<option value='return'".(isset($_REQUEST['mode'])&&$_REQUEST['mode']=='return'?' selected':'').">Send to Return URL (assume callback specified)</option>";
			    $form[] = "\t\t\t\t\t<option value='xml'".(isset($_REQUEST['mode'])&&$_REQUEST['mode']=='xml'?' selected':'').">XML Output Data</option>";
			    $form[] = "\t\t\t\t\t<option value='serial'".(isset($_REQUEST['mode'])&&$_REQUEST['mode']=='serial'?' selected':'').">PHP Serialisation</option>";
			    $form[] = "\t\t\t\t\t<option value='json'".(!isset($_REQUEST['mode'])||$_REQUEST['mode']=='json'?' selected':'').">JSON Data (default)</option>";
			    $form[] = "\t\t\t\t</select>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t</tr>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td colspan='3' style='padding-left:64px;'>";
			    $form[] = "\t\t\t\t<input type='hidden' name='mimetype' value='text/plain'>";
			    $form[] = "\t\t\t\t<input type='hidden' name='return' value='" . (isset($_REQUEST['return'])?$_REQUEST['return']:API_URL)."'>";
			    $form[] = "\t\t\t\t<input type='hidden' name='callback' value='" . (isset($_REQUEST['callback'])?$_REQUEST['callback']:"") ."'>";
			    $form[] = "\t\t\t\t<div style='clear: none; width: auto; margin-right: 59px; float: right;'><font style='color: rgb(250,0,0); font-size: 169%; font-weight: bold'>*</font>&nbsp;<font style='color: rgb(0,0,0); font-size: 152%; font-weight: bold'><em>Required/Essential Fields</em></font></div>";
			    $form[] = "\t\t\t\t<input type='submit' value='Run Spam Test' name='submit' style='padding:11px; font-size:122%;'>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t</tr>";
			    $form[] = "\t</table>";
			    $form[] = "</form>";
			    break;
			case "image-training":
			    $form[] = "<form name=\"" . $ua . "\" method=\"POST\" action=\"" . API_URL . '/v4/' .$ua . "/training/images.api\">";
			    $form[] = "\t<table class='spam-training' id='spam-training' style='vertical-align: top !important; min-width: 98%;'>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td style='width: 320px;'>";
			    $form[] = "\t\t\t\t<label for='sender-username'>Sender Username:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<input type='textbox' name='usernames[sender]' id='sender-username' maxlen='64' size='29' value='".(isset($_REQUEST['usernames']['sender'])?$_REQUEST['usernames']['sender']:"")."'/>&nbsp;&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t</tr>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td style='width: 320px;'>";
			    $form[] = "\t\t\t\t<label for='sender-email'>Sender's Email:</label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<input type='textbox' name='emails[sender]' id='sender-email' maxlen='198' size='34' value='".(isset($_REQUEST['emails']['sender'])?$_REQUEST['emails']['sender']:"")."'/>&nbsp;&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t</tr>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td style='width: 320px;'>";
			    $form[] = "\t\t\t\t<label for='sender-name'>Sender's Name:</label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<input type='textbox' name='names[sender]' id='sender-name' maxlen='100' size='29' value='".(isset($_REQUEST['names']['sender'])?$_REQUEST['names']['sender']:"")."'/>&nbsp;&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t</tr>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td style='width: 320px;'>";
			    $form[] = "\t\t\t\t<label for='sender-ip'>Sender's IP Address:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<input type='textbox' name='sender-ip' id='sender-ip' maxlen='128' size='42' value='".(isset($_REQUEST['sender-ip'])?$_REQUEST['sender-ip']:whitelistGetIP(true))."'/>&nbsp;&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td style='width: 320px;'>";
			    $form[] = "\t\t\t\t<label for='recipient-username'>Recipient's Username:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<input type='textbox' name='usernames[recipient]' id='recipient-username' maxlen='64' size='29' value='".(isset($_REQUEST['usernames']['sender'])?$_REQUEST['usernames']['sender']:"")."'/>&nbsp;&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t</tr>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td style='width: 320px;'>";
			    $form[] = "\t\t\t\t<label for='recipient-email'>Recipient's Email:</label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<input type='textbox' name='emails[recipient]' id='recipient-email' maxlen='198' size='34' value='".(isset($_REQUEST['emails']['recipient'])?$_REQUEST['emails']['recipient']:"")."'/>&nbsp;&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t</tr>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td style='width: 320px;'>";
			    $form[] = "\t\t\t\t<label for='recipient-name'>Recipient's Name:</label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<input type='textbox' name='names[recipient]' id='recipient-name' maxlen='100' size='29' value='".(isset($_REQUEST['names']['recipient'])?$_REQUEST['names']['recipient']:"")."'/>&nbsp;&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t</tr>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td style='width: 320px;'>";
			    $form[] = "\t\t\t\t<label for='subject'>Trained Subject:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<input type='textbox' name='subject' id='subject' maxlen='500' size='51' value='".(isset($_REQUEST['subject'])?$_REQUEST['subject']:"")."'/>&nbsp;&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<label for='image'>Image File:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<file name='image' id='image' MAXFILESIZE='" . ( 1024 * 1024 * 1024 * 41.99 ) . ">&nbsp;&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t</tr>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td style='width: 320px;'>";
			    $form[] = "\t\t\t\t<label for='mode'>Training Data Action:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t<select name='mode' id='mode'>";
			    $form[] = "\t\t\t\t\t<option value='ham'>None Spam HTML/Textual</option>";
			    $form[] = "\t\t\t\t\t<option value='spam'>Spam HTML/Textual</option>";
			    $form[] = "\t\t\t\t\t<option value='forget'>Forget Training with HTML/Textual</option>";
			    $form[] = "\t\t\t\t</select>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t\t<td>";
			    $form[] = "\t\t\t\t&nbsp;";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t</tr>";
			    $form[] = "\t\t<tr>";
			    $form[] = "\t\t\t<td colspan='3' style='padding-left:64px;'>";
			    $form[] = "\t\t\t\t<input type='hidden' name='mimetype' value='text/plain'>";
			    $form[] = "\t\t\t\t<input type='hidden' name='return' value='" . (isset($_REQUEST['return'])?$_REQUEST['return']:API_URL)."'>";
			    $form[] = "\t\t\t\t<input type='hidden' name='callback' value='" . (isset($_REQUEST['callback'])?$_REQUEST['callback']:"") ."'>";
			    $form[] = "\t\t\t\t<div style='clear: none; width: auto; margin-right: 59px; float: right;'><font style='color: rgb(250,0,0); font-size: 169%; font-weight: bold'>*</font>&nbsp;<font style='color: rgb(0,0,0); font-size: 152%; font-weight: bold'><em>Required/Essential Fields</em></font></div>";
			    $form[] = "\t\t\t\t<input type='submit' value='Spam/Ham/Forget Training' name='submit' style='padding:11px; font-size:122%;'>";
			    $form[] = "\t\t\t</td>";
			    $form[] = "\t\t</tr>";
			    $form[] = "\t</table>";
			    $form[] = "</form>";
			    break;
		}
		return implode("\n", $form);
	}
}

if (!function_exists("mkdirSecure")) {
	/**
	 *
	 * @param unknown_type $path
	 * @param unknown_type $perm
	 * @param unknown_type $secure
	 */
	function mkdirSecure($path = '', $perm = 0777, $secure = true)
	{
		if (!is_dir($path))
		{
			mkdir($path, $perm, true);
			if ($secure == true)
			{
				writeRawFile($path . DIRECTORY_SEPARATOR . '.htaccess', "<Files ~ \"^.*$\">\n\tdeny from all\n</Files>");
			}
			return true;
		}
		return false;
	}
}

if (!function_exists("whitelistGetIP")) {

	/* function whitelistGetIPAddy()
	 *
	* 	provides an associative array of whitelisted IP Addresses
	* @author 		Simon Roberts (Chronolabs) simon@labs.coop
	*
	* @return 		array
	*/
	function whitelistGetIPAddy() {
		return array_merge(whitelistGetNetBIOSIP(), file(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'whitelist.txt'));
	}
}

if (!function_exists("whitelistGetNetBIOSIP")) {

	/* function whitelistGetNetBIOSIP()
	 *
	* 	provides an associative array of whitelisted IP Addresses base on TLD and NetBIOS Addresses
	* @author 		Simon Roberts (Chronolabs) simon@labs.coop
	*
	* @return 		array
	*/
	function whitelistGetNetBIOSIP() {
		$ret = array();
		foreach(file(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'whitelist-domains.txt') as $domain) {
			$ip = gethostbyname($domain);
			$ret[$ip] = $ip;
		}
		return $ret;
	}
}

if (!function_exists("whitelistGetIP")) {

	/* function whitelistGetIP()
	 *
	* 	get the True IPv4/IPv6 address of the client using the API
	* @author 		Simon Roberts (Chronolabs) simon@labs.coop
	*
	* @param		$asString	boolean		Whether to return an address or network long integer
	*
	* @return 		mixed
	*/
	function whitelistGetIP($asString = true){
		// Gets the proxy ip sent by the user
		static $the_IPs = array();
		if (!isset($the_IPs[$_SERVER['REMOTE_ADDR']]) || empty($the_IPs[$_SERVER['REMOTE_ADDR']]))
		{
			$proxy_ip = '';
			if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else
				if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
					$proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
				} else
					if (! empty($_SERVER['HTTP_FORWARDED_FOR'])) {
						$proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
					} else
						if (!empty($_SERVER['HTTP_FORWARDED'])) {
							$proxy_ip = $_SERVER['HTTP_FORWARDED'];
						} else
							if (!empty($_SERVER['HTTP_VIA'])) {
								$proxy_ip = $_SERVER['HTTP_VIA'];
							} else
								if (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
									$proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
								} else
									if (!empty($_SERVER['HTTP_COMING_FROM'])) {
										$proxy_ip = $_SERVER['HTTP_COMING_FROM'];
									}
			if (!empty($proxy_ip) && $is_ip = preg_match('/^([0-9]{1,3}.){3,3}[0-9]{1,3}/', $proxy_ip, $regs) && count($regs) > 0)  {
				$the_IPs[$_SERVER['REMOTE_ADDR']] = $regs[0];
			} elseif(substr($_SERVER['REMOTE_ADDR'], 0, 3)=='::1'||substr($_SERVER['REMOTE_ADDR'], 0, 3)=='10.'||substr($_SERVER['REMOTE_ADDR'], 0, 4)=='192.'||substr($_SERVER['REMOTE_ADDR'], 0, 4)=='127.')
			{
				$externalContent = getURIData('http://checkip.dyndns.com/',25, 45);
				preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $externalContent, $m);
				$the_IPs[$_SERVER['REMOTE_ADDR']] = $m[1];
			} else {
				$the_IPs[$_SERVER['REMOTE_ADDR']] = $_SERVER['REMOTE_ADDR'];
			}
		}
		$the_IP = ($asString) ? $the_IPs[$_SERVER['REMOTE_ADDR']] : ip2long($the_IPs[$_SERVER['REMOTE_ADDR']]);
		return $the_IP;
	}
}

if (!function_exists("getIPIdentity")) {
	/**
	 *
	 * @param string $ip
	 * @return string
	 */
	function getIPIdentity($ip = '', $sarray = false)
	{
	    include_once API_ROOT_PATH . DS . 'class' . DS . 'whois.php';
		if (empty($ip))
			$ip = whitelistGetIP(true);
		if (strlen(session_id())==0)
			session_start();
		
		if (!isset($_SESSION['networking'][$ip]))
		{
			if (defined('API_NETWORK_LOGIC'))
			{
				$uris = cleanWhitespaces(file($file = __DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "lookups.diz"));
				shuffle($uris); shuffle($uris); shuffle($uris); shuffle($uris);
				if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE || FILTER_FLAG_NO_RES_RANGE) === false)
				{
					$data = array();
					foreach($uris as $uri)
					{
						if ($data['ip']==$ip || $data['country']['iso'] == "-" || empty($data))
							$data = json_decode(getURIData(sprintf($uri, 'myself', 'json'), 3, 6), true);
							if (count($data) > 0 &&  $data['country']['iso'] != "-")
								continue;
					}
				} else{
					foreach($uris as $uri)
					{
						if ($data['ip']!=$ip || $data['country']['iso'] == "-" || empty($data))
							$data = json_decode(getURIData(sprintf($uri, $ip, 'json'), 3, 6), true);
							if (count($data) > 0 &&  $data['country']['iso'] != "-")
								continue;
					}
				}
			}
	
			if (!isset($data['ip']) && empty($data['ip']))
				$data['ip'] = $ip;
				
			$_SESSION['networking'][$ip] = array();
			$_SESSION['networking'][$ip]['ipaddy'] = $data['ip'];
			if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false)
				$_SESSION['networking'][$ip]['type'] = 'ipv6';
			else
				$_SESSION['networking'][$ip]['type'] = 'ipv4';
			$_SESSION['networking'][$ip]['netbios'] = gethostbyaddr($_SESSION['networking'][$ip]['ipaddy']);
			$_SESSION['networking'][$ip]['data'] = array('ipstack' => gethostbynamel($_SESSION['networking'][$ip]['netbios']));
			$_SESSION['networking'][$ip]['domain'] = getBaseDomain("http://".$_SESSION['networking'][$ip]['netbios']);
			$_SESSION['networking'][$ip]['country'] = $data['country']['iso'];
			$_SESSION['networking'][$ip]['region'] = $data['location']['region'];
			$_SESSION['networking'][$ip]['city'] = $data['location']['city'];
			$_SESSION['networking'][$ip]['postcode'] = $data['location']['postcode'];
			$_SESSION['networking'][$ip]['timezone'] = "GMT " . $data['location']['gmt'];
			$_SESSION['networking'][$ip]['longitude'] = $data['location']['coordinates']['longitude'];
			$_SESSION['networking'][$ip]['latitude'] = $data['location']['coordinates']['latitude'];
			$_SESSION['networking'][$ip]['last'] = $_SESSION['networking'][$ip]['created'] = time();
			
			$whois = array();
			$whoisobj = new whois();
			$whois['domain'] = $whoisobj->query($_SESSION['networking'][$ip]['domain'], 'raw');
			$whoisobj = new whois();
			$whois['ip'] = $whoisobj->query($ip, 'raw');
			$wsid = md5(json_encode($whois));
		    $sql = "SELECT count(*) FROM `" . $GLOBALS['APIDB']->prefix('whois') . "` WHERE `id` = '".$wsid = md5(json_encode($whois))."'";
			list($countb) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
			if ($countb == 0)
			{
				$wsdata = array();
				$wsdata['id'] = $wsid;
				$wsdata['whois'] = mysqli_real_escape_string($GLOBALS['APIDB']->conn, json_encode($whois));
				$wsdata['created'] = time();
				$wsdata['last'] = time();
				$wsdata['instances'] = 1;
				if (!$GLOBALS['APIDB']->queryF($sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('whois') . "` (`" . implode('`, `', array_keys($wsdata)) . "`) VALUES ('" . implode("', '", $wsdata) . "')"))
					@$GLOBALS['APIDB']->queryF($sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('whois') . "` SET `instances` = `instances` + 1, `last` = unix_timestamp() WHERE `id` =  '$wsid'");
			}
			$_SESSION['networking'][$ip]['ipid'] = md5(json_encode($_SESSION['networking'][$ip]));
			$_SESSION['networking'][$ip]['whois'] = array($wsid=>$wsid);
			
			$data = array();
			foreach($_SESSION['networking'][$ip] as $key => $value)
				if (is_array($value))
				    $data[$key] = mysqli_real_escape_string($GLOBALS['APIDB']->conn, json_encode($value));
				else
				    $data[$key] = mysqli_real_escape_string($GLOBALS['APIDB']->conn, $value);

			$sql['selectb'] = "SELECT * from `" . $GLOBALS['APIDB']->prefix('networking') . "` WHERE `ipid` LIKE '" . $data['ipid'] . "'";
			if (!$GLOBALS['APIDB']->getRowsNum($GLOBALS['APIDB']->queryF($sql['selectb'])))
			{
				$sql['inserta'] = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('networking') . "` (`" . implode("`, `", array_keys($data)) . "`) VALUES ('" . implode("', '", $data) . "')";
				$GLOBALS['APIDB']->queryF($sql['inserta']);
			}
		}
		$sql['updatea'] = "UPDATE `" . $GLOBALS['APIDB']->prefix('networking') . "` SET `last` = '". time() . '\' WHERE `ipid` = "' . $_SESSION['networking'][$ip]['ipid'] .'"';
		$GLOBALS['APIDB']->queryF($sql['updatea']);
		if ($sarray == false)
			return $_SESSION['networking'][$ip]['ipid'];
		else
			return $_SESSION['networking'][$ip];
	}
}


if (!function_exists("getBaseDomain")) {
	/**
	 * getBaseDomain
	 *
	 * @param string $url
	 * @return string|unknown
	 */
	function getBaseDomain($url)
	{

		static $fallout, $stratauris, $classes;

		if (empty($classes))
		{
			if (empty($stratauris)) {
				$stratauris = cleanWhitespaces(file(__DIR__  . DIRECTORY_SEPARATOR .  "data" . DIRECTORY_SEPARATOR . "stratas.diz"));
				shuffle($stratauris); shuffle($stratauris); shuffle($stratauris); shuffle($stratauris);
			}
			shuffle($stratauris);
			$attempts = 0;
			while(empty($classes) || $attempts <= (count($stratauris) * 1.65))
			{
				$attempts++;
				$classes = array_keys(unserialize(getURIData($stratauris[mt_rand(0, count($stratauris)-1)] ."/v1/strata/serial.api", 3, 6)));
			}
		}
		if (empty($fallout))
		{
			if (empty($stratauris)) {
				$stratauris = cleanWhitespaces(file(__DIR__  . DIRECTORY_SEPARATOR .  "data" . DIRECTORY_SEPARATOR . "stratas.diz"));
				shuffle($stratauris); shuffle($stratauris); shuffle($stratauris); shuffle($stratauris);
			}
			shuffle($stratauris);
			$attempts = 0;
			while(empty($fallout) || $attempts <= (count($stratauris) * 1.65))
			{
				$attempts++;
				$fallout = array_keys(unserialize(getURIData($stratauris[mt_rand(0, count($stratauris)-1)] ."/v1/fallout/serial.api", 3, 6)));
			}
		}

		// Get Full Hostname
		$url = strtolower($url);
		$hostname = parse_url($url, PHP_URL_HOST);
		if (!filter_var($hostname, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 || FILTER_FLAG_IPV4) === false)
			return $hostname;

			// break up domain, reverse
			$elements = explode('.', $hostname);
			$elements = array_reverse($elements);

			// Returns Base Domain
			if (in_array($elements[0], $classes))
				return $elements[1] . '.' . $elements[0];
				elseif (in_array($elements[0], $fallout) && in_array($elements[1], $classes))
				return $elements[2] . '.' . $elements[1] . '.' . $elements[0];
				elseif (in_array($elements[0], $fallout))
				return  $elements[1] . '.' . $elements[0];
				else
					return  $elements[1] . '.' . $elements[0];
	}
}


if (!function_exists("getMimetype")) {
	/**
	 *
	 * @param unknown_type $path
	 * @param unknown_type $perm
	 * @param unknown_type $secure
	 */
	function getMimetype($extension = '-=-')
	{
		$mimetypes = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'mimetypes.diz'));
		foreach($mimetypes as $mimetype)
		{
			$parts = explode("||", $mimetype);
			if (strtolower($extension) == strtolower($parts[0]))
				return $parts[1];
				if (strtolower("-=-") == strtolower($parts[0]))
					$final = $parts[1];
		}
		return $final;
	}
}


if (!function_exists("cleanWhitespaces")) {
	/**
	 *
	 * @param array $array
	 */
	function cleanWhitespaces($array = array())
	{
		foreach($array as $key => $value)
		{
			if (is_array($value))
				$array[$key] = cleanWhitespaces($value);
				else {
					$array[$key] = trim(str_replace(array("\n", "\r", "\t"), "", $value));
				}
		}
		return $array;
	}
}


if (!function_exists("getURIData")) {

	/* function getURIData()
	 *
	 * 	cURL Routine
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 *
	 * @return 		float()
	 */
	function getURIData($uri = '', $timeout = 65, $connectout = 65, $post_data = array())
	{
		if (!function_exists("curl_init"))
		{
			return file_get_contents($uri);
		}
		if (!$btt = curl_init($uri)) {
			return false;
		}
		curl_setopt($btt, CURLOPT_HEADER, 0);
		curl_setopt($btt, CURLOPT_REQUEST, (count($posts)==0?false:true));
		if (count($posts)!=0)
			curl_setopt($btt, CURLOPT_POSTFIELDS, http_build_query($post_data));
			curl_setopt($btt, CURLOPT_CONNECTTIMEOUT, $connectout);
			curl_setopt($btt, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($btt, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($btt, CURLOPT_VERBOSE, false);
			curl_setopt($btt, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($btt, CURLOPT_SSL_VERIFYPEER, false);
			$data = curl_exec($btt);
			curl_close($btt);
			return $data;
	}
}


if (!function_exists('sef'))
{

	/**
	 * Safe encoded paths elements
	 *
	 * @param unknown $datab
	 * @param string $char
	 * @return string
	 */
	function sef($value = '', $stripe ='-')
	{
		$value = str_replace('&', 'and', $value);
		$value = str_replace(array("'", '"', "`"), 'tick', $value);
		$replacement_chars = array();
		$accepted = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","m","o","p","q",
				"r","s","t","u","v","w","x","y","z","0","9","8","7","6","5","4","3","2","1");
		for($i=0;$i<256;$i++){
			if (!in_array(strtolower(chr($i)),$accepted))
				$replacement_chars[] = chr($i);
		}
		$result = (str_replace($replacement_chars, $stripe, ($value)));
		while(substr($result, 0, strlen($stripe)) == $stripe)
			$result = substr($result, strlen($stripe), strlen($result) - strlen($stripe));
			while(substr($result, strlen($result) - strlen($stripe), strlen($stripe)) == $stripe)
				$result = substr($result, 0, strlen($result) - strlen($stripe));
				while(strpos($result, $stripe . $stripe))
					$result = str_replace($stripe . $stripe, $stripe, $result);
					return(strtolower($result));
	}
}


if (!class_exists("XmlDomConstruct")) {
	/**
	 * class XmlDomConstruct
	 *
	 * 	Extends the DOMDocument to implement personal (utility) methods.
	 *
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 */
	class XmlDomConstruct extends DOMDocument {

		/**
		 * Constructs elements and texts from an array or string.
		 * The array can contain an element's name in the index part
		 * and an element's text in the value part.
		 *
		 * It can also creates an xml with the same element tagName on the same
		 * level.
		 *
		 * ex:
		 * <nodes>
		 *   <node>text</node>
		 *   <node>
		 *     <field>hello</field>
		 *     <field>world</field>
		 *   </node>
		 * </nodes>
		 *
		 * Array should then look like:
		 *
		 * Array (
		 *   "nodes" => Array (
		 *     "node" => Array (
		 *       0 => "text"
		 *       1 => Array (
		 *         "field" => Array (
		 *           0 => "hello"
		 *           1 => "world"
		 *         )
		 *       )
		 *     )
		 *   )
		 * )
		 *
		 * @param mixed $mixed An array or string.
		 *
		 * @param DOMElement[optional] $domElement Then element
		 * from where the array will be construct to.
		 *
		 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
		 *
		 */
		public function fromMixed($mixed, DOMElement $domElement = null) {

			$domElement = is_null($domElement) ? $this : $domElement;

			if (is_array($mixed)) {
				foreach( $mixed as $index => $mixedElement ) {

					if ( is_int($index) ) {
						if ( $index == 0 ) {
							$node = $domElement;
						} else {
							$node = $this->createElement($domElement->tagName);
							$domElement->parentNode->appendChild($node);
						}
					}

					else {
						$node = $this->createElement($index);
						$domElement->appendChild($node);
					}

					$this->fromMixed($mixedElement, $node);

				}
			} else {
				$domElement->appendChild($this->createTextNode($mixed));
			}

		}
			
	}
}
?>
