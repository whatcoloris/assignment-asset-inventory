<!DOCTYPE html>
<html lang="en">
   <head>
      <title>Asset Inventory Form</title>
      <!-- Compiled and minified CSS -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
      <!-- Compiled and minified JavaScript -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
      
      <!-- Initialize the Materialize Javascript Objects -->
      <script>
         document.addEventListener('DOMContentLoaded', function() {
            var dElems = document.querySelectorAll('.datepicker');
            var dateInstances = M.Datepicker.init(dElems,{format:'yyyy-mm-dd'});
            var sElems = document.querySelectorAll('select');
            var selectInstances = M.FormSelect.init(sElems);
         });
      </script>

      <!-- Override Default Materialize Styles -->
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
      <!-- Start the Session on each page load -->
      <?php
         session_start();
      ?>

      <!-- HTML Code for the Modal Box -->
      <div id="modal1" class="modal">
         <div class="modal-content">
            <h4>Duplicate Device ID Detected</h4>
            <div id="modal-record"></div>
            <p>Would you like to overwrite the existing record?</p>
         </div>
         <div class="modal-footer">
            <form method="post">
               <input type="submit" class="modal-close red btn" name="modal-no" value="No">
               <input type="submit" class="modal-close green btn" name="modal-yes" value="Yes">
            </form>
         </div>
      </div>

      <!-- The Main Container for the site -->
      <div class="container">
         <div class="purple">
            <h1>Asset Inventory Form</h1>
         </div>

         <!-- The Form for submitting Assets -->
         <div class="row">
            <form method="post" class="input-field">
               <table>
                  <th colspan="2"><h2 class="header">Add an Asset</h2></th>
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
                        <input type="submit" name="submit" class="btn purple" value="Add Asset">
                     </td>
                     <td>
                        <input type="submit" name="clear" class="btn yellow darken-3" value="Clear JSON">
                     </td>
                  </tr>
               </table>
            </form>
         </div>

         <!-- 3 PHP Functions for checking, submitting, and displaying Assets -->
         <?php
            function checkDeviceID($given_id) {
               if (array_key_exists("json", $_SESSION)) {
                  $existing_assets = json_decode($_SESSION["json"], true);
                  if ($existing_assets != null) {
                     $size = count($existing_assets);
                     for ($i = 0; $i < $size; $i++) {
                        if ($given_id === $existing_assets[$i]["Device ID"]) {
                           echo "<script>var recordDisplay = document.getElementById('modal-record');";
                           echo 'recordDisplay.innerHTML = "';
                           displayEntry($existing_assets[$i]);
                           echo '";';
                           echo "var elem = document.querySelectorAll('#modal1');";
                           echo "var instance = M.Modal.init(elem, {dismissible: false});
                           instance[0].open();";
                           echo "</script>";
                           return $i;
                        }
                     }
                  }
               }
            }

            function loadAsset() {
               if ($_SERVER["REQUEST_METHOD"] === "POST") {
                  $array = [];
                  $overwrite = "";

                  if (array_key_exists("clear", $_POST)) {
                     session_unset();
                     $_SESSION["json"] = "";
                  } else {
                     if (! array_key_exists("modal-no", $_POST)) {
                        if (! array_key_exists("save_record", $_SESSION) || ! array_key_exists("modal-yes", $_POST)) {
                           $_SESSION["save_record"] = array("Signed Out To"=>$_POST["signed_out_to"],"Location"=>$_POST["location"],
                           "Phone"=>$_POST["phone"],"Device ID"=>$_POST["device_id"],"Category"=>$_POST["category"],
                           "Description"=>$_POST["description"],"Purchased"=>$_POST["purchased"],"Time"=>time());

                           $_SESSION["overwrite"] = checkDeviceID($_POST["device_id"]);
                           //echo "<script>alert('device checked: ".$_SESSION["overwrite"]."');</script>";
                        }

                        if (array_key_exists("json", $_SESSION)) {
                           $array = json_decode($_SESSION["json"], true);
                        }

                        if ($_SESSION["overwrite"] != "" && array_key_exists("modal-yes", $_POST)) {
                           $array[$_SESSION["overwrite"]] = $_SESSION["save_record"];
                           $_SESSION["overwrite"] = "";
                           //echo "<script>alert('overwritten');</script>";
                        } else {
                           if (! array_key_exists("modal-yes", $_POST) && ! array_key_exists("modal-no", $_POST) && $_SESSION["overwrite"] == "") {
                              //echo "<script>alert('record added');</script>";
                              $array[] = $_SESSION["save_record"];
                           }
                        }
      
                        $_SESSION["json"] = json_encode($array);
                     }
                  }
               }
            }

            function displayEntry($content) {
               echo "<div class='col s12 m6 l4 entry'>";
               echo "<div class='card'>";
               echo "<div class='card-content'>";
               echo "<span class='card-title'>";
               echo $content["Device ID"];
               echo "</span>";
               echo "<ul>";
               foreach ($content as $key => $value) {
                  if ($key != "Time") {
                     if ($key === "Category") {
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
               echo "</div></div></div>";
            }

            loadAsset();
         ?>

         <!-- Output from the Asset Form -->
         <div class="row">
            <h2>The last 5 entries</h2>
            <?php
               if (array_key_exists("json", $_SESSION) && $_SESSION["json"] != null) {
                  $array = json_decode($_SESSION["json"], true);
                  $display_array = array_column($array, 'Time');
                  array_multisort($display_array, SORT_DESC, $array);
                  
                  $size = count($array);
                  if ($size>4) {
                     for ($i = 0; $i < 5; $i++) {
                        displayEntry($array[$i]);
                     }
                  } else {
                     for ($i = 0; $i < $size; $i++) {
                        displayEntry($array[$i]);
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
      <!-- End of Container -->
      </div>
   </body>
</html>

