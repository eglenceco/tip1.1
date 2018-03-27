<?php
include('config.php');
include('boostrap.php');

$sql="select * from staff";
$result = mysqli_query($conn,$sql);

if ($result->num_rows > 0) {
echo '<form action="results.php" method="POST">';    
echo "<table class='table table-hover'>";
echo '<tr><td>Sales:<input type="text" name="sales"> </td></tr>';
echo '<tr><td>Total Tip:<input type="text" name="totaltip"> </td></tr>';

echo "<tr><td>Name</td></tr>";



// output data of each row
while($row = $result->fetch_assoc()) {
    echo "<tr>";
    $name= $row["name"];
    $id=$row["id"];

    echo'<td><input type="checkbox" name="check_list[]" value="'.$id.'">' .$name.'<br></td>';
    echo "</tr>";
}
echo "</table>";
echo '<input type="submit" name="submit" value="Calculate">';
echo'</form>';

} else {
echo "0 results";
}
?>

<?php
$conn->close();
?>