<?php

  require_once('lib.php');


  // Initialise session and database
  $db = NULL;
  $ok = init($db, TRUE);

  if ($ok) {
    // Ensure request is complete and for a student
    $ok = isset($_POST['value']) && $_SESSION['isStudent'];
  }

  if ($ok) {
    // Grabar nota
    $ok = FALSE;
    if (grabarNota($db, $_SESSION['user_pk'], $_POST['value'])) {
      updateGradebook($db, $_SESSION['user_resource_pk'], $_SESSION['user_pk']);
      $ok = TRUE;
    }
  }

  // Generate response
  if ($ok) {
    $response = array('response' => 'Success');
  } else {
    $response = array('response' => 'Fail');
  }

  // Return response
  header('Content-type: application/json');
  echo json_encode($response);
