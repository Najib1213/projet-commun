<?php
session_start();

require_once '../Modèles/database.php';
require_once '../Modèles/db_pg.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';
require('fpdf/fpdf.php');
require('fpdf/makefont/makefont.php');

// Vérification utilisateur (connexion locale)
if (!isset($_SESSION['user']['email']) || empty($_POST['destinataire'])) {
    die("Erreur d'accès.");
}
$expediteur = $_SESSION['user']['email'];
$destinataire = filter_var($_POST['destinataire'], FILTER_VALIDATE_EMAIL);
if (!$destinataire) die("Destinataire non valide.");

// Connexion capteurs
$pdoCapteurs = $pdo;

// Récupération des données
function getLastValue($pdoCapteurs, $sql) {
    $stmt = $pdoCapteurs->query($sql);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
$gaz         = getLastValue($pdoCapteurs, "SELECT cap_fumee_val AS value_percent, cap_fumee_time AS recorded_at FROM pc_g8 ORDER BY cap_fumee_time DESC LIMIT 1");
$humidite    = getLastValue($pdoCapteurs, "SELECT hum AS value_percent, temps AS recorded_at FROM capteur_hum_temp ORDER BY temps DESC LIMIT 1");
$temperature = getLastValue($pdoCapteurs, "SELECT temp AS value_percent, temps AS recorded_at FROM capteur_hum_temp ORDER BY temps DESC LIMIT 1");
$luminosite  = getLastValue($pdoCapteurs, "SELECT value_percent, recorded_at FROM luminosity_readings ORDER BY recorded_at DESC LIMIT 1");
$son         = getLastValue($pdoCapteurs, "SELECT db_level AS value_percent, created_at AS recorded_at FROM sound_measurements ORDER BY created_at DESC LIMIT 1");

// HTML/CSS commun pour l'email et pour la version PDF (autant que possible)
$html_table = '
<style>
.table-sensor {
    border-collapse: collapse;
    width: 100%;
    margin: 16px 0;
    font-family: Arial, sans-serif;
}
.table-sensor th, .table-sensor td {
    border: 1px solid #6e6e6e;
    padding: 8px 12px;
    text-align: center;
}
.table-sensor th {
    background-color: #0a6bbf;
    color: #fff;
}
.table-sensor tr:nth-child(even) {
    background-color: #f2f2f2;
}
.table-sensor tr:nth-child(odd) {
    background-color: #ffffff;
}
.header-title {
    color: #0a6bbf;
    font-size: 22px;
    margin-bottom: 20px;
    text-align: center;
    font-family: Arial, sans-serif;
}
</style>
<div class="header-title">État des capteurs à un instant T</div>
<table class="table-sensor">
    <tr>
        <th>Type</th>
        <th>Valeur</th>
        <th>Date</th>
    </tr>
    <tr>
        <td>Son</td>
        <td>' . ($son && isset($son['value_percent']) ? $son['value_percent'] . ' dB' : 'aucune donnée') . '</td>
        <td>' . ($son && isset($son['recorded_at']) ? $son['recorded_at'] : '-') . '</td>
    </tr>
    <tr>
        <td>Lumière</td>
        <td>' . ($luminosite && isset($luminosite['value_percent']) ? $luminosite['value_percent'] . ' lux' : 'aucune donnée') . '</td>
        <td>' . ($luminosite && isset($luminosite['recorded_at']) ? $luminosite['recorded_at'] : '-') . '</td>
    </tr>
    <tr>
        <td>Gaz (fumée)</td>
        <td>' . ($gaz && isset($gaz['value_percent']) ? $gaz['value_percent'] . ' %' : 'aucune donnée') . '</td>
        <td>' . ($gaz && isset($gaz['recorded_at']) ? $gaz['recorded_at'] : '-') . '</td>
    </tr>
    <tr>
        <td>Température</td>
        <td>' . ($temperature && isset($temperature['value_percent']) ? $temperature['value_percent'] . ' °C' : 'aucune donnée') . '</td>
        <td>' . ($temperature && isset($temperature['recorded_at']) ? $temperature['recorded_at'] : '-') . '</td>
    </tr>
    <tr>
        <td>Humidité</td>
        <td>' . ($humidite && isset($humidite['value_percent']) ? $humidite['value_percent'] . ' %' : 'aucune donnée') . '</td>
        <td>' . ($humidite && isset($humidite['recorded_at']) ? $humidite['recorded_at'] : '-') . '</td>
    </tr>
</table>
';

// Génération du PDF avec FPDF (on va faire un tableau qui reprend un peu la couleur du site)
class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial','B',18);
        $this->SetTextColor(10,107,191); // #0a6bbf
        $this->Cell(0,10,utf8_decode('Etat des capteurs à un instant T'),0,1,'C');
        $this->Ln(4);
    }
    function SensorTable($header, $data)
    {
        // Couleurs
        $this->SetFillColor(10,107,191);
        $this->SetTextColor(255,255,255);
        $this->SetDrawColor(110,110,110);
        $this->SetLineWidth(.3);
        $this->SetFont('Arial','B',12);
        // Header
        $w = array(60, 40, 60);
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],8,utf8_decode($header[$i]),1,0,'C',true);
        $this->Ln();
        // Données
        $this->SetFont('Arial','',11);
        $this->SetTextColor(0);
        $fill = false;
        foreach($data as $row) {
            $this->Cell($w[0],8,utf8_decode($row[0]),'LR',0,'C',$fill);
            $this->Cell($w[1],8,utf8_decode($row[1]),'LR',0,'C',$fill);
            $this->Cell($w[2],8,utf8_decode($row[2]),'LR',0,'C',$fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell(array_sum($w),0,'','T');
    }
}
$pdf = new PDF();
$pdf->AddPage();
// Tableau
$header = array('Type', 'Valeur', 'Date');
$data = array(
    array('Son', ($son && isset($son['value_percent']) ? $son['value_percent'].' dB' : 'aucune donnée'), ($son && isset($son['recorded_at']) ? $son['recorded_at'] : '-')),
    array('Lumière', ($luminosite && isset($luminosite['value_percent']) ? $luminosite['value_percent'].' lux' : 'aucune donnée'), ($luminosite && isset($luminosite['recorded_at']) ? $luminosite['recorded_at'] : '-')),
    array('Gaz (fumée)', ($gaz && isset($gaz['value_percent']) ? $gaz['value_percent'].' %' : 'aucune donnée'), ($gaz && isset($gaz['recorded_at']) ? $gaz['recorded_at'] : '-')),
    array('Température', ($temperature && isset($temperature['value_percent']) ? $temperature['value_percent'].' °C' : 'aucune donnée'), ($temperature && isset($temperature['recorded_at']) ? $temperature['recorded_at'] : '-')),
    array('Humidité', ($humidite && isset($humidite['value_percent']) ? $humidite['value_percent'].' %' : 'aucune donnée'), ($humidite && isset($humidite['recorded_at']) ? $humidite['recorded_at'] : '-')),
);
$pdf->SensorTable($header, $data);
$pdfFile = 'export_capteurs_'.time().'.pdf';
$pdf->Output('F', $pdfFile);

// Envoi du mail HTML stylé
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
    $mail->Subject = 'Etat instantané des capteurs';
    $mail->Body    = '
        <div style="font-family: Arial, sans-serif;">
        <p>Veuillez trouver en pièce jointe l\'état instantané des capteurs.</p>
        '.$html_table.'
        <p style="margin-top:20px;">Cordialement,<br>'.$expediteur.'</p>
        </div>
    ';
    $mail->addAttachment($pdfFile);

    $mail->send();
    unlink($pdfFile);

    echo "<script>alert('Mail envoyé avec succès !');window.location='capteur.php';</script>";
} catch (Exception $e) {
    unlink($pdfFile);
    echo "Erreur d'envoi: ", $mail->ErrorInfo;
}
?>