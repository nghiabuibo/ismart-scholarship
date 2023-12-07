<?php

$name = $params[1];
$scholarship_amount = $additional_info[0];
$date_signed = $additional_info[1];
$date_expired = $additional_info[2];

$pageLayout = [
	'scholarship' => [
		'bg_path' => __DIR__.'/../assets/pdf/ismart-scholarship.pdf',
		'dimension' => [1754, 1241]
	]
];

use setasign\Fpdi\Tcpdf\Fpdi;
$pdf = new Fpdi('L', 'px', $pageLayout['scholarship']['dimension'], true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('iSmart Online');
$pdf->SetTitle('iSmart Online Scholarship');
$pdf->SetSubject('iSmart Online');
$pdf->SetKeywords('iSmart Online, Scholarship');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}



// ---------------------------------------------------------

// convert TTF font to TCPDF format and store it on the fonts folder
$mont = TCPDF_FONTS::addTTFfont(__DIR__.'/../assets/fonts/Montserrat-SemiBold.ttf', 'TrueTypeUnicode', '', 96);
$mont_b = TCPDF_FONTS::addTTFfont(__DIR__.'/../assets/fonts/Montserrat-Bold.ttf', 'TrueTypeUnicode', '', 96);
$mont_i = TCPDF_FONTS::addTTFfont(__DIR__.'/../assets/fonts/Montserrat-Italic.ttf', 'TrueTypeUnicode', '', 96);

$scholarship_width = $pageLayout['scholarship']['dimension'][0];
$scholarship_height = $pageLayout['scholarship']['dimension'][1];
// set the source file
$pdf->setSourceFile($pageLayout['scholarship']['bg_path']);

// add a page
$pdf->AddPage('L', $pageLayout['scholarship']['dimension']);

// import scholarship page 1
$tplId = $pdf->importPage(1);
// use the imported page and place it at point 0, 0 with a width equal to layout width (fullpage)
$pdf->useTemplate($tplId, 0, 0, $scholarship_width);

$pdf->SetTextColor(44, 51, 147);
$pdf->SetFont($mont_b, '', 80, false);
$pdf->writeHTMLCell($scholarship_width, 300, 0, 520, $name, 0, 0, false, true, 'C');

$pdf->SetFont($mont_b, '', 80, false);
$pdf->writeHTMLCell($scholarship_width, 300, 0, 710, $scholarship_amount, 0, 0, false, true, 'C');

$pdf->SetTextColor(33, 33, 33);
$pdf->SetFont($mont_i, '', 20, false);
$pdf->writeHTMLCell(300, 100, 532, 954, $date_signed);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output(vn_to_en($name, true).'_iSmart_Online_Scholarship.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+