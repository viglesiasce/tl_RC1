<?php
/** 
 * TestLink Open Source Project - http://testlink.sourceforge.net/ 
 * This script is distributed under the GNU General Public License 2 or later.
 * 
 * Date API
 *
 * @package 	TestLink
 * @author 		franciscom; Piece copied form Mantis and adapted to TestLink needs
 * @copyright 	2002 - 2004  Mantis Team   - mantisbt-dev@lists.sourceforge.net
 * @copyright 	2005-2009, TestLink community 
 * @version    	CVS: $Id: date_api.php,v 1.6 2010/04/09 19:47:09 franciscom Exp $
 * @link 		http://www.teamst.org/
 *
 * @internal Revisions:
 * 
 *	20100405 - franciscom - fixed problems found while trying to solve BUGID 3295
 *							some logic on create_range_option_list() was not clear
 *							and may be has never worked ok !!!.
 *							added BLANK option also for time.
 *
 *	20080816 - franciscom
 *	added code to manage datetime Custom Fields (Mantis contribution on 2005)
 *       
 */
 
/**
 * create html code for months combo-box
 * @param integer $p_month (optional) selected month
 * @return array 
 * @todo havlatm: do we use it? Remove?
 */
function create_month_option_list( $p_month = 0 ) 
{
	$month_option=''; 
	for ($i=1; $i<=12; $i++) {
		$month_name = date( 'F', mktime(0,0,0,$i,1,2000) );
		if ( $i == $p_month ) {
			$month_option .= "<option value=\"$i\" selected=\"selected\">$month_name</option>";
		} else {
			$month_option .= "<option value=\"$i\">$month_name</option>";
		}
	}
	return $month_option;
}

	
function create_numeric_month_option_list( $p_month = 0 ) 
{
	$month_option=''; 
	for ($i=1; $i<=12; $i++) {
		if ($i == $p_month) {
			$month_option .= "<option value=\"$i\" selected=\"selected\"> $i </option>" ;
		} else {
			$month_option .= "<option value=\"$i\"> $i </option>" ;
		}
	}
	return $month_option;
}


function create_day_option_list( $p_day = 0 ) 
{
	$day_option = '';
	for ($i=1; $i<=31; $i++) {
		if ( $i == $p_day ) {
			$day_option .= "<option value=\"$i\" selected=\"selected\"> $i </option>";
		} else {
			$day_option .= "<option value=\"$i\"> $i </option>";
		}
	}
	return $day_option;
}
	

function create_year_option_list( $p_year = 0 ) 
{
	$year_option = '';
	$current_year = date( "Y" );
	
	for ($i=$current_year; $i>1999; $i--) {
		if ( $i == $p_year ) {
			$year_option .= "<option value=\"$i\" selected=\"selected\"> $i </option>";
		} else {
			$year_option .= "<option value=\"$i\"> $i </option>";
		}
	}
	return $year_option;
}

	
function create_year_range_option_list( $p_year = 0, $p_start = 0, $p_end = 0) 
{
	$year_option='';
	
	$t_current = date( "Y" ) ;
	$t_forward_years = 2; // config_get( 'forward_year_count' ) ;
	
	$t_start_year = $p_start ;
	if ($t_start_year == 0) {
		$t_start_year = $t_current ;
	}
	if ( ( $p_year < $t_start_year ) && ( $p_year != 0 ) ) {
		$t_start_year = $p_year ;
	}
	
	$t_end_year = $p_end ;
	if ($t_end_year == 0) {
		$t_end_year = $t_current + $t_forward_years ;
	}
	if ($p_year > $t_end_year) {
		$t_end_year = $p_year + $t_forward_years ;
	}
	
	for ($i=$t_start_year; $i <= $t_end_year; $i++) {
		if ($i == $p_year) {
			$year_option .= "<option value=\"$i\" selected=\"selected\"> $i </option>" ;
		} else {
			$year_option .= "<option value=\"$i\"> $i </option>" ;
		}
	}
	return $year_option;
}


