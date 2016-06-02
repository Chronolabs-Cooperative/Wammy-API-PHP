<?php
/**
 * Chronolabs Entitiesing Repository Services REST API API
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
 * @package         entities
 * @since           2.1.9
 * @author          Simon Roberts <wishcraft@users.sourceforge.net>
 * @subpackage		api
 * @description		Entitiesing Repository Services REST API
 * @link			http://sourceforge.net/projects/chronolabsapis
 * @link			http://cipher.labs.coop
 */


	require_once  __DIR__ . DIRECTORY_SEPARATOR . "header.php";

	$mode = !isset($_REQUEST['mode'])?md5(NULL):$_REQUEST['mode'];
	
	switch ($mode)
	{
		case "register":
			$required = array('peerid', 'api-uri', 'api-uri-callback', 'api-uri-spam', 'api-uri-ham', 'api-uri-forget', 'version', 'polinating');
			foreach($required as $field)
				if (!in_array($field, array_keys($_POST)))
					die("Field \$_POST[$field] is required to operate this function!");
			
			$sql = "INSERT INTO `peers` (`peerid`, 'api-uri', 'api-uri-callback', 'api-uri-spam', 'api-uri-ham', 'api-uri-forget', `version`, `polinating`, `created`, `heard`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', unix_timestamp())";
			if ($GLOBALS['FontsDB']->queryF(sprintf($sql, mysql_real_escape_string($_POST['peerid']), mysql_real_escape_string($_POST['api-uri']), mysql_real_escape_string($_POST['api-uri-callback']), mysql_real_escape_string($_POST['api-uri-spam']), mysql_real_escape_string($_POST['api-uri-ham']), mysql_real_escape_string($_POST['api-uri-forgot']), mysql_real_escape_string($_POST['version']), ($_POST['polinating']==true?'Yes':'No'), time())))
			{
				if ($_POST['polinating']==true)
				{
					@setCallBackURI(sprintf($_POST['api-uri'].$_POST['api-uri-spam'], $mode), 245, 245, array('peerid'=>$GLOBALS['peer-id'], 'api-uri'=>API_URL, 'api-uri-callback'=>API_URL_CALLBACK, 'api-uri-spam'=>API_URL_SPAM, 'api-uri-ham'=>API_URL_HAM, 'api-uri-forget'=>API_URL_FORGOT, 'version'=>API_VERSION, 'polinating'=>API_POLINATING), array());
					if (API_URL === API_ROOT_NODE)
					{
						$sql = "SELECT * FROM `peers` WHERE `peerid` NOT LIKE '%s' AND  `peerid` NOT LIKE '%s' AND `polinating` = 'Yes'";
						if ($GLOBALS['FontsDB']->getRowsNum($results = $GLOBALS['FontsDB']->queryF(sprintf($sql, mysql_real_escape_string($GLOBALS['peer-id']), mysql_real_escape_string($_POST['peer-id']))))>=1)
						{
							while($other = $GLOBALS['FontsDB']->fetchArray($results))
							{
								@setCallBackURI(sprintf($other['api-uri-callback'],$mode), 245, 245, $_POST, array('success'=>"UPDATE `peers` SET `called` = UNIX_TIMESTAMP() WHERE `peer-id` = '" . $other['peer-id'] . "'"));
								@setCallBackURI(sprintf($_POST['api-uri-callback'], $mode), 245, 245, array('peerid'=>$other['peerid'], 'api-uri'=>$other['api-uri'], 'api-uri-callback'=>$other['api-uri-callback'], 'api-uri-spam'=>$other['api-uri-spam'], 'api-uri-ham'=>$other['api-uri-ham'], 'api-uri-forget'=>$other['api-uri-forget'], 'version'=>$other['version'], 'polinating'=>$other['polinating']), array());
							}
						}
					}
				}
				
			}
			break;
		case "fingering":
			$required = array('fingerprint', 'peer-id', 'ip');
			foreach($required as $field)
				if (!in_array($field, array_keys($_POST)))
					die("Field \$_POST[$field] is required to operate this function!");
			$sql = "UPDATE `peers` SET `heard` = unix_timestamp() where `peer-id` LIKE '%s'";
			$GLOBALS['FontsDB']->queryF(sprintf($sql, $_POST['peer-id']));
			$sql = "SELECT COUNT(*) as RC from `fonts_fingering` where `fingerprint` LIKE '%s'";
			list($count) = $GLOBALS['FontsDB']->fetchRow($GLOBALS['FontsDB']->queryF(sprintf($sql, $_POST['fingerprint'])));
			die(json_encode(array('count'=>$count)));
			break;
			
		case "download":
			$required = array('font-id', 'peer-id', 'ip');
			foreach($required as $field)
				if (!in_array($field, array_keys($_POST)))
					die("Field \$_POST[$field] is required to operate this function!");
			$sql = "UPDATE `peers` SET `heard` = unix_timestamp() where `peer-id` LIKE '%s'";
			$GLOBALS['FontsDB']->queryF(sprintf($sql, $_POST['peer-id']));
			$sql = "SELECT * from `fonts_archiving` WHERE (`font_id` = '".$_POST['font-id'].")";
			$result = $GLOBALS['FontsDB']->queryF($sql);
			while($row = $GLOBALS['FontsDB']->fetchArray($result))
			{
				$sql = "SELECT * from `fonts` WHERE `id` = '" . $row['font_id'] . "'";
				$font = $GLOBALS['FontsDB']->fetchArray($GLOBALS['FontsDB']->queryF($sql));
				switch($font['medium'])
				{
					case 'FONT_RESOURCES_CACHE':
					case 'FONT_RESOURCES_RESOURCE':
						if ($font['medium'] == FONT_RESOURCES_CACHE)
						{
							$sessions = unserialize(file_get_contents(FONT_RESOURCES_CACHE . DIRECTORY_SEPARATOR . "file-store-sessions.serial"));
							if (!file_exists(constant($font['medium']) . $row['path'] . DIRECTORY_SEPARATOR . $row['filename']) && !isset($sessions[md5($font['path'] . DIRECTORY_SEPARATOR . $font['filename'])]))
							{
								mkdir(constant("FONT_RESOURCES_CACHE") . $row['path'], 0777, true);
								writeRawFile(constant("FONT_RESOURCES_CACHE") . $row['path'] . DIRECTORY_SEPARATOR . $row['filename'], getURIData(sprint(FONT_RESOURCES_STORE, $row['path'] . DIRECTORY_SEPARATOR . $row['filename'])));
								$sessions[md5($row['path'] . DIRECTORY_SEPARATOR . $row['filename'])] = array("opened" => microtime(true), "dropped" => microtime(true) + mt_rand(3600 * 0.785, 3600 * 1.896), "resource" => $font['path'] . DIRECTORY_SEPARATOR . $font['filename']);
							} else {
								if ($sessions[md5($row['path'] . DIRECTORY_SEPARATOR . $row['filename'])]['dropped'] < microtime(true) + ($next = mt_rand(1800*.3236, 2560*.5436)))
									$sessions[md5($row['path'] . DIRECTORY_SEPARATOR . $row['filename'])]['dropped'] = $sessions[md5($row['path'] . DIRECTORY_SEPARATOR . $row['filename'])]['dropped'] + $next;
							}
							writeRawFile(FONT_RESOURCES_CACHE . DIRECTORY_SEPARATOR . "file-store-sessions.serial", serialize($sessions));
						}
						$resource = json_decode(getArchivedZIPFile($zip = constant($font['medium']) . $row['path'] . DIRECTORY_SEPARATOR . $row['filename'], 'font-resource.json'), true);
						break;
					case 'FONT_RESOURCES_PEER':
						$sessions = unserialize(file_get_contents(FONT_RESOURCES_CACHE . DIRECTORY_SEPARATOR . "file-store-sessions.serial"));
						if (!file_exists(constant(FONT_RESOURCES_CACHE) . $row['path'] . DIRECTORY_SEPARATOR . $row['filename']) && !isset($sessions[md5($font['path'] . DIRECTORY_SEPARATOR . $font['filename'])]))
						{
							$sql = "SELECT * FROM `peers` WHERE `peer-id` LIKE '%s'";
							if ($GLOBALS['FontsDB']->getRowsNum($results = $GLOBALS['FontsDB']->queryF(sprintf($sql, mysql_real_escape_string($font['peer_id']))))==1)
							{
								$peer = $GLOBALS['FontsDB']->fetchArray($results);
								mkdir(constant("FONT_RESOURCES_CACHE") . $row['path'], 0777, true);
								writeRawFile(constant("FONT_RESOURCES_CACHE") . $row['path'] . DIRECTORY_SEPARATOR . $row['filename'], getURIData(sprint($peer['api-uri'].$peer['api-uri-zip'], $row['font_id'])));
								$sessions[md5($row['path'] . DIRECTORY_SEPARATOR . $row['filename'])] = array("opened" => microtime(true), "dropped" => microtime(true) + mt_rand(3600 * 0.785, 3600 * 1.896), "resource" => $font['path'] . DIRECTORY_SEPARATOR . $font['filename']);
							}
						} else {
							if ($sessions[md5($row['path'] . DIRECTORY_SEPARATOR . $row['filename'])]['dropped'] < microtime(true) + ($next = mt_rand(1800*.3236, 2560*.5436)))
								$sessions[md5($row['path'] . DIRECTORY_SEPARATOR . $row['filename'])]['dropped'] = $sessions[md5($row['path'] . DIRECTORY_SEPARATOR . $row['filename'])]['dropped'] + $next;
						}
						writeRawFile(FONT_RESOURCES_CACHE . DIRECTORY_SEPARATOR . "file-store-sessions.serial", serialize($sessions));
						$resource = json_decode(getArchivedZIPFile($zip = FONT_RESOURCES_CACHE . $row['path'] . DIRECTORY_SEPARATOR . $row['filename'], 'font-resource.json'), true);
						break;
				}
				$resource['downloads'][$ipid][microtime(true)] = getIPIdentity(whitelistGetIP(true), true);
				if (!mkdir($currently = FONT_RESOURCES_SORTING . DIRECTORY_SEPARATOR .$state . DIRECTORY_SEPARATOR . $ipid. DIRECTORY_SEPARATOR . $row['font_id'], 0777, true))
					if (!is_dir($currently))
						die("Failed to make path: $currently");
				$filename = urldecode(str_replace('.zip', ".$state", $row['filename']));
				if (!$GLOBALS['FontsDB']->queryF($sql = "INSERT INTO `fonts_downloads` (`font_id`, `archive_id`, `filename`, `ip_id`, `when`) VALUES ('" . $row['font_id'] . "', '" . $row['id'] . "', '$filename', '$ipid', unix_timestamp())"))
					die("SQL Failed: $sql;");
				$expanded = 0;
				$output = array();
				$extract = getExtractionShellExec();
				$cmd = str_replace("%pack", $zip, str_replace("%path", $currently, (substr($extract['zip'],0,1)!="#"?$extract['zip']:substr($extract['zip'],1))));
				exec($cmd, $output);
				//$packed = getFileListAsArray($currently);
				$count = 0;
				foreach(getCompleteDirListAsArray($currently) as $path)
				{
					$packed[str_replace($currently.DIRECTORY_SEPARATOR, "", $path)] = getFileListAsArray($path);
					$count = $count + count($packed[str_replace($currently.DIRECTORY_SEPARATOR, "", $path)]);
				}
				if ($count<2)
					die("Path: $currently<br/>\nFailed: $cmd<br/>\n".implode("<br/>\n", $output));
				exec("rm -Rfv " . $unpacking);
				$cmd = "chmod -Rfv 0777 ".$currently;
				exec($cmd, $output);
				$filez = getFileListAsArray($currently);
				foreach(getCompleteDirListAsArray($currently) as $path)
				{
					$filez[str_replace($currently, "", $path)] = getFileListAsArray($path);
				}
				$resource['files'] = $filez;
				writeRawFile($currently . DIRECTORY_SEPARATOR . "font-resource.json", json_encode($resource));
				unlink($currently . DIRECTORY_SEPARATOR . "file.diz");
				writeRawFile($currently . DIRECTORY_SEPARATOR . "file.diz", getFileDIZ($row['font_id'], 0, $row['font_id'], $row['filename'], $expanded, $filez));
				$sortpath = FONTS_CACHE . DIRECTORY_SEPARATOR . $ipid. DIRECTORY_SEPARATOR . $row['font_id'];
				$output = array();
				$packing = getArchivingShellExec();
				$fontfiles = getCompleteFontsListAsArray($currently);
				foreach($fontfiles['ttf'] as $md5 => $preview)
				{
					if (isset($preview) && file_exists($preview))
					{
						require_once __DIR__ . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'WideImage' . DIRECTORY_SEPARATOR . 'WideImage.php';
						$img = WideImage::load(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'font-preview.png');
						$height = $img->getHeight();
						$lsize = 66;
						$ssize = 14;
						$step = mt_rand(8,11);
						$canvas = $img->getCanvas();
						$i=0;
						while($i<$height)
						{
							$canvas->useFont($preview, $point = $ssize + ($lsize - (($lsize  * ($i/$height)))), $img->allocateColor(0, 0, 0));
							$canvas->writeText(19, $i, "All Work and No Pay Makes Wishcraft a Dull Bored!");
							$i=$i+$point + $step;
						}
						$canvas->useFont($preview, 14, $img->allocateColor(0, 0, 0));
						$canvas->writeText('right', 'bottom', API_URL);
						$img->saveToFile($currently . DIRECTORY_SEPARATOR . 'Font Preview for '.getRegionalFontName($row['font_id']).'.jpg');
						$img->saveToFile($currently . DIRECTORY_SEPARATOR . 'Font Preview for '.getRegionalFontName($row['font_id']).'.gif');
						$img->saveToFile($currently . DIRECTORY_SEPARATOR . 'Font Preview for '.getRegionalFontName($row['font_id']).'.png');
						unset($img);
						$title = getRegionalFontName($row['font_id']);
						if (strlen($title)<=9)
							$img = WideImage::load(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'font-title-small.png');
						elseif (strlen($title)<=18)
							$img = WideImage::load(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'font-title-medium.png');
						elseif (strlen($title)<=35)
							$img = WideImage::load(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'font-title-large.png');
						elseif (strlen($title)>=36)
							$img = WideImage::load(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'font-title-extra.png');
						$canvas->useFont($preview, 78, $img->allocateColor(0, 0, 0));
						$canvas->writeText('center', 'center', $title);
						$img->saveToFile($currently . DIRECTORY_SEPARATOR . 'font-name-banner.jpg');
						$img->saveToFile($currently . DIRECTORY_SEPARATOR . 'font-name-banner.gif');
						$img->saveToFile($currently . DIRECTORY_SEPARATOR . 'font-name-banner.png');
						unset($img);
					}
				}
				chdir($currently);
				$filelist = getCompleteFilesListAsArray($currently);
				$cmd = (substr($packing['zip'],0,1)!="#"?DIRECTORY_SEPARATOR . "usr" . DIRECTORY_SEPARATOR . "bin" . DIRECTORY_SEPARATOR:'') . str_replace("%filelist", "\"".implode("\" \"", $filelist)."\"", str_replace("%folder", "./", str_replace("%pack", $packfile = ($sortpath . (substr($sortpath, strlen($sortpath)-1, 1)!=DIRECTORY_SEPARATOR?DIRECTORY_SEPARATOR:"") . $row['filename']), str_replace("%commentfile", "./file.diz", (substr($packing['zip'],0,1)!="#"?$packing['zip']:substr($packing['zip'],1))))));
				exec($cmd, $output);
				switch($font['medium'])
				{
					case 'FONT_RESOURCES_CACHE':
					case 'FONT_RESOURCES_RESOURCE':
						if (!is_dir(FONT_RESOURCES_RESOURCE . $row['path']))
							mkdir(FONT_RESOURCES_RESOURCE . $row['path'], 0777, true);
						if (!copy($packfile, FONT_RESOURCES_RESOURCE . $row['path'] . DIRECTORY_SEPARATOR . $row['filename']))
						{
							die("Failed: $cmd<br/>\n<br/>\n" .implode("<br/>\n", $output));
						}
						break;
				}
				$output = array();
				if (!is_dir($sortpath))
					mkdir($sortpath, 0777, true);
				chdir($currently);
				$cmd = "chmod -Rfv 0777 ".FONTS_CACHE;
				exec($cmd, $output);
				$output = array();
				$cmd = (substr($packing[$state],0,1)!="#"?DIRECTORY_SEPARATOR . "usr" . DIRECTORY_SEPARATOR . "bin" . DIRECTORY_SEPARATOR:'') . str_replace("%filelist", "\"".implode("\" \"", $filelist)."\"", str_replace("%folder", "./", str_replace("%pack", $packfile = ($sortpath . (substr($sortpath, strlen($sortpath)-1, 1)!=DIRECTORY_SEPARATOR?DIRECTORY_SEPARATOR:"") . $filename), str_replace("%commentfile", "./file.diz", (substr($packing[$state],0,1)!="#"?$packing[$state]:substr($packing[$state],1))))));
				exec($cmd, $output);		
	
				if (file_exists($packfile))
				{
					$resultb = $GLOBALS['FontsDB']->queryF($sql = "SELECT * FROM `fonts_callbacks` WHERE `failed` <= unix_timestamp() - (3600 * 6) AND LENGTH(`uri`) > 0 AND `type` IN ('archive') AND `font_id` = '" . $row['font_id'] . "'");
					while($callback = $GLOBALS['FontsDB']->fetchArray($resultb))
					{
						@setCallBackURI($callback['uri'], 145, 145, array_merge(array('format' => $output, 'downloads' => $font['downloaded']+1, 'font-key' => $row['font_id'], 'ipid' => getIPIdentity('', true))), array('success'=>"UPDATE `fonts_callbacks` SET `calls` = `calls` + 1, `last` = UNIX_TIMESTAMP() WHERE `id` = '" . $callback['id'] . "'"));
					}
					switch($font['medium'])
					{
						case 'FONT_RESOURCES_CACHE':
						case 'FONT_RESOURCES_RESOURCE':
							$GLOBALS['FontsDB']->queryF($sql = 'UPDATE `fonts` SET `medium` = \'FONT_RESOURCES_RESOURCE\', downloaded` = `downloaded` + 1 WHERE `id` = \'' . $row['font_id'] . "'");
							$GLOBALS['FontsDB']->queryF($sql = "UPDATE `fonts_downloads` SET `fingerprint` = '".md5_file($packfile) . "' WHERE `font_id` = '" . $row['font_id'] . "' AND `archive_id` = '" . $row['id'] . "' AND `filename` = '$filename' AND `ipid` = '$ipid'");
							break;
						case 'FONT_RESOURCES_PEERS':
							$resultb = $GLOBALS['FontsDB']->queryF($sql = "SELECT * FROM `peers` WHERE `down` <= unix_timestamp() - (3600 * 6) AND `polinating` = 'yes' AND `peer-id` = '" . $font['peer_id'] . "'");
							while($peer = $GLOBALS['FontsDB']->fetchArray($resultb))
							{
								@setCallBackURI(sprintf($peer['api-uri'].$peer['api-uri-callback'], 'download'), 345, 345, array('font-id' => $row['font_id'], 'ip' => whitelistGetIP(true), 'ipid' => getIPIdentity(whitelistGetIP(true), true)), array('success'=>"UPDATE `peers` SET `called` = UNIX_TIMESTAMP() WHERE `peer-id` = '" . $peer['peer-id'] . "'"));
							}
							break;
					}
					unlink($packfile);
				}
			}
		default:
			
			break;
	}
	exit(0);
?>