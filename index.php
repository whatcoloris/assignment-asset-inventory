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
            var dElems = document.querySelectorAll('.datepicker');
            var dateInstances = M.Datepicker.init(dElems,{format:'yyyy-mm-dd'});
            var sElems = document.querySelectorAll('select');
            var selectInstances = M.FormSelect.init(sElems);
            var mElems = document.querySelectorAll('#modal1.modal');
            var modalInstances = M.Modal.init(mElems);
         });
      </script>

      <style>
         .input-field input:focus + label {
            color: purple !important;
         }
         .row .input-field input:focus {
            border-bottom: 1px solid purple !important;
            box-shadow: 0 1px 0 0 purple !important
         }
         ul.dropdown-content.select-dropdown li span {
            color: purple; /* no need for !important :) */
         }
         .datepicker-date-display {
            background-color: purple;
         }
         .datepicker-table td.is-selected {
            background-color: purple;
         }
         .datepicker-table td.is-today {
            color: purple;
         }
         .datepicker-table td.is-selected.is-today {
            background-color: purple;
            color: white;
         }
         .datepicker-cancel {
            color: purple;
         }
         .datepicker-done {
            color: purple;
         }
         .header {
            background: linear-gradient(to bottom, orange, yellow)
         }
      </style>
   </head>
   <body>
      <?php
         session_start();
      ?>

      <div id="modal1" class="modal">
         <div class="modal-content">
            <h4>Duplicate Device ID Detected</h4>
            <div id="modal-record"></div>
            <p>Would you like to overwrite the existing record?</p>
         </div>
         <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">No</a>
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Yes</a>
         </div>
      </div>

      <div class="container">
         <div class="purple">
            <h1>Asset Inventory Form</h1>
         </div>
         <div class="row">
            <form method="post" class="input-field">
               <table>
                  <th><h2 class="header">Add an Asset</h2></th>
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
                     <td>Purchased:</td>
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
            function checkDeviceID($given_id) {
               echo "CHECKING DEVICE ID";
               if (array_key_exists("json", $_SESSION)) {
                  $existing_assets = json_decode($_SESSION["json"], true);
                  if ($existing_assets != null) {
                     foreach ($existing_assets as $asset) {
                        if ($given_id == $asset["Device ID"]) {
                           echo "THE ID IS ALREADY IN USE";
                           echo "<script>var recordDisplay = document.getElementById('modal-record');";
                           echo 'recordDisplay.innerHTML = "';
                           displayEntry($asset);
                           echo '";';
                           echo "</script>";
                           return false;
                        }
                     }
                     return true;
                  } else {
                     return true;
                  }
               } else {
                  return true;
               }
            }

            $array = [];
            $signed_out_to = $location = $phone = $device_id = $category = $description = $purchased = "";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
               if (array_key_exists("clear", $_POST)) {
                  echo "CLEARING SESSION";
                  session_unset();
                  $_SESSION["json"] = "";
               } else {
                  if (checkDeviceID($_POST["device_id"])) {
                     $signed_out_to = $_POST["signed_out_to"];
                     $location = $_POST["location"];
                     $phone = $_POST["phone"];
                     $device_id = $_POST["device_id"];
                     $category = $_POST["category"];
                     $description = $_POST["description"];
                     $purchased = $_POST["purchased"];

                     if (array_key_exists("json", $_SESSION)) {
                        echo "WE HAVE A SESSION";
                        $array = json_decode($_SESSION["json"], true);
                     }

                     $array[] = array("Signed"=>$signed_out_to,"Location"=>$location,"Phone"=>$phone,
                                    "Device ID"=>$device_id,"Category"=>$category,"Description"=>$description,
                                    "Purchased"=>$purchased,"Time"=>time());
   
                     $_SESSION["json"] = json_encode($array);
                  }
               }
            }
         ?>

         <div class="row">
            <h2>The last 5 entries</h2>
            <?php
               function displayEntry($content) {
                  echo "INSIDE DISPLAY ENTRY";
                  echo "<div class='col s6 m4 l3'>";
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
               } else {
                  echo "No assets added yet.";
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

