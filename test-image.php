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

	global $sessionid, $source;
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'header.php';
	
	$GLOBALS['APIDB']->queryF("START TRANSACTION");
	
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
	
	
	$data = $errors = array();
	$version = isset($inner['version'])?(string)$inner['version']:'v4';
	$state = isset($inner['state'])?(string)$inner['state']:'json';
	$mode = isset($inner['mode'])?(string)$inner['mode']:'return';
	$output = isset($inner['output'])?(string)$inner['output']:'test';
	switch($output)
	{
		case "test":
			
			if (!isset($inner['subject']) || empty($inner['subject']))
				$errors['missing_subject'] = "Field \$_POST['subject'] is an essential field for this function ~ you must specify it!";
			if (!isset($_FILES['image']) || empty($_FILES['image']))
				$errors['missing_image'] = "Field \$_FILE['image'] is an essential field for this function ~ you must specify it!";
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
			if ($state=='return' && !isset($inner['return']) &&  empty($inner['return']))		
				$errors['missing_return'] = "Field \$_POST['return'] is an essential field for this function for a URL to be specified to return the call to the API in the browser ~ you must specify it!";
			if ($state=='return' && !isset($inner['callback']) &&  empty($inner['callback']))		
				$errors['missing_callback'] = "Field \$_POST['callback'] is an essential field for this function for a URL to be called by the callback cron and sent the testing data after returned to the source in the browser ~ you must specify it!";
			break;
	}

	if ($country == 'random' || $place == 'random')
	    $keyname = md5(whitelistGetIP(true) . '___' . $_SERVER['HTTP_HOST'] . '___' . $_SERVER['REQUEST_URI'] . '_____' . json_encode($inner));
    else
        $keyname = md5($_SERVER['REQUEST_URI'] . json_encode($inner));
    if (!$data = APICache::read($keyname))
    {
    	if (count($errors)>0) {
    		if (function_exists('http_response_code'))
    			http_response_code(500);
    		$data = array('state' => 'error occured', 'errors'=>$errors, 'sessionid'=>$GLOBALS['sessionid']);
    		header('Content-type: application/json');
    		die(json_encode($data));
    	} else {
    	    
    	    require_once API_ROOT_PATH . DS . 'class' . DS . 'uploader.php';
    	    xoops_load('APILists');
    	    switch ($mode)
    	    {
    	        case "return":
    	            if (!is_dir($uploaddir = API_VAR_PATH . DS . session_id() . DS . md5(microtime(true))))
    	               mkdir($uploaddir, 0777, true);
    	            $upload = new APIMediaUploader($uploaddir, array(), 1024 * 1024 * 1024 * 41.99);
    	            $upload->allowUnknownTypes = true;
    	            $upload->setTargetFileName($file = md5(microtime(true)) . "." . substr($_FILES['image']['name'], strlen($_FILES['image']['name'])-3, 3));
    	            if ($upload->fetchMedia('image'))
    	            {
    	                if (copy($uploaddir . DS . $file, API_VAR_PATH . DS . 'queuing' . DS . 'testing' . DS . 'images' . DS . $file))
    	                    unlink($uploaddir . DS . $file);
    	                if (is_file($imgfile = API_VAR_PATH . DS . 'queuing' . DS . 'testing' . DS . 'images' . DS . $file))
    	                {
    	                    $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('jobs') . "` (`state`, `mode`, `typal`, `balance`, `hashing`, `created`, `actionable`) VALUES('queued', 'image', 'testing', 'none', '".md5_file($imgfile) . "', UNIX_TIMESTAMP(), UNIX_TIMESTAMP() + " . API_DELAY_SECONDS . ")";
    	                    if ($GLOBALS['APIDB']->queryF($sql))
    	                    {
    	                        $jid = $GLOBALS['APIDB']->getInsertId();
    	                        $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('routes') . "` (`jid`, `mode`, `medium`, `mimetype`, `training`, `api_url`, `subject`, `recipient_username`, `sender_username`, `recipient_name`, `sender_name`, `recipient_email`, `sender_email`, `recipient_ip`, `sender_ip`, `path`, `file`, `created`, `actionable`) VALUES('$jid', 'testing', 'image', '" . ( !isset($inner['mimetype']) ? 'text/plain' : $inner['mimetype'] ) . "', 'none', '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, $inner['callback']) . "', '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, $inner['subject']) . "', , '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, $inner['usernames']['recipient']) . "', '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, $inner['usernames']['sender']) . "',, '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, $inner['names']['recipient']) . "', '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, $inner['names']['sender']) . "', , '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, $inner['emails']['recipient']) . "', '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, $inner['emails']['sender']) . "', , '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, whitelistGetIP(true)) . "', '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, $inner['sender-ip']) . "', '" . str_replace(API_VAR_PATH, '', dirname($imgfile)) . "', '" . basename($imgfile) . "', UNIX_TIMESTAMP(), UNIX_TIMESTAMP() + " . API_DELAY_SECONDS . ")";
    	                        if ($GLOBALS['APIDB']->queryF($sql))
    	                        {
    	                            $rid = $GLOBALS['APIDB']->getInsertId();
    	                            redirect_header($inner['return'], 0, 'Callback API being delegated!');
    	                        }
    	                    }
    	                }
    	            }
    	            redirect_header($inner['return'], 0, '');
    	            break;
    	        default:
    	         
    	            $utf = array();
    	         
    	            if (!is_dir($uploaddir = API_VAR_PATH . DS . session_id() . DS . md5(microtime(true))))
    	                mkdir($uploaddir, 0777, true);
    	            
	                $upload = new APIMediaUploader($uploaddir, array(), 1024 * 1024 * 1024 * 41.99);
	                $upload->allowUnknownTypes = true;
	                $upload->setTargetFileName($file = md5(microtime(true)) . "." . substr($_FILES['image']['name'], strlen($_FILES['image']['name'])-3, 3));
	                if ($upload->fetchMedia('image'))
	                {
	                    
	                    $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('jobs') . "` (`state`, `mode`, `typal`, `balance`, `hashing`, `created`, `actionable`) VALUES('queued', 'image', 'testing', 'none', '" . ($hashing = md5_file($uploaddir . DS . $file)) . "', UNIX_TIMESTAMP(), UNIX_TIMESTAMP() + " . API_DELAY_SECONDS . ")";
                        if ($GLOBALS['APIDB']->queryF($sql))
                        {
                            $jid = $GLOBALS['APIDB']->getInsertId();
                            $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('routes') . "` (`jid`, `mode`, `medium`, `mimetype`, `training`, `api_url`, `subject`, `recipient_username`, `sender_username`, `recipient_name`, `sender_name`, `recipient_email`, `sender_email`, `recipient_ip`, `sender_ip`, `path`, `file`, `created`, `actionable`) VALUES('$jid', 'testing', 'image', '" . ( !isset($inner['mimetype']) ? 'text/plain' : $inner['mimetype'] ) . "', 'none', '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, $inner['callback']) . "', '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, $inner['subject']) . "', , '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, $inner['usernames']['recipient']) . "', '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, $inner['usernames']['sender']) . "',, '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, $inner['names']['recipient']) . "', '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, $inner['names']['sender']) . "', , '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, $inner['emails']['recipient']) . "', '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, $inner['emails']['sender']) . "', , '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, whitelistGetIP(true)) . "', '" . mysqli_real_escape_string($GLOBALS['APIDB']->conn, $inner['sender-ip']) . "', '" . str_replace(API_VAR_PATH, '', dirname($imgfile)) . "', '" . basename($imgfile) . "', UNIX_TIMESTAMP(), UNIX_TIMESTAMP() + " . API_DELAY_SECONDS . ")";
                            if ($GLOBALS['APIDB']->queryF($sql))
                            {
                                $rid = $GLOBALS['APIDB']->getInsertId();
                                shell_exec(sprintf(API_IMAGE_CONVERT_JPG, $uploaddir . DS . $file, $uploaddir . DS . $file));
                                shell_exec(sprintf(API_IMAGE_CONTRAST_JPG, $uploaddir . DS . $file, $uploaddir . DS . $file));
                                shell_exec(sprintf(API_IMAGE_TINTRED_JPG, $uploaddir . DS . $file, $uploaddir . DS . $file));
                                shell_exec(sprintf(API_IMAGE_TINTBLUE_JPG, $uploaddir . DS . $file, $uploaddir . DS . $file));
                                shell_exec(sprintf(API_IMAGE_TINTGREEN_JPG, $uploaddir . DS . $file, $uploaddir . DS . $file));
                                shell_exec(sprintf(API_IMAGE_TINTGREY_JPG, $uploaddir . DS . $file, $uploaddir . DS . $file));
                                shell_exec(sprintf(API_IMAGE_TINTPURPLE_JPG, $uploaddir . DS . $file, $uploaddir . DS . $file));
                                shell_exec(sprintf(API_IMAGE_TINTORANGE_JPG, $uploaddir . DS . $file, $uploaddir . DS . $file));
                                shell_exec(sprintf(API_IMAGE_TINTYELLOW_JPG, $uploaddir . DS . $file, $uploaddir . DS . $file));
                                shell_exec(sprintf(API_IMAGE_TINTPINK_JPG, $uploaddir . DS . $file, $uploaddir . DS . $file));
                                shell_exec(sprintf(API_IMAGE_TINTBROWN_JPG, $uploaddir . DS . $file, $uploaddir . DS . $file));
                                shell_exec(sprintf(API_IMAGE_TINTCYAN_JPG, $uploaddir . DS . $file, $uploaddir . DS . $file));
                                shell_exec(sprintf(API_IMAGE_TINTWHITE_JPG, $uploaddir . DS . $file, $uploaddir . DS . $file));
                                shell_exec(sprintf(API_IMAGE_TINTBLACK_JPG, $uploaddir . DS . $file, $uploaddir . DS . $file));
                                if (count(APILists::getImgListAsArray($uploaddir)) >= 7)
                                {
                                    $utf = array();
                                    foreach(APILists::getImgListAsArray($uploaddir) as $jpgfile)
                                    {
                                        if (substr($jpgfile, strlen($jpgfile) - 3, 3) == 'jpg')
                                        {
                                            $parts = array_reverse(explode('.', $jpgfile));
                                            if (!empty($parts[1]))
                                            {
                                                shell_exec(sprintf(API_IMAGE_JPGTOPNM, $uploaddir . DS . $jpgfile, $uploaddir . DS . $jpgfile));
                                                $utf[$parts[1]] = trim(shell_exec(sprintf(API_IMAGE_OCR, $uploaddir . DS . $jpgfile)));
                                                if (isset($utf[$parts[1]]) && empty($utf[$parts[1]]))
                                                    unset($utf[$parts[1]]);
                                            }
                                        }
                                    }
                                }
                                shell_exec("rm -Rf \"".API_VAR_PATH . DS . session_id()."\"");
                            }
                        }
	                }
	                
	                $totals = $status = array();
	                $ttlgamma = $ttlalpha = $ttlnums = 0;
	                if (count($utf) >0)
	                {
	                    $ttl = count($utf);
	                    $avg = 0;
	                    foreach($utf as $key => $text)
	                        $avg = $avg + strlen($text);
                        foreach($utf as $key => $text)
                            if (strlen($text) < ($avg/$ttl))
                                unset($utf[$key]);
                        foreach($utf as $optionality => $message)
                        {
                            $template = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'testing-template.diz');
                            foreach(($resource = getTemplateArray($inner, $message)) as $key => $value)
                                $template = str_replace("%key", $value, $template);
                            $fname = API_VAR_PATH . DS . $hashing . '.' . $optionality . '.msg';
                            writeRawFile($fname, $template);
                            $sout = array();
                            $rvar = false;
                            exec(sprintf(API_SPAMTESTER, $fname), $sout, $rvar);
                            unlink($fname);
                            $gamma = $alpha = $nums = 0;
                            foreach($sout as $key => $valstore)
                            {
                                if (strpos($valstore, "/")>0)
                                {
                                    $parts = explode("/", $valstore);
                                    if (count($parts)==2&&$parts[0]!=0&&$parts[1]!=0)
                                    {
                                        $nums++;
                                        $alpha = $alpha + ((float)$parts[0] * 1000 + 1);
                                        $gamma = $gamma + ((float)$parts[1] * 1000 + 2);
                                    }
                                }
                            }
                            if ($nums>0)
                            {
                                $alpha = $alpha / $nums;
                                $gamma = $gamma / $nums;
                            }

                            $ttlnums = $ttlnums + $nums;
                            $ttlalpha = $ttlalpha + $alpha;
                            $ttlgamma = $ttlgamma + $gamma;
                            
                            
                            $sql = "SELECT max(`alpha`) as `maximum-alpha`, avg(`alpha`) as `average-alpha`, stddev(`alpha`) as `stddev-alpha`, max(`gamma`) as `maximum-gamma`, avg(`gamma`) as `average-gamma`, stddev(`gamma`) as `stddev-gamma` FROM  `" . $GLOBALS["APIDB"]->prefix("jobs") . "`";
                            list($maxalpha, $avgalpha, $stdevalpha, $maxgamma, $avggamma, $stdevgamma) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
                            
                            if ($alpha==0 && $gamma == 0) {
                                $elite = 'ham';
                            } elseif ($maxalpha>0 && $alpha < ($avgalpha - $stdevalpha) && $maxgamma>0 && $gamma < ($avggamma - $stddevgamma) || ($alpha / $gamma / 1000 * 100) < 41) {
                                $elite = 'ham';
                            } elseif ($maxalpha>0 && ($ttlalpha / $ttlnums) < ($avgalpha - $stdevalpha) && $maxgamma>0 && ($ttlgamma / $ttlnums) < ($avggamma - $stddevgamma) || ($ttlalpha / $ttlgamma / 1000 * 100) < 51) {
                                $elite = 'ham';
                            } else {
                                $elite = 'spam';
                            }
                            
                            $status[$elite]++;
                            
                            $fname = API_PATH . 'scoring' . DS . 'testing' . DS . $elite . DS . 'images' . DS  . $hashing . '.' . $optionality . '.msg';
                            writeRawFile($fname, $template);
                                                        
                            $fname = API_PATH . 'training' . DS . $elite . DS . 'images' . DS  . $hashing  . '.' . $optionality . '.msg';
                            writeRawFile($fname, $template);
                            
                            require_once API_ROOT_PATH . DS . 'class' . DS . 'sentences.php';
                            $sentences = new APISentences($message);
                            
                            $fname = API_PATH . 'scoring' . DS . 'testing' . DS . $elite . DS . 'images' . DS  . $hashing . '.' . $optionality . '.json';
                            writeRawFile($fname, json_encode($sentences->getDumpArray()));
                            
                            foreach($sentences->getCountArray() as $key => $value)
                                $total[$key] = $total[$key] + $value;
                        }
	                }
	                
	                if ($status['spam'] > $status['ham'])
	                    $elite = 'spam';
                    elseif ($status['spam'] <= $status['ham'])
                        $elite = 'ham';
                    
	                foreach($totals as $ikey => $value)
	                    if (!$GLOBALS['APIDB']->queryF($sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('jobs') . "` SET `$ikey` = '" . (is_numeric($value) ? floor( $value / ( count($utf) ) ) : mysqli_real_escape_string($GLOBALS['APIDB']->conn, $value) ) . " WHERE `jid` = '$jid'" ))
	                        die("SQL Failed: $sql;");
	       
	                if (!$GLOBALS['APIDB']->queryF($sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('jobs') . "` SET `updated` = UNIX_TIMESTAMP() WHERE `jid` = '$jid'"))
	                    die("SQL Failed: $sql;");
	                
	                foreach($totals as $ikey => $value)
	                    $totals[$ikey] = (is_numeric($value) ? floor( $value / ( count($utf) ) ) : $value );
                    
	                $sql = "SELECT max(`alpha`) as `maximum-alpha`, avg(`alpha`) as `average-alpha`, stddev(`alpha`) as `stddev-alpha`, max(`gamma`) as `maximum-gamma`, avg(`gamma`) as `average-gamma`, stddev(`gamma`) as `stddev-gamma` FROM  `" . $GLOBALS["APIDB"]->prefix("jobs") . "`";
                    $lestats = $GLOBALS['APIDB']->fetchArray($GLOBALS['APIDB']->queryF($sql));
                    
                    $data = array_merge($inner, $totals, $lestats, array('hashing' => $hashing, 'modal' => 'testing', 'typal' => 'image', 'sessionid'=>$GLOBALS['sessionid']), array('alpha' => $ttlalpha / $ttlnums, 'gamma' => $ttlalpha / $ttlnums, 'segments' => $ttlnums));
                    $data = array($elite => $data);
                    
	                if (isset($inner['callback']) && !empty($inner['callback']))
	                {
	                    setCallBackURI($inner['callback'], 290, 290, $data);
	                };
	                if (!empty($data))
	                {
	                    @APICache::write($keyname, $data, API_CACHE_SECONDS);
	                    if (!$sessions = APICache::read('sessions-'.md5($_SERVER['HTTP_HOST'])))
	                        $sessions = array();
                        $sessions[$keyname] = time() + API_CACHE_SECONDS;
                        @APICache::write('sessions-'.md5($_SERVER['HTTP_HOST']), $sessions, API_CACHE_SECONDS * API_CACHE_SECONDS * API_CACHE_SECONDS);
	                }
    	    }
    		
    	}
    }
    
    
    if (function_exists('http_response_code'))
        http_response_code(200);
        
    error_reporting(0);
    if (!empty($data))
    {
        @APICache::write($keyname, $data, API_CACHE_SECONDS);
        if (!$sessions = APICache::read('sessions-'.md5($_SERVER['HTTP_HOST'])))
            $sessions = array();
        $sessions[$keyname] = time() + API_CACHE_SECONDS;
        @APICache::write('sessions-'.md5($_SERVER['HTTP_HOST']), $sessions, API_CACHE_SECONDS * API_CACHE_SECONDS * API_CACHE_SECONDS);
    }
    
    if (function_exists('mb_http_output')) {
        mb_http_output('pass');
    }
    
    $GLOBALS['APIDB']->queryF("COMMIT");
	
	switch ($state) {
		case 'return':
			if (function_exists('http_response_code'))
				http_response_code(301);
			header('Location: ' . $inner['return']);
			exit(0);
			break;
		case 'raw':
		    header('Content-type: application/x-httpd-php');
		    echo ('<?php'."\n\n".'return ' . var_export($data, true) . ";\n\n?>");
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
	