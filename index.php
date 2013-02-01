<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim();
$app->get('/grab/:uri', function($uri) {
	echo "Hallo" . $uri;
});

$app->post('/grab', function() {
	$sUrl = isset($_GET['url']) ? $_GET['url'] : null;

	if(!file_exists(getFileName($sUrl))) {
		$sImageUrl = grabWebSite($sUrl);
	} else {
		$sImageUrl = getFileName($sUrl);
	}

	if(isset($sImageUrl) && file_exists($sImageUrl)) {
	      header('Location: ' . $sImageUrl);
	} else {
		echo "Fehler...";
	}
});

function getFileName($sUrl) {
	return md5($sUrl).'.png';
}

function grabWebSite($sUrl) {
        $sFilename = getFileName($sUrl);
        $sExecute = 'xvfb-run --server-args="-screen 0, 1024x768x24" cutycapt --url=' . $sUrl . ' --out=' . $sFilename;
        chdir(dirname(__FILE__));
        passthru($sExecute);
	return $sFilename;
}

$app->get('.*', function() {
	echo 'Usage http://shot.urks.org/grab/:uri or use the form
	<form action="/grab" method="POST">
		<input type="text" name="uri" />
	<input type="submit" />
</form>
';
});


$app->run();
