<p> Service Request Resolution Report </p>
<?php
$query = "SELECT  MONTHNAME(Date_Of_Request) AS Month, Issue_Type, AVG(DATEDIFF(Date_Resolved, Date_Of_Request)) AS Avg_Days_To_Resolve
			FROM Maintenance_Request 
			WHERE MONTH(Date_Of_Request) <= MONTH(now()) 
			AND MONTH(Date_Of_Request) in (8,9,10)
			AND Issue_Status = 'resolved'
			AND YEAR(Date_Of_Request) = YEAR(now()) 
			GROUP BY MONTHNAME(Date_Of_Request), Issue_Type
			ORDER BY MONTH(Date_Of_Request) ASC;";
 ?>
 <?php if ($result = db()->query($query)) { ?>
    <table>
      <thead>
        <tr>
           <td>Month</td>
           <td>Type of Request</td>
           <td>Average No of Days</td>
         </tr>
      </thead>
      <tbody>
        <?php 
         $month = ''; 
         while ($row = $result->fetch_assoc()) { 
        ?>
           <tr>
            <td><?php echo ($month == $row['Month']) ? '' : $row['Month']; ?></td>
            <td><?php echo $row['Issue_Type']; ?></td>
            <td><?php echo $row['Avg_Days_To_Resolve']; ?></td>
          </tr>
          <?php $month = $row['Month'];?>
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

  