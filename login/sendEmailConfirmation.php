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

    $asunto = "Link de activación de Usuario en el Sistema.";

    $mensaje = "Gracias por crear sus usuario en Nuestro Sistema, para poder acceder, debe activar su "
            . "usuario haciendo clic en el siguiente enlace:"."\n"
           ."<a href='https://ibvirtual3.iboutplacement.com/link_activation.php?link=$string' target='_blank'>https://ibvirtual3.iboutplacement.com/link_activation.php?link=$string</a>"."\n"
           // . "http://localhost/pruebas/online/link_activation.php?link=$string"."\n"
           //CAMBIAR LINK
            . "usted se ha registrado con : "." ".$email;


    // Para enviar un correo HTML mail, la cabecera Content-type debe fijarse
    // Cabeceras adicionales
    $cabeceras .= 'From: CORPORACIÓN IBGROUP <cm.outplacement.coaching@corpibgroup.com>' . "\r\n".
                  'Reply-To: cm.outplacement.coaching@corpibgroup.com' . "\r\n" .
                  'X-Mailer: PHP/' . phpversion();

include("../PhpMailer/PHPMailer.php");
include("../Phpmailer/SMTP.php");
include("../Phpmailer/Exception.php");

$sql3 = "SELECT correo FROM ibvirtuallicencias where correo = '$email' and status = 'Enabled'";
$query3 = $connect->prepare($sql3);
$query3->execute();

if($query3->rowCount() > 0){
    echo "<script>alert('Usted actualmente ya cuenta con el servicio activo');location.href ='https://ibvirtual3.iboutplacement.com/assets/login.php';</script>"; //CAMBIAR LINK 

}else{

$sql = "SELECT correo FROM ibvirtuallicencias where correo = '$email' and status = 'Disabled'";
$query = $connect->prepare($sql);
$query->execute();

if($query->rowCount() > 0){

    $sql1 = "SELECT TIMEDIFF(CURRENT_TIMESTAMP,fechainscripcion) as dias,correo FROM ibvirtuallicencias WHERE correo = '$email'";
    $query1 = $connect->prepare($sql1);
    $query1->execute();
    $data=$query1->fetch(PDO::FETCH_OBJ);
    $fecha = $data->dias;

if($fecha < 5){

    $sql2 = "UPDATE ibvirtuallicencias SET codigo='$string' WHERE correo='$email'";
    $query2 = $connect->prepare($sql2);
    $query2->execute();

$mail = new  PHPMailer\PHPMailer\PHPMailer();

try {
//$mail->MsgHTML($message);

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

$titulo = utf8_decode($asunto);
                                
$mail->Subject = $titulo;
$mail->Body    =  $mensaje.'</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

$mail->send();

    echo "<script>alert('Formulario enviado exitosamente, verifique su correo para acceder.');location.href ='https://ibvirtual3.iboutplacement.com/assets/login.php';</script>"; 

} catch (Exception $e) {
    echo "Mensaje de error: {$mail->ErrorInfo}";
}                
}else{
    echo "<script>alert('Su periodo de prueba culmino. Lo invitamos a que se suscriba para disfrutar de los beneficios.');location.href ='https://ibvirtual3.iboutplacement.com/assets/login.php';</script>";
}
}else{
    echo "<script>alert('Registrese para la prueba gratis');location.href ='https://ibvirtual3.iboutplacement.com/assets/login.php';</script>";
}
}
?>