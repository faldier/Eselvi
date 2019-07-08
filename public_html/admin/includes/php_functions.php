<?php

  function delete_row ($database_name, $row_id, $current_Page ,$conn){
    $row_id = (int)$row_id;
    $row_id = sanitize($row_id);
    $conn->query("DELETE FROM $database_name WHERE id = '$row_id'");
    header("Location:$current_Page");
  }





  ?>
