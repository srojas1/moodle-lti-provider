<?php

  use IMSGlobal\LTI\ToolProvider\DataConnector;

  require_once('nota_tp.php');

// Cancel any existing session
  session_name(SESSION_NAME);
  session_start();
  $_SESSION = array();
  session_destroy();

// Initialize database
  $db = NULL;
  if (init($db)) {

    $data_connector = DataConnector\DataConnector::getDataConnector(DB_TABLENAME_PREFIX, $db);
    $tool = new NotaToolProvider($data_connector);
    $tool->setParameterConstraint('oauth_consumer_key', TRUE, 50, array('basic-lti-launch-request', 'ContentItemSelectionRequest', 'DashboardRequest'));
    $tool->setParameterConstraint('resource_link_id', TRUE, 50, array('basic-lti-launch-request'));
    $tool->setParameterConstraint('user_id', TRUE, 50, array('basic-lti-launch-request'));
    $tool->setParameterConstraint('roles', TRUE, NULL, array('basic-lti-launch-request'));
  } else {
    $tool = new NotaToolProvider(NULL);
    $tool->reason = $_SESSION['error_message'];
  }
  $tool->handleRequest();

?>
