<?php
/**
 * Chronolabs Fonting Repository Services REST API API
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
 * @since           2.1.9
 * @author          Simon Roberts <wishcraft@users.sourceforge.net>
 * @subpackage		api
 * @description		Fonting Repository Services REST API
 * @link			http://sourceforge.net/projects/chronolabsapis
 * @link			http://cipher.labs.coop
 */

	global $peerid, $source;
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'header.php';
	
	$data = $errors = array();
	$version = isset($_GET['version'])?(string)$_GET['version']:'v3';
	$mode = isset($_GET['mode'])?(string)$_GET['mode']:'json';
	$output = isset($_GET['output'])?(string)$_GET['output']:'test';
	switch($output)
	{
		case "test":
			
			if (!isset($_REQUEST['subject']) || empty($_REQUEST['subject']))
				$errors['missing_subject'] = "Field \$_POST['subject'] is an essential field for this function ~ you must specify it!";
			if (!isset($_REQUEST['message']) || empty($_REQUEST['message']))
				$errors['missing_message'] = "Field \$_POST['message'] is an essential field for this function ~ you must specify it!";
			if (!isset($_REQUEST['sender-ip']) || empty($_REQUEST['sender-ip']))
				$errors['missing_senderip'] = "Field \$_POST['sender-ip'] is an essential field for this function ~ you must specify it!";
			if (!isset($_REQUEST['usernames']['sender']) || empty($_REQUEST['usernames']['sender']))
				$errors['missing_username_sender'] = "Field \$_POST['usernames']['sender'] is an essential field for this function ~ you must specify it!";
			if (!isset($_REQUEST['usernames']['recipient']) || empty($_REQUEST['usernames']['recipient']))
				$errors['missing_username_recipient'] = "Field \$_POST['usernames']['recipient'] is an essential field for this function ~ you must specify it!";
			if (!isset($_REQUEST['mimetype']) || empty($_REQUEST['mimetype']))
				$errors['missing_mimetype'] = "Field \$_POST['mimetype'] is an essential field for this function ~ you must specify it!";
			if (!in_array($mode, array('return','xml','serial','json')))
				$errors['wrong_mode'] = "Field \$_POST['mode'] is an essential field and is enumerated to only one of the following: return, xml, serial, json!";
			if (!in_array($_REQUEST['mimetype'], array('text/plain','text/html')))
				$errors['wrong_mimetype'] = "Field \$_POST['mimetype'] is an essential field and is enumerated to only one of the following: text/plain, text/html!";
			if ($mode=='return' && !isset($_REQUEST['return']) &&  empty($_REQUEST['return']))		
				$errors['missing_return'] = "Field \$_POST['return'] is an essential field for this function for a URL to be specified to return the call to the API in the browser ~ you must specify it!";
			if ($mode=='return' && !isset($_REQUEST['callback']) &&  empty($_REQUEST['callback']))		
				$errors['missing_callback'] = "Field \$_POST['callback'] is an essential field for this function for a URL to be called by the callback cron and sent the testing data after returned to the source in the browser ~ you must specify it!";
			break;
	}

	
	if (count($errors)>0) {
		if (function_exists('http_response_code'))
			http_response_code(500);
		$data = array('state' => 'error occured', 'errors'=>$errors, 'peerid'=>$GLOBALS['peerid']);
		header('Content-type: application/json');
		die(json_encode($data));
	} else {
		mkdirSecure(DIR_SPAM_TESTING);
		$template = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'testing-template.diz');
		foreach(($resource = getTemplateArray($_REQUEST, $output)) as $key => $value)
			$template = str_replace("%key", $value, $template);
		$fname = DIR_SPAM_TESTING . DIRECTORY_SEPARATOR . $resource['testid'] . '@' . $resource['apidomain'] . '.msg';
		writeRawFile($fname, $template);
		
		$sout = array();
		$rvar = false;
		exec(sprintf(API_SPAMTESTER, $fname), $sout, $rvar);
		unlink($fname);
		$beta = $alpha = $nums = 0;
		foreach($sout as $key => $valstore)
		{
			if (strpos($valstore, "/")>0)
			{
				$parts = explode("/", $valstore);
				if (count($parts)==2&&$parts[0]!=0&&$parts[1]!=0)
				{
					$nums++;
					$alpha = $alpha + ((float)$parts[0] * 1000 + 1);
					$beta = $beta + ((float)$parts[1] * 1000 + 2);
				}
			}
		}
		if ($nums>0)
		{
			$alpha = $alpha / $nums;
			$beta = $beta / $nums;
		}
		
		$sql = "UPDATE `tests` SET `score-alpha` = '%s', `score-beta` = '%s' WHERE `testid` LIKE '%s'";
		$GLOBALS['WammyDB']->queryF($sql = sprintf($sql, $alpha, $beta, $resource['testid']));
		$sql = "UPDATE `users` SET `score-alpha` = `score-alpha` + '%s' / 2, `score-beta` = `score-beta` + '%s' / 2 WHERE `userid` LIKE '%s'";
		$GLOBALS['WammyDB']->queryF($sql = sprintf($sql, $alpha, $beta, $resource['sender-userid']));
		$sql = "UPDATE `users` SET `score-alpha` = `score-alpha` + '%s' / 2, `score-beta` = `score-beta` + '%s' / 2 WHERE `userid` LIKE '%s'";
		$GLOBALS['WammyDB']->queryF($sql = sprintf($sql, $alpha, $beta, $resource['recipient-userid']));
		$sql = "SELECT max(`score-alpha`) as `maximum-alpha`, avg(`score-alpha`) as `average-alpha`, stddev(`score-alpha`) as `stddev-alpha`, max(`score-beta`) as `maximum-beta`, avg(`score-beta`) as `average-beta`, stddev(`score-beta`) as `stddev-beta` FROM  `tests`";
		if (!$data = $GLOBALS['WammyDB']->fetchArray($GLOBALS['WammyDB']->queryF($sql)))
			die("SQL Failed: $sql;");
		if ($alpha==0 && $beta == 0) {
			$data['result'] = 'ham';
		} elseif ($data['maximum-alpha']>0 && $alpha < ($data['average-alpha'] - $data['stddev-alpha']) && $data['maximum-beta']>0 && $beta < ($data['average-beta'] - $data['stddev-beta']) || ($alpha / $beta / 1000 * 100) < 41) {
			$data['result'] = 'ham';
		} else {
			$data['result'] = 'spam';
		}
		
		$update = array();
		foreach($data as $key => $value)
			$update[] = "`$key` = \"" . mysql_real_escape_string($value) . "\"";
		$sql = "UPDATE `tests` SET " . implode(", ", $update) . " WHERE `testid` LIKE '%s'";
		$GLOBALS['WammyDB']->queryF($sql = sprintf($sql, $resource['testid']));
		
		$template = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'training-template.diz');
		foreach($resource as $key => $value)
			$template = str_replace("%key", $value, $template);
		$fname = constant("DIR_TRAINING_".strtoupper($data['result'])) . DIRECTORY_SEPARATOR . $resource['testid'] . '@' . $resource['apidomain'] . '.msg';
		mkdirSecure(constant("DIR_TRAINING_".strtoupper($data['result'])));
		writeRawFile($fname, $template);
		
		$data['score-alpha'] = $alpha;
		$data['score-beta'] = $beta;
		if ($beta != 0 && $alpha < $beta)
			$data['score-percentile'] = (float)$alpha / $beta * 100;
		elseif ($alpha != 0 && $alpha > $beta)
			$data['score-percentile'] = (float)$beta / $alpha * 100;
		else 
			$data['score-percentile'] = 100;
		
		if (isset($_REQUEST['callback']) && !empty($_REQUEST['callback']))
		{
			$fname = DIR_TRAINING_DATA . DIRECTORY_SEPARATOR . $resource['testid'] . '@' . $resource['apidomain'] . '.json';
			mkdirSecure(DIR_TRAINING_DATA);
			writeRawFile($fname, json_encode(array('request'=>$_REQUEST, 'data'=>$data, 'peerid'=>$GLOBALS['peerid'])));
		}
		
		$sql = "SELECT * FROM `peers` WHERE `peerid` NOT LIKE '%s' and `polinating` = 'Yes'";
		$results = $GLOBALS['WammyDB']->queryF(sprintf($sql, mysql_real_escape_string($GLOBALS['peerid'])));
		while($peer = $GLOBALS['WammyDB']->fetchArray($results))
		{
			if (!empty($peer['api-uri-'.$data['result']]))
				setCallBackURI($peer['api-uri-'.$data['result']], 290, 290, array_merge($_REQUEST, array('peerid'=>$GLOBALS['peerid'])));
		}
		
	}
	
	if (function_exists('http_response_code'))
		http_response_code(200);
	
	if (isset($_REQUEST['callback']) && !empty($_REQUEST['callback']))
		setCallBackURI($_REQUEST['callback'], 290, 290, array_merge($_REQUEST, $data, array('peerid'=>$GLOBALS['peerid']), $resource));
				
	switch ($mode) {
		case 'return':
			if (function_exists('http_response_code'))
				http_response_code(301);
			header('Location: ' . $_REQUEST['return']);
			exit(0);
			break;
		case 'json':
			header('Content-type: application/json');
			die(json_encode($data));
			break;
		case 'serial':
			header('Content-type: text/plain');
			die(serialize($data));
			break;
		case 'xml':
			header('Content-type: application/xml');
			$dom = new XmlDomConstruct('1.0', 'utf-8');
			$dom->fromMixed(array('root'=>$data));
 			die($dom->saveXML());
			break;
	}
	
?>		