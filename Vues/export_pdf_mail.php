<?php
session_start();
require_once '../config/connexion_bdd.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';


if (!isset($_SESSION['user']['email']) || empty($_POST['destinataire'])) {
    die("Erreur d'accès.");
}
$expediteur = $_SESSION['user']['email'];
$destinataire = filter_var($_POST['destinataire'], FILTER_VALIDATE_EMAIL);
if (!$destinataire) die("Destinataire non valide.");

// Exemple : Récupération des données capteurs (adapte la requête selon ta table !)
$stmt = $pdo->query("SELECT * FROM mesures ORDER BY date_prise DESC LIMIT 20");
$mesures = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Génération du PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 12, 'Export des données capteurs', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(6);
foreach ($mesures as $m) {
    $pdf->Cell(0, 8, "Capteur #{$m['capteur_id']} : {$m['valeur']} (le {$m['date_prise']})", 0, 1);
}

$pdfFile = 'export_capteurs_'.time().'.pdf';
$pdf->Output('F', $pdfFile);

// Envoi du mail
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'ferreira.dias.arthur@gmail.com';
    $mail->Password   = 'nnxh zchh anko qsob';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom($expediteur, 'Export Capteurs');
    $mail->addAddress($destinataire);
    $mail->isHTML(true);
    $mail->Subject = 'Export des données capteurs';
    $mail->Body    = 'Veuillez trouver en pièce jointe l\'export des données récentes des capteurs.<br>Cordialement,<br>'.$expediteur;
    $mail->addAttachment($pdfFile);

    $mail->send();
    unlink($pdfFile);

    echo "<script>alert('Mail envoyé avec succès !');window.location='capteur.php';</script>";
} catch (Exception $e) {
    unlink($pdfFile);
    echo "Erreur d'envoi: ", $mail->ErrorInfo;
}
?>