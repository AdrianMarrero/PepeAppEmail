<?php
require 'PHPMailer/PHPMailerAutoload.php';
require('Pdf/fpdf.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $data = json_decode(file_get_contents("php://input"));

    $cliente = $data->cliente;
    $tipoDeTrabajo = $data->tipoDeTrabajo;
    $fecha = $data->fecha;
    $firma = $data->firma;
    $firma2 = $data->firma2;
    $firmante = $data->firmante;
    $horaEntrada = $data->horaEntrada;
    $horaSalida = $data->horaSalida;
    $lugar = $data->lugar; 
    $mailCliente = $data->mailCliente; 
    $numeroDeParte = $data->numeroDeParte; 
    $totalHoras = $data->totalHoras;
    $trabajo_realizado = $data->trabajo_realizado;
    $checkEmail = $data->checkEmail;


    $dataURI= $firma;

    $img = explode(',',$dataURI,2);
    $dataURI2 = $firma2;
    $img2 = explode(',',$dataURI2,2);
    $pic = 'data://text/plain;base64,'.$img[1];
    $pic2 = 'data://text/plain;base64,'.$img2[1];
    $type = explode("/", explode(':', substr($dataURI, 0, strpos($dataURI, ';')))[1])[1]; // get the image type
    //if ($type=="png"||$type=="jpeg"||$type=="gif") return array($pic, $type);
    $type2 = explode("/", explode(':', substr($dataURI2, 0, strpos($dataURI2, ';')))[1])[1];

    $PDF = new FPDF('P','pt','A4');
    $PDF->SetMargins('50','50');
    //$PDF->SetAutoPageBreak(true,'10');
    $PDF->AddPage();
    $PDF->SetFont('Arial','B',12);
    $PDF->Cell(0,15,'Jose Luis Marrero Marrero',0,1,'L');
    $PDF->SetFont('Arial','',10);
    $PDF->Cell(0,15,'ELECTRICIDAD EN GENERAL',0,1,'L'); 
    $PDF->Cell(0,15,'Ins Aut PH7973',0,1,'L'); 
    $PDF->Cell(0,15,utf8_decode('C/.Párroco José Suárez Romero 17'),0,1,'L');
    $PDF->SetFont('Arial','B',12);
    $PDF->Cell(0,0,'Parte de Trabajo',0,1,'R');
    $PDF->SetFont('Arial','',10);
    $PDF->Cell(0,15,'Tlf: 620.288.115',0,1,'L');
    $PDF->Cell(0,0,utf8_decode('Nº '.$numeroDeParte),0,1,'R');
    $PDF->Cell(0,15,'Firgas - Gran Canaria',0,1,'L');
    $PDF->Cell(0,0,utf8_decode('Fecha: '.$fecha),0,1,'R');
    $PDF->Ln('30');
    $PDF->SetFont('Arial','B',11);
    $PDF->Cell(50,10,'Cliente:',0,0);
    $PDF->SetFont('Arial','',11);
    $PDF->Cell(0,10,utf8_decode($cliente),0,1);
    $PDF->Ln('30');
    $PDF->SetFont('Arial','B',14);
    $PDF->Cell(0,15,'TRABAJO REALIZADO',0,1,'C');
    $PDF->Ln('30');
    $PDF->SetFont('Arial','',11);
    $PDF->MultiCell(0,15,utf8_decode($trabajo_realizado));

    if(($tipoDeTrabajo == 'Obra') || ($tipoDeTrabajo == 'Avería')){
        $PDF->Ln('30');
        $PDF->SetX(50);
        $PDF->Cell(100,30,'Hora de Entrada',0,0);
        $PDF->SetFont('Arial','B',12);
        $PDF->Cell(70,30,$horaEntrada,1,0, 'C');
        $PDF->SetX(350);
        $PDF->SetFont('Arial','',11);
        $PDF->Cell(100,30,'Hora de Salida:',0,0);
        $PDF->SetFont('Arial','B',12);
        $PDF->Cell(70,30,$horaSalida,1,1, 'C');
        $PDF->Ln('20');
        $PDF->SetFont('Arial','B',12);
        $PDF->Cell(100,30,'Total horas:',0,0);
        $PDF->Cell(100,50,$totalHoras,1,1, 'C');
    }

    $PDF->Ln('10');
    $PDF->SetXY(50, 400);
    $PDF->Cell(200,15,'Firma Cliente',1,0);
    $PDF->SetX(50);
    $PDF->Cell(200,50,utf8_decode($firmante),1,0);
    $PDF->SetX(50);
    $PDF->Image($pic,null,500,150,0,$type);


    $PDF->SetXY(300, 400);
    $PDF->Cell(200,15,'Firma Empresa',1,0);
    $PDF->SetX(300);
    $PDF->Cell(200,50,'Jose Luis Marrero Marrero',1,0);
    $PDF->SetX(300);
    $PDF->Image($pic2,null,500,150,0,$type2);

    //$PDF->Output();
    $doc = $PDF->Output('', 'S');

    $mail = new PHPMailer();                              // Passing `true` enables exceptions
    try {
        //Server settings
        //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
        //$mail->isSMTP();                                      // Set mailer to use SMTP
        //$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        //$mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'adrianmarmar@gmail.com';                 // SMTP username
        $mail->Password = 'marrero22';                           // SMTP password
        //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        //$mail->Port = 25;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('joseluismarrerofirgas@gmail.com', 'Jose Luis Marrero Marrero');
        $mail->addAddress('joseluismarrerofirgas@gmail.com', 'Cliente');     // Add a recipient
        if($checkEmail == true){
            $mail->addAddress('teresa@desnudos.es'); 
        }
        $mail->addAddress('adrianmarmar@gmail.com');               // Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('adrianmarmar@gmail.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        if($checkEmail == false){
            $TextoDeParte = '<div>
                            <div><em>Jose Luis Marrero</em></div>
                            <div><em>Instalaciones electricas en general</em></div>
                            <div><em>Ins Aut PH7973</em></div>
                            <div><em>C/Parroco Jose Suarez 17</em></div>
                            <div><em>Firgas CP:35430</em></div>
                            <div><em>Tf: 620 28 81 15</em></div>
                            <h2>Se adjunta PDF con parte de trabajo</h2>
                            <h4>No se ha enviado el parte a Desnudos</h4>
                            <p><em>PRIVADO Y CONFIDENCIAL Este mensaje va dirigido a la(s) persona(s) indicada(s). Puede contener información confidencial de carácter legal o personal de "Jose Luis Marrero". La transmisión errónea del presente mensaje en ningún momento supone renuncia a su confidencialidad. Si el lector del mensaje no es el destinatario indicado, o el encargado de su entrega a dicha persona, por favor, notifíquelo inmediatamente por teléfono y remita el mensaje original a la dirección de correo electrónico indicada. Cualquier copia o distribución de esta comunicación queda estrictamente prohibida</em></p>
                            </div>';
        }else{
            $TextoDeParte = '<div>
                <div><em>Jose Luis Marrero</em></div>
                <div><em>Instalaciones electricas en general</em></div>
                <div><em>Ins Aut PH7973</em></div>
                <div><em>C/Parroco Jose Suarez 17</em></div>
                <div><em>Firgas CP:35430</em></div>
                <div><em>Tf: 620 28 81 15</em></div>
                <h2>Se adjunta PDF con parte de trabajo</h2>
                <p><em>PRIVADO Y CONFIDENCIAL Este mensaje va dirigido a la(s) persona(s) indicada(s). Puede contener información confidencial de carácter legal o personal de "Jose Luis Marrero". La transmisión errónea del presente mensaje en ningún momento supone renuncia a su confidencialidad. Si el lector del mensaje no es el destinatario indicado, o el encargado de su entrega a dicha persona, por favor, notifíquelo inmediatamente por teléfono y remita el mensaje original a la dirección de correo electrónico indicada. Cualquier copia o distribución de esta comunicación queda estrictamente prohibida</em></p>
                </div>';
        }                    





        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Parte de trabajo';
        $mail->Body    = $TextoDeParte;
        $mail->AltBody = 'Se adjunta PDF con parte de trabajo';
        $mail->AddStringAttachment($doc, 'doc.pdf', 'base64', 'application/pdf');
        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}