// Added contribution (done on mantis) to manage datetime
/** used in cfield_mgr.class.php only */
function create_date_selection_set( $p_name, $p_format, $p_date=0, 
                                    $p_default_disable=false, $p_allow_blank=false, 
                                    $p_year_start=0, $p_year_end=0) 
{
	$str_out='';
	$t_chars = preg_split('//', $p_format, -1, PREG_SPLIT_NO_EMPTY) ;
	if ( $p_date != 0 ) {
		// 20080816 - $t_date = preg_split('/-/', date( 'Y-m-d', $p_date), -1, PREG_SPLIT_NO_EMPTY) ;
		$t_date = preg_split('/-| |:/', date( 'Y-m-d H:i:s', $p_date), -1, PREG_SPLIT_NO_EMPTY) ;
		
	} else {
		// 20080816 -  $t_date = array( 0, 0, 0 );
		// 20100405 - think is WRONG use valid value (0) for time
		// $t_date = array( 0, 0, 0, 0, 0, 0 );
		$t_date = array( 0, 0, 0, -1, -1, -1 );
	}
	
	$t_disable = '' ;
	if ( $p_default_disable == true ) {
		$t_disable = 'disabled' ;
	}
	$t_blank_line_date = '' ;
	$t_blank_line_time = '' ;
	if ( $p_allow_blank == true ) {
		$t_blank_line_date = "<option value=\"0\"></option>" ;
		$t_blank_line_time = "<option value=\"-1\"></option>" ;
	}
	
	foreach( $t_chars as $t_char ) {
		if (strcmp( $t_char, "M") == 0) {
			$str_out .= "<select name=\"" . $p_name . "_month\" $t_disable>" ;
			$str_out .=  $t_blank_line_date ;
			$str_out .= create_month_option_list( $t_date[1] ) ;
			$str_out .= "</select>\n" ;
		}
		if (strcmp( $t_char, "m") == 0) {
			$str_out .= "<select  name=\"" . $p_name . "_month\" $t_disable>" ;
			$str_out .= $t_blank_line_date ;
			$str_out .= create_numeric_month_option_list( $t_date[1] ) ;
			$str_out .= "</select>\n" ;
		}
		if (strcasecmp( $t_char, "D") == 0) {
			$str_out .= "<select  name=\"" . $p_name . "_day\" $t_disable>" ;
			$str_out .= $t_blank_line_date ;
			$str_out .= create_day_option_list( $t_date[2] ) ;
			$str_out .= "</select>\n" ;
		}
		if (strcasecmp( $t_char, "Y") == 0) {
			$str_out .= "<select  name=\"" . $p_name . "_year\" $t_disable>" ;
			$str_out .= $t_blank_line_date ;
			$str_out .= create_year_range_option_list( $t_date[0], $p_year_start, $p_year_end ) ;
			$str_out .= "</select>\n" ;
		}
		
		// -----------------------------------------------------------------
		if (strcasecmp( $t_char, "H") == 0) {
			$str_out .= "<select name=\"" . $p_name . "_hour\" $t_disable>" ;
			$str_out .= $t_blank_line_time ;
			$str_out .= create_range_option_list($t_date[3], 0, 23); 
			$str_out .= "</select>\n" ;
		}
		if (strcasecmp( $t_char, "i") == 0) {
			$str_out .= "<select name=\"" . $p_name . "_minute\" $t_disable>" ;
			$str_out .= $t_blank_line_time ;
			$str_out .= create_range_option_list($t_date[4], 0, 59); 
			$str_out .= "</select>\n" ;
		}
		if (strcasecmp( $t_char, "s") == 0) {
			$str_out .= "<select name=\"" . $p_name . "_second\" $t_disable>" ;
			$str_out .= $t_blank_line_time ;
			$str_out .= create_range_option_list($t_date[5], 0, 59); 
			$str_out .= "</select>\n" ;
		}
	}
	return $str_out;
}


/**
 * 
 *
 */
function create_range_option_list($p_value, $p_min, $p_max ) 
{
	$option_list='';
	for ($idx=$p_min; $idx<=$p_max; $idx++) 
	{
		$selected='';
		$selected = ($idx == $p_value) ? ' selected="selected" ' :'';
		$option_list .="<option value=\"$idx\" {$selected}> $idx </option>";
	}
	return $option_list;
}


?>