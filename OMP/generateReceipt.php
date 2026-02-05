<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;
session_start();

if(!isset($_SESSION['receipt'])) { echo "No receipt available."; exit; }

$data = $_SESSION['receipt'];
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$html = "<h2>Session Booking Receipt</h2>
<p>Name: {$data['name']}</p>
<p>Phone: {$data['phone']}</p>
<p>Address: {$data['address']}</p>
<p>Therapist: {$data['therapist_name']} ({$data['therapist_specialty']})</p>
<p>Date: {$data['date']}</p>
<p>Time: {$data['time']}</p>
<p>Fee: Rs. {$data['session_price']}</p>";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4','portrait');
$dompdf->render();
$dompdf->stream("BookingReceipt_{$data['patient_number']}.pdf", ["Attachment"=>true]);
unset($_SESSION['receipt']);
exit();
