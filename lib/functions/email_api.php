<?php
/** 
 * TestLink Open Source Project - http://testlink.sourceforge.net/
 * This script is distributed under the GNU General Public License 2 or later.
 *
 * Email API (adapted from third party code)
 *
 * @package 	TestLink
 * @author 		franciscom
 * @author 		2002 - 2004 Mantis Team (the code is based on mantis BT project code)
 * @copyright 	2003-2009, TestLink community 
 * @version    	CVS: $Id: email_api.php,v 1.12 2010/05/02 17:11:36 franciscom Exp $
 * @link 		http://www.teamst.org/
 *
 */


/** @uses class.phpmailer.php */
define( 'PHPMAILER_PATH', dirname(__FILE__). '/../../third_party/phpmailer' . DIRECTORY_SEPARATOR );
require_once( PHPMAILER_PATH . 'class.phpmailer.php' );

require_once( 'lang_api.php' );
require_once( 'common.php');
require_once( 'string_api.php');


/** @var mixed reusable object of class SMTP */
$g_phpMailer_smtp = null;


/** 
 * sends the actual email 
 * 
 * @param boolean $p_exit_on_error == true - calls exit() on errors, else - returns true 
 * 		on success and false on errors
 * @param boolean $htmlFormat specify text type true = html, false (default) = plain text
 */
// 20070107 - KL - modified signature to allow caller to specify htmlFormat = true if they so choose
// 20090308 - havlatm - removed unused parameter $p_category
function email_send( $p_from, $p_recipient, $p_subject, $p_message, $p_cc='',
                     $p_exit_on_error = false, $htmlFormat = false ) 
{

	global $g_phpMailer_smtp;
	$op = new stdClass();
	$op->status_ok = true;
 	$op->msg = 'ok';

	// Check fatal Error
	$smtp_host = config_get( 'smtp_host' );
	if( is_blank($smtp_host) )
	{
		$op->status_ok=false;
		$op->msg=lang_get('stmp_host_unconfigured');
		return $op;
	}

	$t_recipient = trim( $p_recipient );
	$t_subject   = string_email( trim( $p_subject ) );
	$t_message   = string_email_links( trim( $p_message ) );

	# short-circuit if no recipient is defined, or email disabled
	# note that this may cause signup messages not to be sent

	# Visit http://phpmailer.sourceforge.net
	# if you have problems with phpMailer
	$mail = new PHPMailer;


	$mail->PluginDir = PHPMAILER_PATH;
  	// 20090201 - franciscom
  	// Need to get strings file for php mailer
  	// To avoid problems I choose ENglish
  	$mail->SetLanguage( 'en', PHPMAILER_PATH . 'language' . DIRECTORY_SEPARATOR );

	# Select the method to send mail
	switch ( config_get( 'phpMailer_method' ) ) {
		case 0: $mail->IsMail();
				break;

		case 1: $mail->IsSendmail();
				break;

		case 2: $mail->IsSMTP();
				{
					# SMTP collection is always kept alive
					#
					$mail->SMTPKeepAlive = true;
					# @@@ yarick123: It is said in phpMailer comments, that phpMailer::smtp has private access.
					# but there is no common method to reset PHPMailer object, so
					# I see the smallest evel - to initialize only one 'private'
					# field phpMailer::smtp in order to reuse smtp connection.

					if( is_null( $g_phpMailer_smtp ) )  {
						register_shutdown_function( 'email_smtp_close' );
					} else {
						$mail->smtp = $g_phpMailer_smtp;
					}
				}
				break;
	}
	$mail->IsHTML($htmlFormat);    # set email format to plain text
	$mail->WordWrap = 80;
	$mail->Priority = config_get( 'mail_priority' );   # Urgent = 1, Not Urgent = 5, Disable = 0

	$mail->CharSet = config_get( 'charset');
	$mail->Host = config_get( 'smtp_host' );
  	$mail->From = config_get( 'from_email' );
	if ( !is_blank( $p_from ) )
	{
	  $mail->From     = $p_from;
	}
	$mail->Sender   = config_get( 'return_path_email' );
	$mail->FromName = '';

	if ( !is_blank( config_get( 'smtp_username' ) ) ) {     # Use SMTP Authentication
		$mail->SMTPAuth = true;
		$mail->Username = config_get( 'smtp_username' );
		$mail->Password = config_get( 'smtp_password' );
	}

	$t_debug_to = '';
	# add to the Recipient list
	$t_recipient_list = split(',', $t_recipient);

	while ( list( , $t_recipient ) = each( $t_recipient_list ) ) {
		if ( !is_blank( $t_recipient ) ) {
				$mail->AddAddress( $t_recipient, '' );
		}
	}

  	// 20051106 - fm
  	$t_cc_list = split(',', $p_cc);
	while(list(, $t_cc) = each($t_cc_list)) {
		if ( !is_blank( $t_cc ) ) {
				$mail->AddCC( $t_cc, '' );
		}
	}

	$mail->Subject = $t_subject;
	$mail->Body    = make_lf_crlf( "\n".$t_message );
	if ( !$mail->Send() ) {

		if ( $p_exit_on_error )  {
		  PRINT "PROBLEMS SENDING MAIL TO: $p_recipient<br />";
		  PRINT 'Mailer Error: '. $mail->ErrorInfo.'<br />';
		  exit;
		}
		else
		{
		  	$op->status_ok = false;
      		$op->msg = $mail->ErrorInfo;
    		return $op;
		}
	}

	if ( !is_null( $mail->smtp ) )  {
		# @@@ yarick123: It is said in phpMailer comments, that phpMailer::smtp has private access.
		# but there is no common method to reset PHPMailer object, so
		# I see the smallest evel - to initialize only one 'private'
		# field phpMailer::smtp in order to reuse smtp connection.
		$g_phpMailer_smtp = $mail->smtp;
	}

	return $op;
}



# --------------------
# closes opened kept alive SMTP connection (if it was opened)
function email_smtp_close()  {
	global $g_phpMailer_smtp;

	if ( !is_null( $g_phpMailer_smtp ) )  {
		if ( $g_phpMailer_smtp->Connected() )  {
			$g_phpMailer_smtp->Quit();
			$g_phpMailer_smtp->Close();
		}
		$g_phpMailer_smtp = null;
	}
}

# --------------------
# clean up LF to CRLF
function make_lf_crlf( $p_string ) {
	$t_string = str_replace( "\n", "\r\n", $p_string );
	return str_replace( "\r\r\n", "\r\n", $t_string );
}
# --------------------
# Check limit_email_domain option and append the domain name if it is set
function email_append_domain( $p_email ) {
	$t_limit_email_domain = config_get( 'limit_email_domain' );
	if ( $t_limit_email_domain && !is_blank( $p_email ) ) {
		$p_email = "$p_email@$t_limit_email_domain";
	}

	return $p_email;
}
?>
