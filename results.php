<?php
include('config.php');
include('boostrap.php');

// set the default timezone to use. Available since PHP 5.1
date_default_timezone_set('America/New_York');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
    $selected=$_POST['check_list'];
       
            $sql = 'SELECT * FROM staff WHERE id IN (' . implode(',', $selected) . ')';

            $coeficient=0;

           $result = mysqli_query($conn,$sql);

           // Prints something like: Monday 8th of August 2005 03:12:46 PM
           $date = date('l\, j F Y h:i A');
           echo $date;

           //the datetim below is used to put it in the database
           $datetime= date("Y-m-d H:i");
           $datetime;
           
           while($row = $result->fetch_assoc()) 
            {
                $coeficient=$coeficient+$row["coef"];  
            }
            echo "<table class='table table-hover'>";
            echo "<tr>";
            echo "<td>";
            echo "Total coeficient is:  </td><td>".$coeficient;
            echo "</td>";
            echo "</tr>";

            $sales=$_POST["sales"];
            echo "<tr>";
            echo "<td>";
            echo "Total sales are: </td><td> ".$sales;
            echo "</td>";
            echo "</tr>";

            $total_tip=$_POST["totaltip"];
            echo "<tr>";
            echo "<td>";
            echo "Total tip is: </td><td>".$total_tip;
            echo "</td>";
            echo "</tr>";
            
       
        $oncard_tip=round((0.08 * $sales),2);
        echo "<tr>";
        echo "<td>";
        echo "On Card tip is:  </td><td>".$oncard_tip;
        echo "</td>";
        echo "</tr>";

        $plstic_tip=round(($total_tip - $oncard_tip),2);
        echo "<tr>";
        echo "<td>";
        echo "Cash tip is  </td><td>".$plstic_tip;
        echo "</td>";
        echo "</tr>";

        $busboy_oncard=round($oncard_tip / ($coeficient * 2),2);
       // echo "Busboy on Card tip is: ".$busboy_oncard."</br>";
        $busboy_plastic=round($plstic_tip / ($coeficient * 2),2);
       // echo "Busboy Cash tip is: ".$busboy_plastic."</br>";

        $waiter_oncard=round(($oncard_tip / $coeficient),2);
       // echo "Waiter on Card tip is: ".$waiter_oncard."</br>";
        $waiter_plastic=round(($plstic_tip / $coeficient),2);
       // echo "Waiter Cash tip is: ".$waiter_plastic."</br>";
        
        $result1 = mysqli_query($conn,$sql);
        if ($result1->num_rows > 0) {
            echo "<table class='table table-hover'>";
            
            echo "<tr>
            <th>Name</th>
            <th>On Card</th>
            <th>Cash</th>
            </tr>";
            
            
            
            // output data of each row
            while($row = $result1->fetch_assoc()) {
                echo "<tr>";
                $name= $row["name"];
                $id=$row["id"];
                $position=$row["position"];

                echo'<td>' .$name.'<br></td>';
                if($position == 'waiter'){
                    
                    $sql5="INSERT INTO report (date, name, id, sales, tip, cash, declared)
                    VALUES
                        ('$datetime','$name', $id, $sales, $total_tip, $waiter_oncard, $waiter_plastic);";
                    
                    $result5 = $conn->query($sql5);

                echo'<td>' .$waiter_oncard.'</td>';
                echo'<td>' .$waiter_plastic.'</td>';
                }
                else
                {
                    $sql5="INSERT INTO report (date, name, id, sales, tip, cash, declared)
                    VALUES
                        ('$datetime','$name', $id, $sales, $total_tip, $busboy_oncard, $busboy_plastic);";
                    
                    $result5 = $conn->query($sql5);

                echo'<td>' .$busboy_oncard.'</td>';
                echo'<td>' .$busboy_plastic.'</td>';
                }

                echo "</tr>";
            }
            echo "</table>";
            
            } else {
            echo "No match in the database";
            }

    }
}

?>