<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function sendOtpEmail($toEmail, $otp) {
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 2; // Debugging
        $mail->Debugoutput = 'html';

        $mail->isSMTP();
        $mail->Host = 'smtp.elasticemail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ryuzaki@web.pribadi';
        $mail->Password = '30108F2843F59022C27E6785719004A39E1B097B35134F3DA5062EF50B1DF709AFA4890275A4516F1A350B9F30D42ABB';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 2525;

        $mail->setFrom('ryuzaki@web.pribadi', 'Nama Aplikasi');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Kode OTP Verifikasi';
        $mail->Body = "Halo, berikut adalah kode OTP Anda: <b>$otp</b>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Gagal mengirim email: {$mail->ErrorInfo}"; // Tampilkan kesalahan
        return false;
    }
}
?>