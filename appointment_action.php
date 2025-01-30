<?php
include 'dbconnect.php';
date_default_timezone_set('Asia/Kolkata');
session_start();

// Ensure that the Reg_id is set
if (!isset($_SESSION['Reg_id'])) {
    echo "Error: Session not set for Reg_id!";
    exit;
}

$ii = 0;
$f = 0;

$service = $_POST['service'];
$sql = mysqli_query($con, "SELECT * FROM tbl_category WHERE ser_cat_name='$service'");
$row = mysqli_fetch_array($sql);
$b = $row['ser_cat_id'];
$_SESSION['ser_cat_id'] = $b;
$c = $row['Cat_id'];
$date = $_POST['txt_Appoinment_Date'];
$time = $_POST['time'];
$a = $_SESSION['Reg_id'];  // Use Reg_id from session
$s = $_POST['txt_staff'];

$at = "10:00";
$bt = "16:30";
$ct = date("H:i");
$cd = date("Y-m-d");

if (($time >= $at) && ($time <= $bt)) {
    if ((($date == $cd) && ($time > $ct)) || ($date != $cd)) {
        $resu = mysqli_query($con, "SELECT * FROM tbl_appointment WHERE Staff_id='$s' AND Date='$date' AND Status!='5'");
        while ($rw = mysqli_fetch_array($resu)) {
            $f = 1;
            $ti = $time;
            $a1 = strtotime("-15 minutes", strtotime($ti));
            $a2 = date("H:i", $a1);
            $b1 = strtotime("+15 minutes", strtotime($ti));
            $b2 = date("H:i", $b1);

            $sel1 = "SELECT * FROM tbl_appointment WHERE Time BETWEEN '$a2' AND '$b2'";
            $qry1 = mysqli_query($con, $sel1);
            $num = mysqli_num_rows($qry1);

            if ($num > 0) {
                echo ("<script language='javascript'>window.alert('Already booked in this time..Try another time..');
                    window.location.href='appointment_add.php?uid=$_SESSION[ser_cat_id]';</script>");
                exit;
            } else {
                $sql = mysqli_query($con, "SELECT * FROM tbl_appointment WHERE Date='$date' AND Staff_id='$s'");
                while ($row = mysqli_fetch_array($sql)) {
                    $ii++;
                }
                if ($ii > 2) {
                    echo ("<script language='javascript'>window.alert('Appointment is full for the date:.$date');
                        window.location.href='appointment_add.php?uid=$_SESSION[ser_cat_id]';</script>");
                    exit;
                } else {
                    $insertQuery = "INSERT INTO tbl_appointment(Reg_no, ser_cat_id, Cat_id, Date, Time, Staff_id, Status)
                        VALUES ('$a', '$b', '$c', '$date', '$time', '$s', 1)";
                    $sql = mysqli_query($con, $insertQuery);

                    if ($sql) {
                        echo ("<script language='javascript'>window.alert('Service Booked!!');
                            window.location.href='appointment_status.php';</script>");
                        exit;
                    } else {
                        echo ("<script language='javascript'>window.alert('Booking Failed. Try Again!!');
                            window.location.href='appointment_status.php';</script>");
                        echo "Error: " . mysqli_error($con);  // Debugging
                        exit;
                    }
                }
            }
        }

        if ($f == 0) {
            $insertQuery = "INSERT INTO tbl_appointment(Reg_no, ser_cat_id, Cat_id, Date, Time, Staff_id, Status)
                VALUES ('$a', '$b', '$c', '$date', '$time', '$s', 1)";
            $sql = mysqli_query($con, $insertQuery);

            if ($sql) {
                echo ("<script language='javascript'>window.alert('Service Booked!!');
                    window.location.href='appointment_status.php';</script>");
                exit;
            } else {
                echo ("<script language='javascript'>window.alert('Booking Failed. Try Again!!');
                    window.location.href='appointment_status.php';</script>");
                echo "Error: " . mysqli_error($con);  // Debugging
                exit;
            }
        }
    } else {
        echo ("<script language='javascript'>window.alert('Sorry, the time you selected already passed!!');
            window.location.href='appointment_add.php?uid=$_SESSION[ser_cat_id]';</script>");
        exit;
    }
} else {
    echo ("<script language='javascript'>window.alert('Sorry, appointment time is from 10:00am - 4:30pm!!');
        window.location.href='appointment_add.php?uid=$_SESSION[ser_cat_id]';</script>");
    exit;
}
?>
