<html>
   <head>
      <title>Asset Inventory Form</title>
   </head>
   <body>
      <?php
         session_start();
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
               <td><textarea name="description" rows="5" cols="40"></textarea></td>
            </tr>

            <tr>
               <td>Purchased (ie 2023-01-01):</td>
               <td><input type="text" name="purchased"></td>
            </tr>
            
            <tr>
               <td>
                  <input type="submit" name="submit" value="Add Asset"> 
               </td>

               <td>
                  <input type="submit" name="clear" value="Clear JSON"> 
               </td>
            </tr>
         </table>
      </form>


      <?php
         $array = [];
         $signed_out_to = $location = $phone = $device_id = $category = $description = $purchased = "";

         if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(array_key_exists('clear', $_POST)) {
               session_unset();
               $_SESSION["json"] = "";
            } else {
               $signed_out_to = $_POST["signed_out_to"];
               $location = $_POST["location"];
               $phone = $_POST["phone"];
               $device_id = $_POST["device_id"];
               $category = $_POST["category"];
               $description = $_POST["description"];
               $purchased = $_POST["purchased"];

               $array = json_decode($_SESSION["json"]);
               $array[] = array("Signed"=>$signed_out_to,"Location"=>$location,"Phone"=>$phone,"Device ID"=>$device_id,"Category"=>$category,"Description"=>$description,"Purchased"=>$purchased);

               $_SESSION["json"] = json_encode($array);
            }
         }
      ?>

      <?php
         var_dump($_SESSION["json"]);
      ?>
      
   </body>
</html>

