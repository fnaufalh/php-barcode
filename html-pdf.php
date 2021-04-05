<?php

// Include autoloader 
require_once 'dompdf/autoload.inc.php';

// Reference the Dompdf namespace 
use Dompdf\Dompdf;

$json = file_get_contents("doc/prepare.json");
$array = json_decode($json, true);

for ($arr = 0; $arr < count($array); $arr++) {
    // Instantiate and use the dompdf class 
    $dompdf = new Dompdf();

    $fileTitle =
        $array[$arr]['description'] . '_' . $array[$arr]['style'] . '_' . $array[$arr]['colour'] . '_' . $array[$arr]['size'] . '_' . $array[$arr]['ean'];
    $replacedTitle = str_replace(' ', '_', $fileTitle);

    // Load content from html file 
    $html = file_get_contents($fileTitle . ".html");
    $dompdf->loadHtml($html);

    // (Optional) Setup the paper size and orientation 
    // $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF 
    $dompdf->render();

    // Output the generated PDF (1 = download and 0 = preview) 
    $dompdf->stream($fileTitle, array("Attachment" => 0));
}