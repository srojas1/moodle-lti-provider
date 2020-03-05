<?php

  use IMSGlobal\LTI\ToolProvider;
  use IMSGlobal\LTI\ToolProvider\DataConnector;

  require_once('lib.php');
  require_once 'vendor/autoload.php';

  // Initialize session and database
  $db = NULL;
  $ok = init($db, TRUE);
  // Initialize parameters
  $id = 0;

  // Page header
  $title = APP_NAME;
  include 'templates/header.html';

  // Display table of existing tool consumer records
  if ($ok) {
    include 'templates/nota.html';
  }

?>
