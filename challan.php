<?php
require "./library/PDF.php";
require "./library/QR_master.php";
require_once "config.php";

if(!isset($_SESSION['username']))
{
    header("location: login.php");
    exit;
}

$pdf = new PDF('L','mm','A4');

$pdf->AddPage();

$x=7;


$JOB_ID = $_GET['ID'];
$CNIC_NO =   $_SESSION['username'];
$date = date('Y-m-d');

$sql = "SELECT * FROM user_registration where CNIC_NO=$CNIC_NO";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$USER_ID = $row['USER_ID'];


$sql = "SELECT * FROM applications where USER_ID=$USER_ID AND JOB_ID=$JOB_ID";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if(count($row)==0){
	$query = "INSERT INTO applications (USER_ID, JOB_ID, APPLICATION_DATE,STATUS) VALUES ('$USER_ID', '$JOB_ID', '$date','1')";
	if (mysqli_query($conn, $query)) {
		
		$APP_ID = mysqli_insert_id($conn);
		
		$CHALLAN_AMOUNT = 2600;
		$q = "INSERT INTO form_challan (APPLICATION_ID, CHALLAN_AMOUNT, USER_ID) VALUES ('$APP_ID', '$CHALLAN_AMOUNT', '$USER_ID')";
		
		if(mysqli_query($conn, $q)){
			header("location: Dashboard.php");
			exit;
		}else{
			echo "Error: " . $query . "<br>" . mysqli_error($conn);
		}
			
	} else {
		echo "Error: " . $query . "<br>" . mysqli_error($conn);
	}	
}

$sql = "SELECT user_registration.*, applications.*, form_challan.*,job_announcements.* FROM user_registration JOIN applications ON user_registration.USER_ID = applications.USER_ID JOIN form_challan ON applications.APPLICATION_ID = form_challan.APPLICATION_ID JOIN job_announcements ON (job_announcements.JOB_ID = applications.JOB_ID) WHERE user_registration.USER_ID = $USER_ID AND applications.JOB_ID = $JOB_ID";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);


myFunction("BANK'S COPY",$x,$pdf,$row);
line($x,$pdf);
$x=75;
myFunction("ACCOUNT'S COPY",$x,$pdf,$row);
line($x,$pdf);
$x=145;
myFunction("COMPANY COPY",$x,$pdf,$row);
line($x,$pdf);
$x=215;
myFunction("ASPIRANT'S COPY",$x,$pdf,$row);

function line($x,$pdf){
    $pdf->Line($x+70,5,$x+70,213);
}

//$challan_no = $row['CHALLAN_NO'];

$pdf->Output("challan.pdf",'I');

