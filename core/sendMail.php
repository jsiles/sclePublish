<?php
  require_once "../phpmailer/class.phpmailer.php";
  require_once "./mail.cfg";
  require_once "./admin.php";
  set_time_limit(600);
  
  $sSQL="select * from mdl_notificacion_envio where noe_status=0 and noe_retry<=2 and noe_email!=''";
  $nroReg=$db->numrows($sSQL);
  echo "Inicio de envio";
  if($nroReg>0){
    $db->query($sSQL);
    while($noe=$db->next_record())
    {
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

	  $mail->SetLanguage("es", '../phpmailer/language/');

          if(strpos($noe["noe_email"], ";")!==false){
           
              $emailsArray = explode(";",$noe["noe_email"]);
              foreach ($emailsArray as $emails)
              {
                  $mail->AddAddress($emails);
              }
          }else{
              $mail->AddAddress($noe["noe_email"]);
          }
	  $mail->Subject =admin::getDbValue("select not_subject from mdl_notificacion_template where not_tip_uid=".$noe["noe_nti_uid"]);
	  $mail->Body =admin::getDbValue("select not_template from mdl_notificacion_template where not_tip_uid=".$noe["noe_nti_uid"]);
            if($noe["noe_nti_uid"]==2){    
              if($noe["noe_attach"]!=""){
                $mail->addAttachment(PATH_ROOT.$noe["noe_attach"]);
              }
            }
          //$mail->AddBCC("ERios@bnb.com.bo","Rios Rios, Enrique");
          //$mail->AddBCC("RCastro@bnb.com.bo", "Castro Lizarraga, Rosario");
	  $mail->CharSet = 'UTF-8';
        $exito = $mail->Send();
        $intentos=1; 
      while ((!$exito) && ($intentos < 3)) {
              sleep(5);
              $exito = $mail->Send();
              $intentos=$intentos+1;	
      }
      if(!$exito)
      {
             admin::getDbValue("update mdl_notificacion_envio set noe_retry=$intentos, noe_response='".$mail->ErrorInfo."', noe_fecha_envio=GETDATE() where noe_uid=".$noe["noe_uid"]);
      }
      else
      {
             admin::getDbValue("update mdl_notificacion_envio set noe_retry=$intentos, noe_status=1, noe_response='OK', noe_fecha_envio=GETDATE() where noe_uid=".$noe["noe_uid"]);	
      } 
    }
  }
  echo "Fin de envio";
  echo "Inicio avisos";
  
   $sSQL="select * from mdl_notificacion_previa where noe_estado=0 and noe_datetime<GETDATE()";
   $nroReg=$db->numrows($sSQL);
   if($nroReg>0){
    $db->query($sSQL);
    while($nop=$db->next_record())
    {
        $sSQL="select inc_cli_uid from mdl_incoterm where inc_sub_uid=".$nop["nop_sub_uid"];
        $nroReg=$db->numrows($sSQL);
        //echo $nroReg;
        if($nroReg>0){
            $db->query($sSQL);
            while($list=$db->next_record())
            {
                $cli_uid=$list["inc_cli_uid"];
                $cli_email=admin::getDbValue("select cli_mainemail from mdl_client where cli_uid=$cli_uid");
                $nti_uid=1;
                $attach =admin::getDbValue("select pro_document from mdl_product where pro_sub_uid=$sub_uid");
                if(strlen($attach)>0) $attach="/docs/subasta/".$attach;
                else $attach="";
                admin::insertMail($cli_uid, $nti_uid, $attach, $cli_email, $sub_uid);
                $cli_email=admin::getDbValue("select cli_commercialemail from mdl_client where cli_uid=$cli_uid");
                if(strlen($cli_email)>0){
                    admin::insertMail($cli_uid, $nti_uid, $attach, $cli_email, $sub_uid);
                }
                 admin::getDbValue("update mdl_notificacion_previa set noe_estado=1 where nop_uid=".$nop["nop_uid"]);
            }
        }

    }
   }
  echo "Fin avisos";
?>