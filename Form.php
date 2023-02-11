<!DOCTYPE html>
<html lang="en">
   <head>
      <title>Asset Inventory Form</title>
      <!-- Compiled and minified CSS -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
      <!-- Compiled and minified JavaScript -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
      
      <script>
         document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.datepicker');
            var instances = M.Datepicker.init(elems,{format:'yyyy-mm-dd'});
         });

         document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('select');
            var instances = M.FormSelect.init(elems);
         });
      </script>

   </head>
   <body>
      <?php
         session_start();
      ?>
      <div class="container">
         <h1 class="row">Asset Inventory Form</h1>
         <div class="row">
            <h2>Add an Asset</h2>
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
                     <td><input type="text" class="datepicker" name="purchased"></td>
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
         </div>

         <?php
            $array = [];
            $signed_out_to = $location = $phone = $device_id = $category = $description = $purchased = "";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
               if(array_key_exists("clear", $_POST)) {
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

                  if (array_key_exists("json",$_SESSION)) {
                     $array = json_decode($_SESSION["json"]);
                  }
                  $array[] = array("Signed"=>$signed_out_to,"Location"=>$location,"Phone"=>$phone,"Device ID"=>$device_id,"Category"=>$category,
                                 "Description"=>$description,"Purchased"=>$purchased,"Time"=>time());

                  $_SESSION["json"] = json_encode($array);
               }
            }
         ?>

         <div class="row">
            <h2>The last 5 entries</h2>
            <?php
               function displayEntry($content) {
                  echo "<div class='row'>";
                  echo "<h4>";
                  echo $content["Device ID"];
                  echo "</h4>";
                  echo "<ul>";
                  foreach ($content as $key => $value) {
                     if ($key != "Time") {
                        if ($key == "Category") {
                           echo "<li>";
                           echo $key;
                           echo ": ";
                           switch ($value) {
                              case '0':
                                 echo "computer";
                                 break;
                              case '1':
                                 echo "peripheral";
                                 break;
                              case '2':
                                 echo "audio";
                                 break;
                              case '3':
                                 echo "video";
                                 break;
                              case '4':
                                 echo "other";
                                 break;
                              case default:
                                 echo "unknown";
                                 break;
                           }
                           echo "</li>";
                        } else {
                           echo "<li>";
                           echo $key;
                           echo ": ";
                           echo $value;
                           echo "</li>";
                        }
                     }
                  }
                  echo "</ul>";
                  echo "</div>";
               }

               if (array_key_exists("json", $_SESSION) && $_SESSION["json"] != null) {
                  $array = json_decode($_SESSION["json"], true);

                  if (count($array)>4) {
                     for ($i = count($array)-1; $i >= count($array)-5; $i--) {
                        displayEntry($array[$i]);
                     }
                  } else {
                     foreach ($array as $asset) {
                        displayEntry($asset);
                     }
                  }
               }
            ?>
         </div>
         <div>
            <h3>Var Dump:</h3>
            <?php
               if (array_key_exists("json", $_SESSION)) {
                  var_dump($_SESSION["json"]);
               }
            ?>
         </div>
      </div>
   </body>
</html>

