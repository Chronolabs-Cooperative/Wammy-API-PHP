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

	global $domain, $protocol, $business, $entity, $contact, $referee, $peerings, $source;
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'header.php';
	
	$help=true;
	if (isset($_GET['output']) || !empty($_GET['output'])) {
		$version = isset($_GET['version'])?(string)$_GET['version']:'v2';
		$output = isset($_GET['output'])?(string)$_GET['output']:'';
		$name = isset($_GET['name'])?(string)$_GET['name']:'';
		$clause = isset($_GET['clause'])?(string)$_GET['clause']:'';
		$callback = isset($_REQUEST['callback'])?(string)$_REQUEST['callback']:'';
		$mode = isset($_GET['mode'])?(string)$_GET['mode']:'';
		$state = isset($_GET['state'])?(string)$_GET['state']:'';
		switch($output)
		{
			case "forms":
				if (in_array($mode, array('test','training')))
				{
					$help=false;
					if (empty($clause) && isset($_POST['return']))
						$clause = $_POST['return'];
				}
				break;
		}
	} else {
		$help=true;
	}
	
	if ($help==true) {
		if (function_exists('http_response_code'))
			http_response_code(400);
		include dirname(__FILE__).'/help.php';
		exit;
	}
	
	switch($output)
	{
		case "forms":
			if (function_exists('http_response_code'))
				http_response_code(201);
			die(getHTMLForm($mode, $clause, $callback, $output, $version));
			break;
	}
	
	if (function_exists('http_response_code'))
		http_response_code(200);
	
	switch ($output) {
		default:
			echo $data;
			break;
		case "ufo":
			if (!strpos($data, "xml"))
				header('Content-type: text/html');
			else
				header('Content-type: application/xml');
			echo $data;
			break;
		case 'html':
			echo '<h1>' . $country . ' - ' . $place . ' (Places data)</h1>';
			echo '<pre style="font-family: \'Courier New\', Courier, Terminal; font-size: 0.77em;">';
			echo implode("\n", $data);
			echo '</pre>';
			break;
		case 'raw':
			echo implode("} | {", $data);
			break;
		case 'json':
			header('Content-type: application/json');
			echo json_encode($data);
			break;
		case 'serial':
			header('Content-type: text/plain');
			echo serialize($data);
			break;
		case 'xml':
			header('Content-type: application/xml');
			$dom = new XmlDomConstruct('1.0', 'utf-8');
			$dom->fromMixed(array('root'=>$data));
 			echo $dom->saveXML();
			break;
		case "css":
			header('Content-type: text/css');
			echo implode("\n\n", $data);
			break;
		case "preview":
			header('Content-type: text/html');
			echo $data;
			break;
		case "rss":
			header('Content-type: application/rss+xml');
			echo $data;
			break;
		case "diz":
			header('Content-type: text/plain');
			echo $data;
			break;
	}
	
	// Checks Cache for Cleaning
	@cleanResourcesCache();
?>		