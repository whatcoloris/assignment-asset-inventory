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

      <!-- CSS for overriding Materialize CSS and Custom CSS -->
      <style>
         /* Setting Font Family and Default Text Color */
         body {
            font-family: Arial, Helvetica, sans-serif;
            color: #646464;
         }

         /* Overriding Materialize CSS */
         .input-field input:focus + label {
            color: #74519B !important;
         }
         .row .input-field input:focus {
            border-bottom: 1px solid #74519B !important;
            box-shadow: 0 1px 0 0 #74519B !important
         }
         ul.dropdown-content.select-dropdown li span {
            color: #74519B; /* no need for !important :) */
         }
         .datepicker-date-display {
            background-color: #74519B;
         }
         .datepicker-table td.is-selected {
            background-color: #74519B;
         }
         .datepicker-table td.is-today {
            color: #74519B;
         }
         .datepicker-table td.is-selected.is-today {
            background-color: #74519B;
            color: white;
         }
         .datepicker-cancel {
            color: #74519B;
         }
         .datepicker-done {
            color: #74519B;
         }

         /* Custom CSS */
         .title {
            color: white;
            background: linear-gradient(to bottom, #62438A, #74519B);
         }
         #form-title {
            text-align: center;
         }
         .header {
            color: #52377A;
         }
         .asset-btn {
            background-color: #74519B;
         }
         .asset-btn:hover {
            background-color: #EBB93F;
         }
         .asset-btn:focus {
            background-color: #EBB93F;
         }
      </style>
   </head>

   <body>
      <!-- Start the Session on each page load -->
      <?php
         session_start();
      ?>

      <!-- HTML Code for the Modal Box -->
      <div id="modal1" class="modal" role="dialog" aria-labelledby="modal-title" aria-modal="true">
         <div class="modal-content">
            <h4 id="modal-title">Duplicate Device ID Detected</h4>
            <p>Would you like to overwrite this record?</p>
            <div id="modal-record"></div>
         </div>
         <div class="modal-footer">
            <!-- Send User overwrite response via POST to PHP -->
            <form method="post">
               <input type="submit" class="modal-close red btn" name="modal-no" value="No">
               <input type="submit" class="modal-close green btn" name="modal-yes" value="Yes">
            </form>
         </div>
      </div>

      <!-- The Main Container for the site -->
      <div class="container">
         <div class="title" role="banner">
            <h1 id="form-title" aria-label="Asset Inventory Form">Asset Inventory Form</h1>
         </div>

         <!-- The Form for submitting Assets -->
         <div class="row">
            <form method="post" class="input-field flow-text" aria-labelledby="form-header">
               <table>
                  <th colspan="2"><h3 class="header" id="form-header" aria-label="Add an Asset">Add an Asset</h3></th>
                  <tr>
                     <td>Signed out to:</td>
                     <td><input type="text" name="signed_out_to"  role="input" aria-label="Signed out to"></td>
                  </tr>
                  <tr>
                     <td>Location (City, State):</td>
                     <td><input type="text" name="location" role="input" aria-label="Location"></td>
                  </tr>
                  <tr>
                     <td>Phone (xxx-xxx-xxxx):</td>
                     <td><input type="text" name="phone" role="input" aria-label="Phone"></td>
                  </tr>
                  <tr>
                     <td>Device ID:</td>
                     <td><input type="text" name="device_id" role="input" aria-label="Device ID"></td>
                  </tr>
                  <tr>
                     <td>Category:</td>
                     <td>
                        <select name="category" role="listbox" aria-label="Category">
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
                     <td><textarea name="description" rows="5" cols="40" role="textarea" aria-label="Description"></textarea></td>
                  </tr>
                  <tr>
                     <td>Purchased:</td>
                     <td><input type="text" name="purchased" role="input" aria-label="Purchased"></td>
                  </tr>
                  <tr>
                     <td>
                        <input type="submit" name="submit" class="btn asset-btn" value="Add Asset" role="button" aria-label="Add Asset">
                     </td>
                     <td>
                        <input type="submit" name="clear" class="btn yellow darken-1" value="Clear JSON" role="button" aria-label="Clear JSON">
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
                           echo "var instance = M.Modal.init(elem, {dismissible: true});
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
                           $_SESSION["save_record"] = array("Signed out to"=>$_POST["signed_out_to"],"Location"=>$_POST["location"],
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
               if ($content["Device ID"] != null) {
                  echo $content["Device ID"];
               } else {
                  echo "&nbsp;";
               }
               echo "</span>";
               echo "<ul aria-labelledby='card-title'>";
               foreach ($content as $key => $value) {
                  if ($key != "Time") {
                     if ($key === "Category") {
                        switch ($value) {
                           case '0':
                              $string_value = "computer";
                              break;
                           case '1':
                              $string_value = "peripheral";
                              break;
                           case '2':
                              $string_value = "audio";
                              break;
                           case '3':
                              $string_value = "video";
                              break;
                           case '4':
                              $string_value = "other";
                              break;
                        }
                        echo "<li aria-label='";
                        echo $key;
                        echo ": ";
                        echo $string_value;
                        echo "'>";
                        echo $key;
                        echo ": ";
                        echo $string_value;
                        echo "</li>";
                     } else {
                        echo "<li aria-label='";
                        echo $key;
                        echo ": ";
                        echo $value;
                        echo "'>";
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
         <div class="row" aria-label="The 5 most recent assets added">
            <h3>The 5 most recent assets added:</h3>
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
         <div class="row" aria-label="The output of PHP's var_dump function on the JSON file of Assets">
            <h4>Var Dump:</h4>
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

