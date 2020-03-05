<?php

  use IMSGlobal\LTI\ToolProvider;
  use IMSGlobal\LTI\ToolProvider\DataConnector;

  error_reporting(0);
  @ini_set('display_errors', false);

###  Uncomment the next line to enable error messages
  error_reporting(E_ALL);

  require_once('db.php');

###
###  Initialise application session and database connection
###
  function init(&$db, $checkSession = NULL) {

    $ok = TRUE;

// Set timezone
    if (!ini_get('date.timezone')) {
      date_default_timezone_set('UTC');
    }

// Set session cookie path
    ini_set('session.cookie_path', getAppPath());

// Open session
    session_name(SESSION_NAME);
    session_start();


    if (!is_null($checkSession) && $checkSession) {
      $ok = isset($_SESSION['consumer_pk']) && (isset($_SESSION['resource_pk']) || is_null($_SESSION['resource_pk'])) &&
            isset($_SESSION['user_consumer_pk']) && (isset($_SESSION['user_pk']) || is_null($_SESSION['user_pk'])) && isset($_SESSION['isStudent']);
    }


    if (!$ok) {
      $_SESSION['error_message'] = 'Unable to open session.';
    } else {
// Open database connection
      $db = open_db(!$checkSession);
      $ok = $db !== FALSE;
      if (!$ok) {
        if (!is_null($checkSession) && $checkSession) {
// Display a more user-friendly error message to LTI users
          $_SESSION['error_message'] = 'Unable to open database.';
        }
      } else if (!is_null($checkSession) && !$checkSession) {
// Create database tables (if needed)
        $ok = init_db($db);  // assumes a MySQL/SQLite database is being used
        if (!$ok) {
          $_SESSION['error_message'] = 'Unable to initialise database.';
        }
      }
    }

    return $ok;

  }

###
###  Return a count of visible items for a specified resource link
###

  function getNotaFinal($db, $resource_pk) {
		//TODO - Querie para obtener nota
    return 3;
  }

  function grabarNota($db, $user_pk, $nota) {
  	//TODO - Querie para grabar nota
  	return true;
	}

  /*TODO SAMUEL: Grabar Nota*/
/**
 * @param $db
 * @param null $user_resource_pk
 * @param null $user_user_pk
 * @return bool
 */
	function updateGradebook($db, $user_resource_pk = NULL, $user_user_pk = NULL) {
		$data_connector = DataConnector\DataConnector::getDataConnector(DB_TABLENAME_PREFIX, $db);
		$resource_link = ToolProvider\ResourceLink::fromRecordId($_SESSION['resource_pk'], $data_connector);

		$nota = getNotaFinal($db, $_SESSION['resource_pk']);

		$users = $resource_link->getUserResultSourcedIDs();

		foreach ($users as $user) {
			$resource_pk = $user->getResourceLink()->getRecordId();
			$user_pk = $user->getRecordId();
			$update = is_null($user_resource_pk) || is_null($user_user_pk) || (($user_resource_pk === $resource_pk) && ($user_user_pk === $user_pk));
			if ($update) {
					$lti_outcome = new ToolProvider\Outcome(strval($nota));
					$resource_link->doOutcomesService(ToolProvider\ResourceLink::EXT_WRITE, $lti_outcome, $user);
			}
		}
		return true;
	}

###
###  Get the web path to the application
###
  function getAppPath() {

    $root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    if (substr($root, -1) === '/') {  // remove any trailing / which should not be there
      $root = substr($root, 0, -1);
    }
    $dir = str_replace('\\', '/', dirname(__FILE__));

    $path = str_replace($root, '', $dir) . '/';

    return $path;

  }


###
###  Get the application domain URL
###
  function getHost() {

    $scheme = (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on")
              ? 'http'
              : 'https';
    $url = $scheme . '://' . $_SERVER['HTTP_HOST'];

    return $url;

  }


###
###  Get the URL to the application
###
  function getAppUrl() {

    $url = getHost() . getAppPath();

    return $url;

  }


###
###  Return a string representation of a float value
###
  function floatToStr($num) {

    $str = sprintf('%f', $num);
    $str = preg_replace('/0*$/', '', $str);
    if (substr($str, -1) == '.') {
      $str = substr($str, 0, -1);
    }

    return $str;

  }


###
###  Return the value of a POST parameter
###
  function postValue($name, $defaultValue = NULL) {

    $value = $defaultValue;
    if (isset($_POST[$name])) {
      $value = $_POST[$name];
    }

    return $value;

  }


/**
 * Returns a string representation of a version 4 GUID, which uses random
 * numbers.There are 6 reserved bits, and the GUIDs have this format:
 *     xxxxxxxx-xxxx-4xxx-[8|9|a|b]xxx-xxxxxxxxxxxx
 * where 'x' is a hexadecimal digit, 0-9a-f.
 *
 * See http://tools.ietf.org/html/rfc4122 for more information.
 *
 * Note: This function is available on all platforms, while the
 * com_create_guid() is only available for Windows.
 *
 * Source: https://github.com/Azure/azure-sdk-for-php/issues/591
 *
 * @return string A new GUID.
 */
  function getGuid() {

    return sprintf('%04x%04x-%04x-%04x-%02x%02x-%04x%04x%04x',
       mt_rand(0, 65535),
       mt_rand(0, 65535),        // 32 bits for "time_low"
       mt_rand(0, 65535),        // 16 bits for "time_mid"
       mt_rand(0, 4096) + 16384, // 16 bits for "time_hi_and_version", with
                                 // the most significant 4 bits being 0100
                                 // to indicate randomly generated version
       mt_rand(0, 64) + 128,     // 8 bits  for "clock_seq_hi", with
                                 // the most significant 2 bits being 10,
                                 // required by version 4 GUIDs.
       mt_rand(0, 256),          // 8 bits  for "clock_seq_low"
       mt_rand(0, 65535),        // 16 bits for "node 0" and "node 1"
       mt_rand(0, 65535),        // 16 bits for "node 2" and "node 3"
       mt_rand(0, 65535)         // 16 bits for "node 4" and "node 5"
      );

  }

?>