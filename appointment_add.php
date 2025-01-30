<?php
include 'customer_header.php';
include 'dbconnect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$uname = $_SESSION['username'];
$sel = "SELECT Reg_id, F_name FROM tbl_registration WHERE Email='$uname'";
$qry = mysqli_query($con, $sel);
$ans = mysqli_fetch_array($qry);
if (!$ans) {
    echo "Error: Customer details not found.";
    exit();
}
$custid = $ans['Reg_id'];
$custname = $ans['F_name'];
?>
<html>
<head>
    <style>
        table {
            width: 70%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        th {
            background-color: #6ea522;
            color: white;
        }

        h1 {
            text-align: center;
            color: green;
        }

        .center {
            text-align: center;
        }
    </style>
    <h1>SERVICE BOOKING</h1>
    <script type="text/javascript">
        function validate() {
            var dvar1 = document.getElementById("txt_Booking_date");
            if (dvar1.value == "") {
                alert("Enter Booking date...");
                dvar1.focus();
                return false;
            }
            var dvar2 = document.getElementById("slb_Customer_id");
            if (dvar2.value == "") {
                alert("Select Customer Name ...");
                dvar2.focus();
                return false;
            }
            var dvar3 = document.getElementById("slb_Package_id");
            if (dvar3.value == "") {
                alert("Select Package Name ...");
                dvar3.focus();
                return false;
            }
            var dvar4 = document.getElementById("txt_Amount");
            if (dvar4.value == "") {
                alert("Enter Amount...");
                dvar4.focus();
                return false;
            }
        }

        function getStaff(salonId) {
            if (salonId == "") {
                document.getElementById("txt_staff").innerHTML = "<option value=''>--Select--</option>";
                return;
            } 
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    document.getElementById("txt_staff").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "get_staff.php?salon_id=" + salonId, true);
            xhttp.send();
        }
    </script>
</head>
<body>
    <form name="Package_booking_Add.php" action="appointment_action.php" method="post" onSubmit="return validate()">
        <table>
            <tr>
                <td class="center">Name</td>
                <td><input type="text" name="slb_Customer_id" id="slb_Customer_id" value="<?php echo $custname; ?>" readonly="readonly"></td>
            </tr>
            <tr>
                <td class="center"><b>Date</b></td>
                <td><input type="text" name="txt_Booking_date" id="txt_Booking_date" value="<?php echo date("Y/m/d") ?>" readonly="readonly"></td>
            </tr>
            <tr>
                <td class="center"><b>Salon</b></td>
                <td>
                    <select name="txt_salon" id="txt_salon" onchange="getStaff(this.value)" required>
                        <option value="">--Select--</option>
                        <?php
                        $salonResult = mysqli_query($con, "SELECT * FROM tbl_salon WHERE status='2'");
                        while ($salonRow = mysqli_fetch_array($salonResult)) {
                            echo "<option value='" . $salonRow['id'] . "'>" . $salonRow['salon_name'] . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php
            $kid = $_GET['uid'];
            $var = "SELECT * FROM tbl_category WHERE ser_cat_id='$kid'";
            $result = mysqli_query($con, $var);
            while ($row1 = mysqli_fetch_array($result)) {
                $x = $row1['Cat_id'];
                $_SESSION['Cat_id'] = $x;
            ?>
            <tr>
                <td class="center"><b>Service</b></td>
                <td><input type="text" name="service" id="service" value="<?php echo $row1['ser_cat_name']; ?>" readonly="readonly"></td>
            </tr>
            <tr>
                <td class="center"><b>Amount</b></td>
                <td><input type="text" name="txt_Amount" id="txt_Amount" value="<?php echo $row1['ser_cat_price']; ?>" readonly="readonly"></td>
            </tr>
            <tr>
                <td class="center"><b>Appointment Date</b></td>
                <td><input type="date" name="txt_Appoinment_Date" id="txt_Appoinment_Date" value="<?php echo date("Y-m-d") ?>" min="<?php echo date("Y-m-d") ?>" required></td>
            </tr>
            <tr>
                <td class="center"><b>Appointment Time</b></td>
                <td><input type="time" name="time" id="time" required /></td>
            </tr>
            <tr>
                <td class="center"><b>Staff Preferred</b></td>
                <td>
                    <select name="txt_staff" id="txt_staff" required>
                        <option value="">--Select--</option>
                        <!-- Staff options will be populated based on the selected salon -->
                    </select>
                </td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <td></td>
                <td><input type="submit" name="cmd" id="cmd" value="Book" class="center"></td>
            </tr>
        </table>
    </form>
</body>
</html>
<?php
include "footer.php";
?>
