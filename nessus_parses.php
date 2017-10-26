<?php
$files = array();
$dir = opendir('.');
function cleanup_text($string) {
	$string = str_replace("\n", "", $string);
	$string = str_replace("\r", "", $string);
	return $string;
}
while(false != ($file = readdir($dir))) {
		if (preg_match("/.nessus/", $file)) {
                $files[] = $file; 
        }   
}
foreach($files as $file) {
	$reports=simplexml_load_file($file) or die("Error: Cannot create object");
	$count_host = 0;
	foreach($reports->Report->ReportHost as $host) { 
		$count_host++;
	}
	$output_file = $reports->Report['name'] . "[" . date("ymdHis") . "].csv";
	//$output_file = fopen($reports->Report['name'] . "[" . date("ymdHis") . "].csv", 'w');
	$output = "";
	for ($i=0;$i<$count_host;$i++) {
		$host =  $reports->Report->ReportHost[$i]['name'];
		$count_issue = 0;
		foreach($reports->Report->ReportHost[$i]->ReportItem as $issue) {
			$port =  $reports->Report->ReportHost[$i]->ReportItem[$count_issue]['port'] . "/" . $reports->Report->ReportHost[$i]->ReportItem['protocol'];
			$name = $reports->Report->ReportHost[$i]->ReportItem[$count_issue]->plugin_name;
			$synopsis = cleanup_text($reports->Report->ReportHost[$i]->ReportItem[$count_issue]->synopsis);
			$risk_factor = $reports->Report->ReportHost[$i]->ReportItem[$count_issue]->risk_factor;
			$solution = cleanup_text($reports->Report->ReportHost[$i]->ReportItem[$count_issue]->solution);
			if ($risk_factor != "None") {
				$output .= "\"$host\",\"$name\",\"$port\",\"$risk_factor\",\"$synopsis\",\"$solution\"\n";
			}
			$count_issue++;
		}
	}
	file_put_contents($output_file, $output);
}
?>
