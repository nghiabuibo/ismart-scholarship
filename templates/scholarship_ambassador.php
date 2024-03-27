<?php

$name = $additional_info->{'Họ và tên'};
$cert_title = $additional_info->{'Tiêu đề giải thưởng'};
$cert_name = $additional_info->{'Tên giải thưởng'};

$scholarships = [
	[
		'value' => $additional_info->{'Học bổng'},
		'name' => $additional_info->{'Tên học bổng'},
		'name_en' => $additional_info->{'Tên học bổng (EN)'}
	],
	[
		'value' => $additional_info->{'Học bổng 2'},
		'name' => $additional_info->{'Tên học bổng 2'},
		'name_en' => $additional_info->{'Tên học bổng 2 (EN)'}
	]
];

$date_signed = $additional_info->{'Date'};
$date_expired = $additional_info->{'Thời hạn áp dụng'};
$date_expired_en = $additional_info->{'Thời hạn áp dụng (EN)'};

$pageLayout = [
	'certificate' => [
		'bg_path' => __DIR__ . '/../assets/pdf/ismart-certificate-ambassador.pdf',
		'dimension' => [1754, 1241]
	],
	'scholarship' => [
		'bg_path' => __DIR__ . '/../assets/pdf/ismart-scholarship-ambassador.pdf',
		'dimension' => [1754, 1241]
	]
];

use setasign\Fpdi\Tcpdf\Fpdi;

$pdf = new Fpdi('L', 'px', $pageLayout['certificate']['dimension'], true, 'UTF-8', false);

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
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
	require_once(dirname(__FILE__) . '/lang/eng.php');
	$pdf->setLanguageArray($l);
}



// ---------------------------------------------------------

// convert TTF font to TCPDF format and store it on the fonts folder
$mont = TCPDF_FONTS::addTTFfont(__DIR__ . '/../assets/fonts/Montserrat-SemiBold.ttf', 'TrueTypeUnicode', '', 96);
$mont_b = TCPDF_FONTS::addTTFfont(__DIR__ . '/../assets/fonts/Montserrat-Bold.ttf', 'TrueTypeUnicode', '', 96);
$mont_i = TCPDF_FONTS::addTTFfont(__DIR__ . '/../assets/fonts/Montserrat-Italic.ttf', 'TrueTypeUnicode', '', 96);
$mont_mi = TCPDF_FONTS::addTTFfont(__DIR__ . '/../assets/fonts/Montserrat-MediumItalic.ttf', 'TrueTypeUnicode', '', 96);
$mont_sb = TCPDF_FONTS::addTTFfont(__DIR__ . '/../assets/fonts/Montserrat-SemiBold.ttf', 'TrueTypeUnicode', '', 96);
$viceroy = TCPDF_FONTS::addTTFfont(__DIR__ . '/../assets/fonts/UTM_ViceroyJF.ttf', 'TrueTypeUnicode', '', 96);
$bt_xb = TCPDF_FONTS::addTTFfont(__DIR__ . '/../assets/fonts/BT-BeauSans-ExtraBold-BF64d45953a17ee.ttf', 'TrueTypeUnicode', '', 96);
$bt_m = TCPDF_FONTS::addTTFfont(__DIR__ . '/../assets/fonts/BT-BeauSans-Medium-BF64d4595383d81.ttf', 'TrueTypeUnicode', '', 96);

$certificate_width = $pageLayout['certificate']['dimension'][0];
$certificate_height = $pageLayout['certificate']['dimension'][1];
// set the source file
$pdf->setSourceFile($pageLayout['certificate']['bg_path']);

// add a page
$pdf->AddPage('L', $pageLayout['certificate']['dimension']);

// import certificate page 1
$tplId = $pdf->importPage(1);
// use the imported page and place it at point 0, 0 with a width equal to layout width (fullpage)
$pdf->useTemplate($tplId, 0, 0, $certificate_width);

$pdf->SetTextColor(60, 158, 216);
$pdf->SetFont($viceroy, '', 110, false);
$pdf->writeHTMLCell($certificate_width, 300, 0, 570, $name, 0, 0, false, true, 'C');

$pdf->SetTextColor(32, 49, 97);
$pdf->SetFont($bt_m, '', 45, false);
$pdf->writeHTMLCell($certificate_width, 200, 0, 730, $cert_title, 0, 0, false, true, 'C');

$pdf->SetFont($bt_xb, '', 60, false);
$pdf->writeHTMLCell($certificate_width, 200, 0, 790, $cert_name, 0, 0, false, true, 'C');

foreach ($scholarships as $scholarship) {
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
	$pdf->SetFont($mont_b, '', 70, false);
	$pdf->writeHTMLCell($scholarship_width, 300, 0, 520, $name, 0, 0, false, true, 'C');

	$pdf->SetFont($mont_b, '', 80, false);
	$pdf->writeHTMLCell($scholarship_width, 300, 0, 710, $scholarship['value'], 0, 0, false, true, 'C');

	$pdf->SetTextColor(239, 74, 36);
	$pdf->SetFont($mont_b, '', 30, false);
	$pdf->writeHTMLCell($scholarship_width, 200, 0, 820, $scholarship['name'], 0, 0, false, true, 'C');

	$pdf->SetTextColor(54, 54, 54);
	$pdf->SetFont($mont_mi, '', 24, false);
	$pdf->writeHTMLCell($scholarship_width, 200, 0, 860, $scholarship['name_en'], 0, 0, false, true, 'C');

	$pdf->SetTextColor(33, 33, 33);
	$pdf->SetFont($mont_i, '', 20, false);
	$pdf->writeHTMLCell(300, 100, 527, 954, $date_signed);

	$pdf->SetTextColor(44, 51, 147);
	$pdf->SetFont($mont_sb, '', 19, false);
	$pdf->writeHTMLCell(300, 50, 1271, 1112, $date_expired);

	$pdf->SetTextColor(33, 33, 33);
	$pdf->SetFont($mont_i, '', 18, false);
	$pdf->writeHTMLCell(300, 50, 1221, 1133, $date_expired_en);
}

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output(vn_to_en($name, true) . '_iSmart_Online_Scholarship.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+