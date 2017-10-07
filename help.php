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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta property="og:title" content="<?php echo API_VERSION; ?>"/>
<meta property="og:type" content="api<?php echo API_TYPE; ?>"/>
<meta property="og:image" content="<?php echo API_URL; ?>/assets/images/logo_500x500.png"/>
<meta property="og:url" content="<?php echo (isset($_SERVER["HTTPS"])?"https://":"http://").$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; ?>" />
<meta property="og:site_name" content="<?php echo API_VERSION; ?> - <?php echo API_LICENSE_COMPANY; ?>"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="rating" content="general" />
<meta http-equiv="author" content="wishcraft@users.sourceforge.net" />
<meta http-equiv="copyright" content="<?php echo API_LICENSE_COMPANY; ?> &copy; <?php echo date("Y"); ?>" />
<meta http-equiv="generator" content="Chronolabs Cooperative (<?php echo $place['iso3']; ?>)" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo API_VERSION; ?> |~| <?php echo API_LICENSE_COMPANY; ?></title>
<!-- AddThis Smart Layers BEGIN -->
<!-- Go to http://www.addthis.com/get/smart-layers to customize -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50f9a1c208996c1d"></script>
<script type="text/javascript">
  addthis.layers({
	'theme' : 'transparent',
	'share' : {
	  'position' : 'right',
	  'numPreferredServices' : 6
	}, 
	'follow' : {
	  'services' : [
		{'service': 'facebook', 'id': 'Chronolabs'},
		{'service': 'twitter', 'id': 'JohnRingwould'},
		{'service': 'twitter', 'id': 'ChronolabsCoop'},
		{'service': 'twitter', 'id': 'Cipherhouse'},
		{'service': 'twitter', 'id': 'OpenRend'},
	  ]
	},  
	'whatsnext' : {},  
	'recommended' : {
	  'title': 'Recommended for you:'
	} 
  });
</script>
<!-- AddThis Smart Layers END -->
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/style.css" type="text/css" />
<!-- Custom Fonts -->
<link href="<?php echo API_URL; ?>/assets/media/Labtop/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Bold Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Superwide Boldish/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Thin/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Unicase/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/LHF Matthews Thin/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Life BT Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Life BT Bold Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite Normal/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/gradients.php" type="text/css" />
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/shadowing.php" type="text/css" />

</head>
<body>
<div class="main">
    <img style="float: right; margin: 11px; width: auto; height: auto; clear: none;" src="<?php echo API_URL; ?>/assets/images/logo_350x350.png" />
    <h1><?php echo API_VERSION; ?> |~| <?php echo API_LICENSE_COMPANY; ?></h1>
    <p style="text-align: justify; font-size: 169.2356897%; font-weight: 400">This is an API Service for testing text or html content for spam, it will return either by callback or directly on call a hierestic score and marking for the content to allow you with estimated type in the array return!</p>
    <h2>Code API Documentation</h2>
    <p>You can find the phpDocumentor code API documentation at the following path :: <a href="<?php echo API_URL; ?>/docs/" target="_blank"><?php echo API_URL; ?>/docs/</a>. These should outline the source code core functions and classes for the API to function!</p>   
	<h2>Test for Spam/Ham based on AI Linguistic Training Engines</h2>
    <p>This is done with the <em>test.api</em> + <em>forms.api</em> extension at the end of the url, you can test for ham or spam on the API with this system!</p>
    <blockquote>
        <?php echo $testform = getHTMLForm('test'); ?>
		<h3>Code Example:</h3>
		<div style="max-height: 375px; overflow: scroll;">
			<pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
<?php echo htmlspecialchars($testform); ?>
			</pre>
		</div>
    </blockquote>
    <p>Does a test of an image supported by imagemagic for spam/ham testing!</p>
    <blockquote>
        <?php echo $testform = getHTMLForm('image-test'); ?>
		<h3>Code Example:</h3>
		<div style="max-height: 375px; overflow: scroll;">
			<pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
<?php echo htmlspecialchars($testform); ?>
			</pre>
		</div>
    </blockquote>
    <h2>API Training FORMS Document Output</h2>
    <p>This is done with the <em>training.api</em> + <em>forms.api</em> extension at the end of the url, this form will submit content to be analysed and used in meta for detecting spam or ham or to be forgotten from the testing matrix!</p>
    <blockquote>
        <?php echo $trainingform = getHTMLForm('training'); ?>
		<h3>Code Example:</h3>
		<div style="max-height: 375px; overflow: scroll;">
			<pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
