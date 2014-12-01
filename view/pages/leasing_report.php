<p> 3 Month leasing report </p>
<?php

$query = "SELECT MONTHNAME(PR.Pref_Move) AS Lease_Month, A.Category, COUNT(A.Apt_No) AS nums_of_Apartments
          FROM Apartment AS A, Prospective_Resident AS PR, Resident AS R
          WHERE A.Apt_No = R.Apt_No AND PR.Username = R.Username
          AND MONTH(PR.Pref_Move) in (8,9,10)
          AND MONTH(PR.Pref_Move) <= MONTH(now())
          AND YEAR(PR.Pref_Move) = YEAR(now())
          GROUP BY MONTHNAME(PR.Pref_Move), A.Category 
          ORDER BY PR.Pref_Move ASC;";
 if ($result = db()->query($query)) { ?>
    <table>
      <thead>
        <tr>
           <td>Month</td>
           <td>Category</td>
           <td>No. Of Apartments</td>
         </tr>
      </thead>
      <tbody>
        <?php 
         $month = ''; 
         while ($row = $result->fetch_assoc()) { 
        ?>
           <tr>
            <td><?php echo ($month == $row['Lease_Month']) ? '' : $row['Lease_Month']; ?></td>
            <td><?php echo $row['Category']; ?></td>
            <td align ="center"><?php echo $row['nums_of_Apartments']; ?></td>
          </tr>
          <?php $month = $row['Lease_Month'];?>
        <?php 
          }
        ?>
      <tbody>
    </table>
<?php 
}
?>

<br>
<a href="report">
  Back to Reports
</a>


