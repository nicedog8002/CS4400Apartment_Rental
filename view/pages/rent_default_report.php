<p> Rent Defaulters </p>
<div>
Month
<form method="post" action="rent_default_report">
     <select name="default_month">
      <option value="0">month</option>
      <option value="1">January</option>
      <option value="2">February</option>
      <option value="3">March</option>
      <option value="4">April</option>
      <option value="5">May</option>
      <option value="6">June</option>
      <option value="7">July</option>
      <option value="8">August</option>
      <option value="9">September</option>
      <option value="10">October</option>
      <option value="11">November</option>
      <option value="12">December</option>
    </select>
    <input type="submit" value="confirm" name = "submit"/>
</form>
</div>
<br>

<?php
$selectMonth = $_POST['default_month'];
$dateObj = DateTime::createFromFormat('!m', $selectMonth);
$monthName = $dateObj->format('F'); 
$query = "SELECT A.Apt_No AS Apartment_No, (P.Amount - A.Rent) AS Extra_Paid, (DAY(P.Date_Of_Payment) - 3) AS Defaulted_By 
          FROM Apartment AS A, Payment AS P   
          WHERE P.Apt_No = A.Apt_No 
          AND P.Year = YEAR(now()) 
          AND P.Month = $selectMonth
          AND (DAY(P.Date_Of_Payment) - 3) > 0;";
$result = db()->query($query);
 ?>
 <? if (db()->numOfRows($query)){ ?>
 <P> The defaulted rent for <?php echo $monthName; ?> is: </p>
    <br>
    <table>
      <thead>
        <tr>
           <td>Apartment</td>
           <td>Extra Amount Paid($)</td>
           <td>Defaulted By</td>
         </tr>
      </thead>
      <tbody>
        <? while ($row = $result->fetch_assoc()) { 
        ?>
           <tr>
            <td align ="center"><?php echo $row['Apartment_No']; ?></td>
            <td align ="center"><?php echo $row['Extra_Paid']; ?></td>
            <td align ="center"><?php echo $row['Defaulted_By']; ?></td>
          </tr>
        <?
          }
        ?>
      <tbody>
    </table>
<?
} else {
?> 
  <P> No one defaulted the rent for <?php echo $monthName; ?> </p>
<?
}
?>


