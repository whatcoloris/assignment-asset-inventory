<html>
   <head>
      <title>Asset Inventory Form</title>
   </head>
   <body>
      <?php
         $signed_out_to = $location = $phone = $device_id = $category = $description = $purchased = "";
         
         if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $signed_out_to = test_input($_POST["Signed_Out_To"]);
            $location = test_input($_POST["Location"]);
            $phone = test_input($_POST["Phone"]);
            $device_id = test_input($_POST["Device_ID"]);
            $category = test_input($_POST["Category"]);
            $description = test_input($_POST["Description"]);
            $purchased = test_input($_POST["Purchased"]);
         }
         
         function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
         }
      ?>
      
      <form method="post">
         <table>
            <tr>
               <td>Signed Out To:</td> 
               <td><input type="text" name="signed_out_to"></td>
            </tr>
            
            <tr>
               <td>Location (City, State):</td>
               <td><input type="text" name="location"></td>
            </tr>
            
            <tr>
               <td>Phone (xxx-xxx-xxxx):</td>
               <td><input type="text" name="phone"></td>
            </tr>

            <tr>
               <td>Device ID:</td>
               <td><input type="text" name="device_id"></td>
            </tr>

            <tr>
                <td>Category:</td>
                <td>
                    <select name="category">
                        <option value="0">computer</option>
                        <option value="1">peripheral</option>
                        <option value="2">audio</option>
                        <option value="3">video</option>
                        <option value="4">other</option>
                    </select>
                </td>
            </tr>
            
            <tr>
               <td>Description:</td>
               <td><textarea name="comment" rows="5" cols="40"></textarea></td>
            </tr>

            <tr>
               <td>Purchased (year-month-day, ex 2023-01-01):</td>
               <td><input type="text" name="purchased"></td>
            </tr>
            
            <tr>
               <td>
                  <input type="submit" name="submit" value="Submit"> 
               </td>
            </tr>
         </table>
      </form>
      
      <?php
         var_dump()
      ?>
      
   </body>
</html>

