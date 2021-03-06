<?php
	$file = file_get_contents("../websites.json");
	$test = file_get_contents("http://www.google.com");
	if(empty($test))
	{
		echo "Internet is down.";
		exit;
	}
	if(empty($file))
		exit;
	function cmp($a, $b)
	{
		return $a['date_start'] > $b['date_start'];
	}
	$details = json_decode($file, true);
	$contests = [];
	foreach($details['sites'] as $site_name => $site)
	{
		ob_start();		
		include("../" . $site);
		$t_contests = json_decode(ob_get_clean(), true);
		
		if(empty($t_contests) || (is_string($t_contests) && !strcmp($t_contests, "INV")))
		{
			echo $site_name . " needs updating.";
			continue;
		}
		$contests = array_merge($contests, $t_contests);
	}
	usort($contests, "cmp");
	require("../views/v_tabular.php");
?>
