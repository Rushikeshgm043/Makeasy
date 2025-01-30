<?php
include 'dbconnect.php';
include 'customer_header.php';

// Ensure the session has Reg_id
if (!isset($_SESSION['Reg_id'])) {
    echo "Error: Session not set for Reg_id!";
    exit;
}

echo "<h1><font color='green'><center>APPOINTMENT STATUS</font></h1><br>";

$res1 = mysqli_query($con, "SELECT * FROM tbl_appointment WHERE Reg_no='$_SESSION[Reg_id]' and Status!='2' and Status!='5'");
if (!$res1) {
    echo "Error: " . mysqli_error($con);  // Debugging
    exit;
}

if (mysqli_num_rows($res1) == 0) {
    echo "<br><br><center><font color=red size=3>No Services Booked !!!</font></center>";
} else {
    ?>
    <table border=2 width=70%>
        <tr>
            <th>SL.NO</th>
            <th>SERVICE</th>
            <th>DATE</th>
            <th>TIME</th>
            <th>STAFF PREFERRED</th>
            <th>STATUS</th>
            <th>DELETE</th>
        </tr>
    <?php

    $res = mysqli_query($con, "SELECT * FROM tbl_appointment WHERE Reg_no='$_SESSION[Reg_id]' and Status!='5' and Status!='2'");
    if (!$res) {
        echo "Error: " . mysqli_error($con);  // Debugging
        exit;
    }

    $i = 1;
    while ($row = mysqli_fetch_array($res)) {
        $a = $row['ser_cat_id'];
        $r = $row['Staff_id'];

        $res1 = mysqli_query($con, "SELECT * FROM tbl_category WHERE ser_cat_id='$a'");
        $row1 = mysqli_fetch_array($res1);

        $res2 = mysqli_query($con, "SELECT * FROM tbl_registration WHERE Reg_id='$r'");
        $row2 = mysqli_fetch_array($res2);

        ?>
        <tr>
            <td><center><?php echo $i; ?></td>
            <td><center><?php echo $row1['ser_cat_name']; ?></td>
            <td><center><?php echo $row['Date']; ?></td>
            <td><center><?php echo $row['Time']; ?></td>
            <td><center><?php echo $row2['F_name'] . " " . $row2['L_name']; ?></center></td> <!-- Display staff name -->
            <td><center><?php echo $row['Status']; ?></td>
            <td><center>
                <a href="appointment_delete.php?uid=<?php echo $row['App_id']; ?>" onclick="return confirm('Are you sure??')">
                    <img src="images/DeleteRed.png" width="24px">
                </a></center>
            </td>
        </tr>
        <?php
        $i++;
    }
    ?>
    </table>
    <?php
}
?>

<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    th {
        background-color: #6ea522;
        color: white;
    }
</style>
