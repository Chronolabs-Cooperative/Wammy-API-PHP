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
	
	
	
	/**
	 * URI Path Finding of API URL Source Locality
	 * @var unknown_type
	 */
	$odds = $inner = array();
	foreach($inner as $key => $values) {
	    if (!isset($inner[$key])) {
	        $inner[$key] = $values;
	    } elseif (!in_array(!is_array($values) ? $values : md5(json_encode($values, true)), array_keys($odds[$key]))) {
	        if (is_array($values)) {
	            $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
	        } else {
	            $odds[$key][$inner[$key] = $values] = "$values--$key";
	        }
	    }
	}
	
	foreach($_POST as $key => $values) {
	    if (!isset($inner[$key])) {
	        $inner[$key] = $values;
	    } elseif (!in_array(!is_array($values) ? $values : md5(json_encode($values, true)), array_keys($odds[$key]))) {
	        if (is_array($values)) {
	            $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
	        } else {
	            $odds[$key][$inner[$key] = $values] = "$values--$key";
	        }
	    }
	}
	
	foreach(parse_url('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'], '?')?'&':'?').$_SERVER['QUERY_STRING'], PHP_URL_QUERY) as $key => $values) {
	    if (!isset($inner[$key])) {
	        $inner[$key] = $values;
	    } elseif (!in_array(!is_array($values) ? $values : md5(json_encode($values, true)), array_keys($odds[$key]))) {
	        if (is_array($values)) {
	            $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
	        } else {
	            $odds[$key][$inner[$key] = $values] = "$values--$key";
	        }
	    }
	}
	
	$errors = array();
	$version = isset($inner['version'])?(string)$inner['version']:'v4';
	$state = isset($inner['state'])?(string)$inner['state']:'ham';
	$output = isset($inner['output'])?(string)$inner['output']:'test';
	switch($output)
	{
		case "test":
			
			if (!isset($inner['subject']) || empty($inner['subject']))
				$errors['missing_subject'] = "Field \$_POST['subject'] is an essential field for this function ~ you must specify it!";
			if (!isset($inner['message']) || empty($inner['message']))
				$errors['missing_message'] = "Field \$_POST['message'] is an essential field for this function ~ you must specify it!";
			if (!isset($inner['sender-ip']) || empty($inner['sender-ip']))
				$errors['missing_senderip'] = "Field \$_POST['sender-ip'] is an essential field for this function ~ you must specify it!";
			if (!isset($inner['usernames']['sender']) || empty($inner['usernames']['sender']))
				$errors['missing_username_sender'] = "Field \$_POST['usernames']['sender'] is an essential field for this function ~ you must specify it!";
			if (!isset($inner['usernames']['recipient']) || empty($inner['usernames']['recipient']))
				$errors['missing_username_recipient'] = "Field \$_POST['usernames']['recipient'] is an essential field for this function ~ you must specify it!";
			if (!isset($inner['mimetype']) || empty($inner['mimetype']))
				$errors['missing_mimetype'] = "Field \$_POST['mimetype'] is an essential field for this function ~ you must specify it!";
			if (!in_array($state, array('return','xml','serial','json')))
				$errors['wrong_state'] = "Field \$_POST['state'] is an essential field and is enumerated to only one of the following: return, xml, serial, json!";
			if (!in_array($inner['mimetype'], array('text/plain','text/html')))
				$errors['wrong_mimetype'] = "Field \$_POST['mimetype'] is an essential field and is enumerated to only one of the following: text/plain, text/html!";
			if ($state == 'return' && !isset($inner['return']) &&  empty($inner['return']))		
				$errors['missing_return'] = "Field \$_POST['return'] is an essential field for this function for a URL to be specified to return the call to the API in the browser ~ you must specify it!";
			if ($state == 'return' && !isset($inner['callback']) &&  empty($inner['callback']))		
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
		foreach(($resource = getTemplateArray($inner, $output)) as $key => $value)
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
				if (count($parts)==2 && $parts[0] != 0 && $parts[1] != 0)
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
		$fname = constant("DIR_TRAINING_".strtoupper($state)) . DIRECTORY_SEPARATOR . $resource['testid'] . '@' . $resource['apidomain'] . '.msg';
		mkdirSecure(constant("DIR_TRAINING_".strtoupper($state)));
		writeRawFile($fname, $template);
		
		$data['score-alpha'] = $alpha;
		$data['score-beta'] = $beta;
		if ($beta!=0 && $alpha < $beta)
			$data['score-percentile'] = (float)$alpha / $beta * 100;
		elseif ($alpha!=0 && $alpha > $beta)
			$data['score-percentile'] = (float)$beta / $alpha * 100;
		else 
			$data['score-percentile'] = 100;
		
		if (isset($inner['callback']) && !empty($inner['callback']))
		{
			$fname = DIR_TRAINING_DATA . DIRECTORY_SEPARATOR . $resource['testid'] . '@' . $resource['apidomain'] . '.json';
			mkdirSecure(DIR_TRAINING_DATA);
			writeRawFile($fname, json_encode(array('request'=>$inner, 'data'=>$data, 'resource' => $resource, 'peerid'=>$GLOBALS['peerid'])));
		}
		
		$sql = "SELECT * FROM `peers` WHERE `peerid` NOT LIKE '%s' and `polinating` = 'Yes'";
		$results = $GLOBALS['WammyDB']->queryF(sprintf($sql, mysql_real_escape_string($GLOBALS['peerid'])));
		while($peer = $GLOBALS['WammyDB']->fetchArray($results))
		{
			if (!empty($peer['api-uri-'.$state]))
				setCallBackURI($peer['api-uri-'.$state], 290, 290, array_merge($inner, array('testid'=>$resource['testid'], 'peerid'=>$GLOBALS['peerid'])));
		}

		if (isset($inner['callback']) && !empty($inner['callback']))
			setCallBackURI($inner['callback'], 290, 290, array_merge($inner, $data, array('peerid'=>$GLOBALS['peerid']), $resource));	
	}

	if (function_exists('http_response_code'))
		http_response_code(200);
	
?>		