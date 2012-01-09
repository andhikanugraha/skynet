<?php

// PDF

// create new PDF document
$pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', true);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Yayasan Bina Antarbudaya');
$pdf->SetTitle('Tanda Peserta Seleksi Bina Antarbudaya 2012');
// $pdf->SetSubject('TCPDF Tutorial');
// $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

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

// set font
$pdf->SetFont('helvetica', 'B', 14);

// set some text to print
$txt = <<<EOD
Tanda Peserta Seleksi Bina Antarbudaya

EOD;

$pdf->Image(HELIUM_PARENT_PATH . '/assets/logo.png', 140, 7.5, 60);

// print a block of text using Write()
$pdf->MultiCell(0, 15, 'Tanda Peserta Seleksi Bina Antarbudaya', 0, 'L', false, 1, 10, 10);

$pdf->setFont('helvetica', '', 9);

$pdf->setCellHeightRatio(1.6);

$code = $applicant->test_id;
// $code = "INAYPSc/12-13/" . str_pad($det->applicant_id, 4, '0', STR_PAD_LEFT);

$html = <<<EOD
<table>
<tr style="font-size: 300%; margin-top: -5mm">
	<td colspan="2"><b>$code</b></td>
</tr>
<tr>
	<td width="20%">Chapter:</td>
	<td width="80%"><b>{$applicant->chapter->chapter_name}</b></td>
</tr>
<tr>
	<td width="20%">Nama:</td>
	<td width="80%"><b>{$applicant->sanitized_full_name}</b></td>
</tr>
<tr>
	<td width="20%">Asal Sekolah:</td>
	<td width="80%"><b>{$applicant->sanitized_high_school_name}</b></td>
</tr>
<tr>
	<td width="20%">Alamat:</td>
	<td width="80%"><b>{$applicant->applicant_address_street}, {$applicant->applicant_address_city} {$applicant->applicant_address_postcode}</b></td>
</tr>
<tr>
	<td width="20%">Telepon:</td>
	<td width="80%"><b>{$applicant->applicant_mobilephone}</b></td>
</tr>
</table>
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

EOD;

$pdf->writeHTMLCell(150, 5, 10, 20, $html);
$code = Gatotkaca::build_url(array('controller' => 'applicant', 'action' => 'confirm', 'id' => $det->applicant_id));
$pdf->write2DBarcode($code, 'QRCODE', 170, 70, 30, 30);

$pdf->setFont('helvetica', '', 8);
$pdf->setCellHeightRatio(1.2);

$pdf->MultiCell(100, 23, 'Diisi oleh panitia', 1, 'L', false, false, 10, 115);
$pdf->MultiCell(204, 142, '', 1, 'L', false, false, 3, 3);

$pdf->setFont('helvetica', '', 8);
$pdf->Image(HELIUM_PARENT_PATH . '/assets/logo_afs.gif', 170, 120, 28.75, 23.875);
$pdf->MultiCell(30, 5, 'A Partner of', 0, 'L', false, false, 170, 118);

// print photo
if ($picture) {
	$picture_path = $picture->get_cropped_path();
	$pdf->Image($picture_path, 170, 25, 30, 40);
}

$pdf->setFont('helvetica', 'B', 12);
$pdf->MultiCell(210, 40, 'Gunting kartu peserta sesuai garis di atas.
Tanda peserta ini tidak boleh dilaminating.
TANDA PESERTA BELUM SAH SEBELUM PENGUMPULAN BERKAS.', 0, 'C', false, false, 0, 155);


// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('kartu-peserta.pdf', 'I');
exit;