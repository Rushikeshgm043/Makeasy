<?php
include 'admin_header.php';
include 'dbconnect.php';
?>
<h1><font color="green">SALONS</h1></font>
<?php
$res1 = mysqli_query($con, "SELECT * FROM `tbl_salon` WHERE `status`=2");
if (mysqli_num_rows($res1) == 0) {
    echo "<br><br><center><font color='red' size='3'>No Salons Are Added Yet!!!</font></center>";
} else {
?>
<table>
    <td><font size="5">
    <?php
    $i = 1;
    while ($row = mysqli_fetch_array($res1)) {
        echo "<br>" . $i . ". <a href='view_salon.php?uid=" . $row['id'] . "'>" . $row['salon_name'] . "</a><br>";
        $i++;
    }
    ?>
    </font></td>
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
    background-color: #4CAF50;
    color: white;
}
</style>
