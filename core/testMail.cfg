<?php
  require_once "../phpmailer/class.phpmailer.php";
  require_once "./mail.cfg";
  require_once "./admin.php";
  set_time_limit(310);
  $mail = new phpmailer();
  $mail->PluginDir = PLUGINDIR;
  $mail->Mailer = MAILER;
  $mail->Host = HOST;
  $mail->SMTPAuth = SMTPAuth;
  $mail->Username = USERNAME; 
  $mail->Password = PASSWORD;
  $mail->From = FROM;
  $mail->FromName = FROMNAME;
  $mail->Timeout=TIMEOUT;
  $mail->SMTPSecure = SMTPSECURE;
  $mail->Port=PORT;
  
  $mail->SetLanguage("en", '../phpmailer/language/');
  
        $mail->AddAddress("jorge.siles@gmail.com");
        $mail->Subject ="";
        $mail->Body =" test de envio ";
        $exito = $mail->Send();
        $intentos=1; 
        while ((!$exito) && ($intentos < 3)) {
              sleep(5);
              $exito = $mail->Send();
              $intentos=$intentos+1;	
         }
         if(!$exito)
         {
             echo 'falla ';
         }
         else
         {
             echo 'Ok';
         } 