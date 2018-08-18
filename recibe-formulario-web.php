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
    $PDF->Output('D','doc.pdf');
    }
?>
