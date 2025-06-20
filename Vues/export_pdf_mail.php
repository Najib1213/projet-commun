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

// HTML/CSS amélioré pour l'email
$html_table = '
<style>
:root {
    --color-principal: #0a6bbf;
    --color-secondaire: #f5f8fa;
    --color-thead: #e7f1fa;
    --color-border: #d1e3f1;
    --color-txt-dark: #222;
    --color-txt-light: #fff;
}

.card-sensor {
    max-width: 560px;
    margin: 32px auto;
    background: var(--color-secondaire);
    border-radius: 18px;
    box-shadow: 0 8px 32px rgba(10,107,191,0.10);
    padding: 24px 26px 32px 26px;
    font-family: "Segoe UI", Arial, sans-serif;
    color: var(--color-txt-dark);
}
.header-title {
    color: var(--color-principal);
    font-size: 27px;
    margin-bottom: 18px;
    font-weight: 700;
    text-align: center;
    letter-spacing: 1px;
}
.table-sensor {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    margin: 20px 0 8px 0;
    font-size: 15px;
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 12px 0 rgba(10,107,191,0.04);
}
.table-sensor th, .table-sensor td {
    padding: 12px 14px;
    text-align: center;
}
.table-sensor th {
    background: var(--color-thead);
    color: var(--color-principal);
    font-size: 15.5px;
    border-bottom: 2px solid var(--color-principal);
}
.table-sensor tr {
    border-bottom: 1px solid var(--color-border);
}
.table-sensor tr:last-child {
    border-bottom: none;
}
.table-sensor tr:nth-child(even) td {
    background: #f7fbfd;
}
.table-sensor tr:nth-child(odd) td {
    background: #fff;
}
@media (max-width: 600px) {
    .card-sensor { padding: 10px; }
    .table-sensor th, .table-sensor td { padding: 8px 4px; font-size: 14px;}
    .header-title { font-size: 20px; }
}
</style>
<div class="card-sensor">
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
</div>
';

// PDF modernisé (bleu, coins arrondis, header plus marqué, tableau plat)
class PDF extends FPDF
{
    function Header()
    {
        // Ombre légère
        $this->SetDrawColor(10, 107, 191);
        $this->SetLineWidth(0.6);
        $this->SetFillColor(247, 251, 253); // #f7fbfd
        $this->RoundedRect(8, 7, 194, 21, 5, '1234', 'F');
        $this->SetFont('Arial','B',20);
        $this->SetTextColor(10,107,191); // #0a6bbf
        $this->Cell(0,12,'Etat des capteurs a un instant T',0,1,'C');
        $this->Ln(7);
    }

    // Fonction pour arrondir les coins (nécessite une petite extension dans FPDF)
    function RoundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));

        $xc = $x+$w-$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k, ($hp-$y)*$k ));

        if (strpos($corners, '2')===false)
            $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k, ($hp-$y)*$k ));
        else
            $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);

        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k, ($hp-$yc)*$k ));
        if (strpos($corners, '3')===false)
            $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k, ($hp-($y+$h))*$k ));
        else
            $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);

        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k, ($hp-($y+$h))*$k ));
        if (strpos($corners, '4')===false)
            $this->_out(sprintf('%.2F %.2F l', $x*$k, ($hp-($y+$h))*$k ));
        else
            $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);

        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $x*$k, ($hp-$yc)*$k ));
        if (strpos($corners, '1')===false)
            $this->_out(sprintf('%.2F %.2F l', $x*$k, ($hp-$y)*$k ));
        else
            $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }
    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }

    function SensorTable($header, $data)
    {
        // Couleurs d'entête
        $this->SetFillColor(231,241,250); // thead bleu très clair
        $this->SetTextColor(10,107,191);
        $this->SetDrawColor(209,227,241);
        $this->SetLineWidth(.4);
        $this->SetFont('Arial','B',13);
        // Header
        $w = array(58, 38, 55);
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],11,$header[$i],1,0,'C',true);
        $this->Ln();
        // Données
        $this->SetFont('Arial','',12);
        $fill = false;
        foreach($data as $row) {
            $this->SetFillColor($fill ? 247 : 255, $fill ? 251 : 255, $fill ? 253 : 255); // alternance
            $this->SetTextColor(34,34,34);
            $this->Cell($w[0],10,$row[0],'LR',0,'C',$fill);
            $this->Cell($w[1],10,$row[1],'LR',0,'C',$fill);
            $this->Cell($w[2],10,$row[2],'LR',0,'C',$fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell(array_sum($w),0,'','T');
        $this->Ln(3);
        // Footer
        $this->SetFont('Arial','I',10);
        $this->SetTextColor(120,120,120);
        $this->Cell(0,8,'Document généré le '.date('d/m/Y à H:i'),0,1,'R');
    }
}
$pdf = new PDF();
$pdf->AddPage();
// Tableau
$header = array('Type', 'Valeur', 'Date');
$data = array(
    array('Son', ($son && isset($son['value_percent']) ? $son['value_percent'].' dB' : 'aucune donnée'), ($son && isset($son['recorded_at']) ? $son['recorded_at'] : '-')),
    array('Lumiere', ($luminosite && isset($luminosite['value_percent']) ? $luminosite['value_percent'].' lux' : 'aucune donnée'), ($luminosite && isset($luminosite['recorded_at']) ? $luminosite['recorded_at'] : '-')),
    array('Gaz (fumee)', ($gaz && isset($gaz['value_percent']) ? $gaz['value_percent'].' %' : 'aucune donnée'), ($gaz && isset($gaz['recorded_at']) ? $gaz['recorded_at'] : '-')),
    array('Temperature', ($temperature && isset($temperature['value_percent']) ? $temperature['value_percent'].' °C' : 'aucune donnée'), ($temperature && isset($temperature['recorded_at']) ? $temperature['recorded_at'] : '-')),
    array('Humidite', ($humidite && isset($humidite['value_percent']) ? $humidite['value_percent'].' %' : 'aucune donnée'), ($humidite && isset($humidite['recorded_at']) ? $humidite['recorded_at'] : '-')),
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
        <div style="background:#f5f8fa;padding:28px 0 28px 0;">
            <div style="max-width:620px;margin:0 auto;background:#fff;border-radius:18px;box-shadow:0 8px 32px rgba(10,107,191,0.10);padding:36px 20px 32px 20px;">
                <div style="font-family:\'Segoe UI\',Arial,sans-serif;text-align:center;color:#0a6bbf;font-size:27px;font-weight:700;letter-spacing:1px;padding-bottom:17px;">
                    Valeur numérique des capteurs
                </div>
                <div style="font-family:\'Segoe UI\',Arial,sans-serif;font-size:15px;color:#222;text-align:center;margin-bottom:24px;">
                    Veuillez trouver en pièce jointe l\'état instantané des capteurs.
                </div>
                '.$html_table.'
                <div style="font-family:\'Segoe UI\',Arial,sans-serif;font-size:15px;color:#222;margin-top:30px;text-align:right;">
                    Cordialement,<br>'.$expediteur.'
                </div>
            </div>
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