function myFunction($copy, $x,$pdf,$record)
{

    $stdName = $record['FULL_NAME'];
    $surName = $record['SURNAME'];
    $application_id = $record['APPLICATION_ID'];
    $cnic_no = $record['CNIC_NO'];

    $JOB_TITLE = $record['JOB_TITLE'];

    $total_amount = $record['CHALLAN_AMOUNT'];

    // $in_words = $record['IN_WORDS'];
    $in_words =  convert_number_to_words($total_amount);
    //$in_words = "ASD";
    $in_words = ucwords(strtoupper($in_words)).' ONLY';

    $date = date('d-m-Y', strtotime("+7 days"));
	$valid_upto = $date;

    $account_no = "10023041526444";
    $challan_no = $record['FORM_CHALLAN_ID'];
    
    

    $pdf->SetFont('Arial','B',20);

    $pdf->Image('assets/img/logo.jpg',5+$x,4,18);
    $pdf->Image('assets/img/hbl.png',25+$x,10,18);

    $pdf->SetFont('Arial','B',7);
    $height=25;
    $pdf->text(20+$x,$height, $copy );
    $pdf->Ln();

    $pdf->SetFont('Arial','B',8);


    $height=$height+6;
    $pdf->SetFont('Arial','',8);
    $pdf->text($x+7,$height,"Please receive and credit Our Account");
    $height = $height+5;
    $pdf->SetFont('Arial','B',8);
    $pdf->text($x+9,$height,"JOB PORTAL ACCOUNT NO.");
    $pdf->SetFont('Arial','B',11);
    $height = $height+5;
    $pdf->text($x+15,$height,"CMD. $account_no");
//   $height = $height+5;
//     $pdf->SetFont('Arial','',8);
//     $pdf->text($x+43,$height,"DATE: $current_date");
    $height = $height+2;
    $pdf->SetTextColor(255,0,0);
    $pdf->SetFont('Arial','B',11);
    $pdf->SetXY($x + 7, $height);
    //$pdf->text($x+13,$height,"CHALLAN NO: ");
    $pdf->Cell(30,7,"CHALLAN NO",1,"","C",false);
    $height = $height+ 6;
    $pdf->SetTextColor(255,0,0);
    $pdf->SetFont('Arial','B',11);
    //  $pdf->text($x+13,$height,$challan_no);
    //$pdf->SetXY($x + 13, $height);
    $pdf->Cell(30,7,$challan_no,1,"","C",false);
    $pdf->SetFont('Arial','B',9);
    $height=$height+7;
    $pdf->SetTextColor(255,0,0);
    $pdf->text($x+7,$height,"This challan is valid upto: $valid_upto");

    $pdf->SetFont('Arial','B',11);
    $height =$height+ 3;
    

    $pdf->setTextColor(60,60,60);

    $height =$height+5;
    $pdf->SetFont('Arial','',8);
    $pdf->text($x+5,$height,"NAME:");
    $pdf->SetFont('Arial','B',9);
    $height =$height+4;
    $pdf->text($x+5,$height,strtoupper($stdName));

    $height =$height+5;
    $pdf->SetFont('Arial','',8);
    $pdf->text($x+5,$height,"SURNAME:");
    $pdf->SetFont('Arial','B',9);
    $height =$height+4;
    $pdf->text($x+5,$height,strtoupper($surName));

    $height =$height+5;
    $pdf->SetFont('Arial','',8);
    $pdf->text($x+5,$height,"CNIC NO:");
    $pdf->SetFont('Arial','B',9);
    $height =$height+4;
    $pdf->text($x+5,$height,$cnic_no);

    
    $height =$height+5;
    $pdf->SetFont('Arial','',8);
    $pdf->text($x+5,$height,"APPLIED FOR:");
    $height =$height+4;
    $pdf->SetFont('Arial','B',9);
    $pdf->text($x+5,$height,strtoupper($JOB_TITLE));
    $height =$height+8;
    $pdf->SetFont('Arial','',8);
    $pdf->text($x+5,$height,"");


    $pdf->setTextColor(0,0,0);

    $pdf->ln(0);
    $height=$height+4;
    $pdf->SetXY($x + 3, $height);
    $pdf->SetFont('Times','B',10);
    $pdf->Cell(40,6,"Purpose of Payment",1,"","C",false);
    $pdf->Cell(25,6,"Amount (Rs.)",1,"","C",false);

    $height = $height+6;
    $pdf->SetXY($x + 3,$height );
    $pdf->SetFont('Times','B',10);
    $x1 = $pdf->getX();
    $y = $pdf->getY();
    $pdf->MultiCell(40,5,"APPLIED FOR (".$JOB_TITLE.")",1,"J");
    $pdf->SetXY($x1+40, $y);
    $pdf->Cell(25,10,"Rs. ".number_format($total_amount,2),1,"","R",false);
    $height = $height+15;
    $pdf->SetXY($x + 3, $height);
    $pdf->SetFont('Times','B',9);

//    $pdf->TableCell(65,4,"Amount (in words): $in_words",0,'L',0);

    $pdf->MultiCell(65,4,"Amount (in words): $in_words",0,"L",false);

    $pdf->SetXY($x + 4, 157);
    $pdf->SetFont('ARIAL','',8);

    $pdf->MultiCell(64,4,"                      IMPORTANT NOTE
         This paid amount (Rs: ".number_format($total_amount,2)."/=) is non-transferable and non-refundable. ",1,"L",false);

    $data = $application_id. "~". $challan_no . "~".$cnic_no."~" . $total_amount . "~" . $valid_upto . "~" . $account_no;
    //$result=str_pad($data, 10, "0", STR_PAD_LEFT);


    $s="                                                                                ".$data;

    $result=substr($s, strlen($s) - 80, strlen($s));

//    $pdf->text($x+5,190,"MANAGER");
//    $pdf->text($x+52,190,"CASHIER");

    $pdf->setTextColor(0,0,0);

    $pdf->SetFont('Arial','',4);
    $pdf->text($x+5,199,$data);
    $pdf->SetFont('Times','',6);
    $pdf->text($x+5,203,"Powered by: Students of Computer Science, University of Sindh Jamshoro");

    QRcode::png("$result","qr_images/".$challan_no.".png", 'QR_ECLEVEL_L', 3, 2);
    $path="qr_images/".$challan_no.".png";
    $pdf->Image($path,44+$x,6,18);
}



function convert_number_to_words($number) {

    $hyphen      = ' ';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';

    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }
    $number = (int) $number;
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}//method
?>