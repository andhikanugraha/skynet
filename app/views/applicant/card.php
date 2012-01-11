<?php


// PDF

// create new PDF document
$pdf = new SkynetPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', true);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Yayasan Bina Antarbudaya');
$pdf->SetTitle('Tanda Peserta Seleksi Bina Antarbudaya 2012');
// $pdf->SetSubject('TCPDF Tutorial');
// $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->setSourceFile(HELIUM_PARENT_PATH . '/assets/kartu-peserta-tpl.pdf');

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(10, 10, 10, 10);

//set auto page breaks
$pdf->SetAutoPageBreak(FALSE, 0);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------


// add a page
$pdf->AddPage();

$pdf->useTemplate($pdf->importPage(1));
// set font
$pdf->SetFont('helvetica', 'B', 14);

// set some text to print
$txt = <<<EOD
Tanda Peserta Seleksi Bina Antarbudaya

EOD;

// $pdf->Image(HELIUM_PARENT_PATH . '/assets/logo.png', 140, 7.5, 60);

// print a block of text using Write()
// $pdf->MultiCell(0, 15, 'Tanda Peserta Seleksi Bina Antarbudaya', 0, 'L', false, 1, 10, 10);

$pdf->setFont('helvetica', '', 9);

$pdf->setCellHeightRatio(1.6);

$code = $applicant->test_id;
// $code = "INAYPSc/12-13/" . str_pad($det->applicant_id, 4, '0', STR_PAD_LEFT);
$pdf->setFont('helvetica', 'B', 28);
$pdf->MultiCell(180, 30, $code, 0, 'L', false, 1, 8.8, 28);
$pdf->setFont('helvetica', 'B', 12);
$pdf->MultiCell(120, 30, $applicant->sanitized_full_name, 0, 'L', false, 1, 8.8, 57.5);
$pdf->MultiCell(120, 30, $applicant->sanitized_high_school_name, 0, 'L', false, 1, 8.8, 69);
$pdf->setFont('helvetica', '', 12);
$st = str_replace("\n", ', ', $applicant->applicant_address_street);
$pdf->MultiCell(120, 30, "$st, {$applicant->applicant_address_city} {$applicant->applicant_address_postcode}", 0, 'L', false, 1, 8.8, 81);
$pdf->MultiCell(100, 30, $applicant->chapter->chapter_name, 0, 'L', false, 1, 98.8, 57.5);
$pdf->MultiCell(100, 30, $applicant->applicant_mobilephone, 0, 'L', false, 1, 98.8, 69);

$url = PathsComponent::build_url('/');
$pdf->setFont('helvetica', '', 9);
$pdf->MultiCell(180, 30, "Untuk informasi selengkapnya, kunjungi <a href=\"$url\">$url</a>", 0, 'L', false, 1, 8.8, 91, true, 0, true);

// print photo
if ($picture) {
	$picture_path = $picture->get_cropped_path();
	$pdf->Image($picture_path, 152, 25, 48, 64);
}

$code = PathsComponent::build_url(array('controller' => 'applicant', 'action' => 'view', 'id' => $applicant->id));
$pdf->write2DBarcode($code, 'QRCODE', 152, 94, 20, 20);

$pdf->write1DBarcode($applicant->test_id . chr(13), 'C93', 10, 42, 120, 8);

/*
<br>
Harap hadir pada <b>Seleksi Tahap Pertama</b> yang akan dilaksanakan pada<br>
Tanggal <b>1 Mei 2011</b> pukul <b>07.00 WIB</b> di <b>_____________________________</b>.
<br>
Apabila lulus Seleksi Tahap Pertama dapat mengikuti Seleksi Tahap Kedua pada<br>
Tanggal <b>__________</b> pukul <b>______ WIB</b> di <b>_____________________________</b>.
<br>
Apabila lulus Seleksi Tahap Kedua dapat mengikuti Seleksi Tahap Ketiga pada<br>
Tanggal <b>__________</b> pukul <b>______ WIB</b> di <b>_____________________________</b>.
<br><br>
Untuk informasi selengkapnya, kunjungi <a href="http://seleksi.binabudbdg.org/" style="color: black; text-decoration: none"><b>http://seleksi.binabudbdg.org/</b></a>
*/

// ---------------------------------------------------------

//Close and output PDF document
//TODO: Save this somewhere so we only need to generate once
$pdf->Output('kartu-peserta.pdf', 'I');
exit;