<?php include '../assets/listar.php'; ?>
<?php

$string = "";
$posible = "1234567890abcdefghijklmnopqrstuvwxyz_";
$i = 0;
while($i < 20){
    $char = substr($posible, mt_rand(0, strlen($posible)-1),1);
    $string .= $char;
    $i++;
}
$email = $_POST['email'];

$sql = "SELECT correo FROM usuarios where correo = '$email' and status = 'Enabled'";
$query = $connect->prepare($sql);
$query->execute();

$asunto = "Recuperar contraseña de Usuario";

$mensaje = "Gracias por crear sus usuario en Nuestro Sistema, para poder acceder, debe activar su "
        . "usuario haciendo clic en el siguiente enlace:"."\n"
       . "<a href='https://ibvirtual3.iboutplacement.com/login/Recupera.php?link=$string' target='_blank'> https://ibvirtual3.iboutplacement.com/login/Recupera.php?link=$string</a>"."\n"
       // http://localhost:81/ibvirtual2/login/Recupera.php?link=$string
        . "usted se ha registrado con : "." ".$email;


$cabeceras .= 'From: CORPORACIÓN IBGROUP <cm.outplacement.coaching@corpibgroup.com>' . "\r\n". 
              'Reply-To: cm.outplacement.coaching@corpibgroup.com' . "\r\n" .
              'X-Mailer: PHP/' . phpversion();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
              
require '../Phpmailer/Exception.php';
require '../Phpmailer/PHPMailer.php';
require '../Phpmailer/SMTP.php';


if($query->rowCount() > 0){

    $sql1 = "UPDATE usuarios SET token='$string' WHERE correo='$email'";
    $query1 = $connect->prepare($sql1);
    $query1->execute();

$mail = new PHPMailer(true);
try {
    
    $mail->SMTPDebug = 0;                      
    $mail->isSMTP();                                            
    $mail->Host       = 'smtp.gmail.com';                       //CAMBIAR EL PROTOCOLO SI ES NECESARIO 
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = 'masterps3098@gmail.com';      //CAMBIAR CON CORREO CORPORATIVO               
    $mail->Password   = 'masterps309825';                            //CAMBIAR LA CLAVE DEL CORREO   
    $mail->SMTPSecure = 'tls';         
    $mail->Port       = 587;                                   

    
    $mail->setFrom('masterps3098@gmail.com', 'IB GROUP');   //CAMBIAR CON CORREO CORPORATIVO 
    $mail->addAddress($email);     

    $titulo = utf8_decode('Reestablecer Contraseña');

    $mail->isHTML(true);                                  
    $mail->Subject = $titulo;
    $mail->Body    =  $mensaje.'</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();

    echo "<script>alert('Se enviará un link a ".$email." para que modifique su contraseña, verifique su correo para acceder.');location.href ='https://ibvirtual3.iboutplacement.com/assets/login.php';</script>";                                                                                                                                         //http://localhost:81/ibvirtual2/assets/login.php
// CAMBIAR LINK
} catch (Exception $e) {
    echo "Mensaje de error: {$mail->ErrorInfo}";
}
}else{
    echo "<script>alert('Su correo no está registrado o actualmente se encuentra desabilitado');location.href ='javascript:history.back()';</script>";
}
?>
