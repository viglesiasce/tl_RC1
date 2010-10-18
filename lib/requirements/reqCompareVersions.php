<?php
/** 
 * TestLink Open Source Project - http://testlink.sourceforge.net/
 * This script is distributed under the GNU General Public License 2 or later. 
 *
 * @package 	TestLink
 * @author asimon
 * @copyright 	2005-2009, TestLink community 
 * @version    	CVS: $Id: reqCompareVersions.php,v 1.5 2010/08/31 09:31:51 mx-julian Exp $
 * @link 		http://www.teamst.org/index.php
 *
 * Compares selected requirements versions with each other.
 *
 * @internal Revisions:
 * 20100831 - Julian - added requirement title to page heading
 */

require_once("../../config.inc.php");
require_once("common.php");
require('../../third_party/diff/diff.php');

$templateCfg = templateConfiguration();
testlinkInitPage($db);
$smarty = new TLSmarty();

$differ = new diff();
$args = init_args();
$gui = new stdClass();

$reqMgr = new requirement_mgr($db);
$reqSet = $reqMgr->get_by_id($args->req_id);

$gui->req_versions = $reqSet;
$gui->req_id = $args->req_id;
$gui->compare_selected_versions = $args->compare_selected_versions;
$gui->context = $args->context;
$gui->version_short = lang_get('version_short');

$labels = array();
$labels["num_changes"] = lang_get("num_changes");
$labels["no_changes"] = lang_get("no_changes");

//if already two versions are selected, display diff
//else display template with versions to select
if ($args->compare_selected_versions) {
	$diff_array = array("scope" => array());

	foreach($reqSet as $req) {
		if ($req['version'] == $args->version_left) {
			$left = $req;
		}
		if ($req['version'] == $args->version_right) {
			$right = $req;
		}
	}

	foreach($diff_array as $key => $val) {
		// insert line endings so diff is better readable and makes sense (not everything in one line)
		// then cast to array with \n as separating character, differ needs that
		$diff_array[$key]["left"] = explode("\n", str_replace("</p>", "</p>\n", $left[$key]));
		$diff_array[$key]["right"] = explode("\n", str_replace("</p>", "</p>\n", $right[$key]));
		$diff_array[$key]["diff"] = $differ->inline($diff_array[$key]["left"], $gui->version_short . $args->version_left, 
		                                            $diff_array[$key]["right"], $gui->version_short . $args->version_right, $args->context);
		$diff_array[$key]["count"] = count($differ->changes);
		$diff_array[$key]["heading"] = lang_get($key);
	
		//are there any changes? then display! if not, nothing to show here
		if ($diff_array[$key]["count"] > 0) {
			$diff_array[$key]["message"] = sprintf($labels["num_changes"], $key, $diff_array[$key]["count"]);
		} else {
			$diff_array[$key]["message"] = sprintf($labels["no_changes"], $key);
		}
	}

	$gui->diff_array = $diff_array;
	$glue_char = config_get('gui_title_separator_1');   
	$gui->subtitle = sprintf(lang_get('diff_subtitle_req'), $args->version_left, $args->version_left, 
	                         $args->version_right, $args->version_right, 
	                         $reqSet[0]['req_doc_id'] . $glue_char . $reqSet[0]['title']);
}

$smarty->assign('gui', $gui);
$smarty->display($templateCfg->template_dir . $templateCfg->default_template);



function init_args()
{
	$args = new stdClass();

	$args->req_id = isset($_REQUEST['requirement_id']) ? $_REQUEST['requirement_id'] : 0;
	$args->compare_selected_versions = isset($_REQUEST['compare_selected_versions']);
	$args->version_left = $_REQUEST['version_left'];
	$args->version_right = $_REQUEST['version_right'];

	$diffEngineCfg = config_get("diffEngine");
	if (isset($_REQUEST['context_show_all'])) {
		$args->context = null;
	} else {
		$args->context = (isset($_REQUEST['context']) && is_numeric($_REQUEST['context'])) ? $_REQUEST['context'] : $diffEngineCfg->context;
	}

	return $args;
}

?>