<?php
include 'dbconnect.php';

$salonId = $_GET['salon_id'];
error_log("Salon ID: " . $salonId); // Log the salon ID

$staffResult = mysqli_query($con, "SELECT tbl_registration.Reg_id, tbl_registration.F_name, tbl_registration.L_name 
                                    FROM tbl_staff 
                                    JOIN tbl_registration ON tbl_staff.Reg_id = tbl_registration.Reg_id 
                                    WHERE tbl_staff.Salon_id = '$salonId'");

if (!$staffResult) {
    error_log("MySQL error: " . mysqli_error($con)); // Log MySQL errors
    echo "Error: " . mysqli_error($con);
    exit();
}

$options = "<option value=''>--Select--</option>";
while ($staffRow = mysqli_fetch_array($staffResult)) {
    error_log("Fetched staff: " . print_r($staffRow, true)); // Log fetched staff data
    $options .= "<option value='" . $staffRow['Reg_id'] . "'>" . $staffRow['F_name'] . " " . $staffRow['L_name'] . "</option>";
}
if ($options == "<option value=''>--Select--</option>") {
    echo "<option value=''>No staff found</option>";
} else {
    echo $options;
}
?>