<?php echo htmlspecialchars($trainingform); ?>
			</pre>
		</div>
    </blockquote>
    <p>Does a training with an image of an image supported by imagemagic for spam/ham testing!</p>
    <blockquote>
        <?php echo $trainingform = getHTMLForm('image-training'); ?>
		<h3>Code Example:</h3>
		<div style="max-height: 375px; overflow: scroll;">
			<pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
<?php echo htmlspecialchars($trainingform); ?>
			</pre>
		</div>
    </blockquote>
    <h2>FORMS Document Output</h2>
    <p>This is done with the <em>forms.api</em> extension at the end of the urland will provide a HTML Submission form for the API in options the only modal for this at the moment is an Upload form!</p>
    <blockquote>
    <font color="#001201">The following examples for <em>forms.api</em> uses the cURL function <strong>getURIData()</strong> in PHP to use the example below in PHP!</font><br/><br/>
    <pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
&lt;?php
	if (!function_exists("getURIData")) {
	
		/* function getURIData() cURL Routine
		 * 
		 * @author 		Simon Roberts (labs.coop) wishcraft@users.sourceforge.net
		 * @return 		string
		 */
		function getURIData($uri = '', $timeout = 25, $connectout = 25, $post_data = array())
		{
			if (!function_exists("curl_init"))
			{
				return file_get_contents($uri);
			}
			if (!$btt = curl_init($uri)) {
				return false;
			}
			curl_setopt($btt, CURLOPT_HEADER, 0);
			curl_setopt($btt, CURLOPT_POST, (count($posts)==0?false:true));
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
?&gt;

		</pre><br/><br/>
        <font color="#001201">You basically import and output to the buffer the HTML Submission form for the form to test content as or for spam at the following URI: <strong><?php echo API_URL; ?>/v4/test/forms.api</strong> -- this will generate a HTML form with the return path specified for you to buffer -- see example below in PHP!</font><br/>
		<pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
&lt;?php
	// output the table & form
	echo getURIData("<?php echo API_URL; ?>/v4/test/forms.api", 560, 560, 
	
				 /* URL Variables passed as $_POST (required) */
				array('return' => '<?php echo API_URL . '/'; ?>', 
				      'callback' => '<?php echo API_URL; ?>/v4/uploads/callback.api'
				      'sender-ip' => '<?php echo whitelistGetIP(true); ?>',
				      'usernames' => array('sender'=>'guest', 'recipient' => 'webmaster'),
				      'mimetype' => 'text/plain',
				      'mode' => 'json'));
?&gt;
		</pre><br/><br/>
		<font color="#001201">You basically import and output to the buffer the HTML Submission form for training of the spam detection hierestics at the following URI: <strong><?php echo API_URL; ?>/v4/training/forms.api</strong> -- this will generate a HTML form with the return path specified for you to buffer -- see example below in PHP!</font><br/>
		<pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
&lt;?php
	// output the table & form
	echo getURIData("<?php echo API_URL; ?>/v4/training/forms.api", 560, 560, 
	
				 /* URL Variables sent as $_POST (required) */
				array('return' => '<?php echo API_URL . '/'; ?>', 
				      'callback' => '<?php echo API_URL; ?>/v4/uploads/callback.api'
				      'sender-ip' => '<?php echo whitelistGetIP(true); ?>',
				      'usernames' => array('sender'=>'guest', 'recipient' => 'webmaster'),
				      'mimetype' => 'text/plain',
				      'mode' => 'spam'));
?&gt;
		</pre>
		 <font color="#2e31c1; font-size: 134%; font-weight: 900;">An example of the callback routines the variables are outlined in this file you click and download the PHP Routines examples: <a href="/callback-example.php" target="_blank">callback-example.php</a></font>
    </blockquote>   
  	<?php if (file_exists($fionf = __DIR__ .  DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'apis-localhost.html')) {
    	readfile($fionf);
    }?>	
    <?php if (!in_array(whitelistGetIP(true), whitelistGetIPAddy())) { ?>
    <h2>Limits</h2>
    <p>There is a limit of <?php echo MAXIMUM_QUERIES; ?> queries per hour. You can add yourself to the whitelist by using the following form API <a href="http://whitelist.<?php echo domain; ?>/">Whitelisting form (whitelist.<?php echo domain; ?>)</a>. This is only so this service isn't abused!!</p>
    <?php } ?>
    <h2>The Author</h2>
    <p>This was developed by Simon Roberts in 2013 and is part of the Chronolabs System and api's.<br/><br/>This is open source which you can download from <a href="https://sourceforge.net/projects/chronolabsapis/">https://sourceforge.net/projects/chronolabsapis/</a> contact the scribe  <a href="mailto:wishcraft@users.sourceforge.net">wishcraft@users.sourceforge.net</a></p></body>
</div>
</html>
<?php 
