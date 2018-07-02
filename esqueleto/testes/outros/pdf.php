<?php

require '../../vendor/autoload.php';

use mikehaertl\wkhtmlto\Pdf;

$pdf = new Pdf(array(
    'print-media-type',
//    'margin-bottom' => 10,
//    'margin-left' => 10,
//    'margin-right' => 10,
//    'margin-top' => 10,
    
//    'background'
//    'debug-javascript',
//    'javascript-delay' => 20000,
));

//$pdf->addPage('http://www.flotcharts.org');
$pdf->addPage('pdf/index.html');
//$pdf->addPage('pdf/flot/examples/series-pie/index.html');

$pdf->send('report.pdf');