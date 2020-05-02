<?php

/**
 * ionCube Callback
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    ionCube
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

/**
 * ionCube Loader Error Callback
 *
 * @param array $err_code  Input values
 * @param array $params    array of context-dependent values
 * @return html Error Message
 */
function ioncube_event_handler($err_code, $params)
{
	echo "<html><body><h1 style='text-align:center;'>IonCube error has occurred:</h1>";
	switch ($err_code)
	{
		case ION_CORRUPT_FILE:
			echo "<h4>An encoded file has been corrupted.</h4>";
		break;
		case ION_EXPIRED_FILE:
			echo "<h4>An encoded file has reached its expiry time.</h4>";
		break;
		case ION_NO_PERMISSIONS:
			echo "<h4>An encoded file has a server restriction and is used on a non-authorised system.</h4>";
		break;
		case ION_CLOCK_SKEW:
			echo "<h4>An encoded file is used on a system where the clock is set more than 24 hours before the file was encoded.</h4>";
		break;
		case ION_LICENSE_NOT_FOUND:
			echo "<h4>The license file required by an encoded script could not be found.</h4>";
		break;
		case ION_LICENSE_CORRUPT:
			echo "<h4>The license file has been altered or the passphrase used to decrypt the license was incorrect.</h4>";
		break;
		case ION_LICENSE_EXPIRED:
			echo "<h4>The license file has reached its expiry time.</h4>";
		break;
		case ION_LICENSE_PROPERTY_INVALID:
			echo "<h4>A property marked as ‘enforced’ in the license file was not matched by a property contained in the encoded file.</h4>";
		break;
		case ION_LICENSE_HEADER_INVALID:
			echo "<h4>The header block of the license file has been altered.</h4>";
		break;
		case ION_LICENSE_SERVER_INVALID:
			echo "<h4>The license has a server restriction and is used on a non-authorised system.</h4>";
		break;
		case ION_UNAUTH_INCLUDING_FILE:
			echo "<h4>The encoded file has been included by a file which is either non-encoded or has incorrect properties.</h4>";
		break;
		case ION_UNAUTH_INCLUDED_FILE:
			echo "<h4>The encoded file has included a file which is either non-encoded or has incorrect properties.</h4>";
		break;
		case ION_UNAUTH_APPEND_PREPEND_FILE:
			echo "<h4>The php.ini has either the --auto-append-file or --auto-prepend-file setting enabled.</h4>";
		break;
		default:
			echo "<h4>An unknown error occurred.</h4>";
		break;
	}
	echo "<h4></body></html></h4>";exit;
}

?>
