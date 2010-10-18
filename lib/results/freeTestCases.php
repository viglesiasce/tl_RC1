<?php
/** 
 * TestLink Open Source Project - http://testlink.sourceforge.net/
 * This script is distributed under the GNU General Public License 2 or later. 
 *  
 * @filesource $RCSfile: freeTestCases.php,v $
 * @version $Revision: 1.7 $
 * @modified $Date: 2010/10/05 08:29:41 $ by $Author: asimon83 $
 * @author Francisco Mancardi - francisco.mancardi@gmail.com
 * 
 * For a test project, list FREE test cases, i.e. not assigned to a test plan.
 * 
 * rev:
 * 20101005 - asimon - added linked icon for testcase editing
 * 20100920 - Julian - use exttable
 *                   - added importance column
 * 20090412 - franciscom - BUGID 2363
 *
 */
require_once("../../config.inc.php");
require_once("common.php");
require_once('exttable.class.php');
testlinkInitPage($db,true,false,"checkRights");

$templateCfg = templateConfiguration();
$tcase_cfg = config_get('testcase_cfg');

$importance_levels = config_get('importance_levels');

$args = init_args();
$tproject_mgr = new testproject($db);

$msg_key = 'all_testcases_has_testplan';

$edit_label = lang_get('design');
$edit_img = TL_THEME_IMG_DIR . "edit_icon.png";

$gui = new stdClass();
$gui->freeTestCases = $tproject_mgr->getFreeTestCases($args->tproject_id);
$gui->path_info = null;
$gui->tableSet = null;
if(!is_null($gui->freeTestCases['items']))
{
    if($gui->freeTestCases['allfree'])
    { 
        // has no sense display all test cases => display just message.
        $msg_key = 'all_testcases_are_free';
  	}   
  	else
  	{
        $msg_key = '';    
        $tcasePrefix = $tproject_mgr->getTestCasePrefix($args->tproject_id) . $tcase_cfg->glue_character;
        $tcaseSet = array_keys($gui->freeTestCases['items']);
        $options = array('output_format' => 'path_as_string');
        $tsuites = $tproject_mgr->tree_manager->get_full_path_verbose($tcaseSet,$options);
        $titleSeperator = config_get('gui_title_separator_1');
  	    
		$columns = getColumnsDefinition();
	
		// Extract the relevant data and build a matrix
		$matrixData = array();
		
		foreach($gui->freeTestCases['items'] as $tcases) {
			$rowData = array();
			
			$rowData[] = strip_tags($tsuites[$tcases['id']]);
			//build test case link

			$edit_link = "<a href=\"javascript:openTCEditWindow({$tcases['id']});\">" .
						 "<img title=\"{$edit_label}\" src=\"{$edit_img}\" /></a> ";
			$tcaseName = $tcasePrefix . $tcases['tc_external_id'] . $titleSeperator .
			             strip_tags($tcases['name']);
		    $tcLink = $edit_link . $tcaseName;
			$rowData[] = $tcLink;

//			$rowData[] = "<a href=\"lib/testcases/archiveData.php?edit=testcase&id={$tcases['id']}\">" .
//			             $tcasePrefix . $tcases['tc_external_id'] . $titleSeperator .
//			             strip_tags($tcases['name']);
			
			switch ($tcases['importance']) {
				case $importance_levels[LOW]:
					$rowData[] = "<!-- 1 -->" . lang_get('low_importance');
					break;
				case $importance_levels[MEDIUM]:
					$rowData[] = "<!-- 2 -->" . lang_get('medium_importance');
					break;
				case $importance_levels[HIGH]:
					$rowData[] = "<!-- 3 -->" . lang_get('high_importance');
					break;
			}
			
			$matrixData[] = $rowData;
		}
		
		$table = new tlExtTable($columns, $matrixData, 'tl_table_test_cases_not_assigned_to_any_test_plan');
		
		$table->setGroupByColumnName(lang_get('test_suite'));
		
		$table->setSortByColumnName(lang_get('importance'));
		$table->sortDirection = 'DESC';
		
		$table->showToolbar = true;
		$table->toolbarExpandCollapseGroupsButton = true;
		$table->toolbarShowAllColumnsButton = true;
		
		$gui->tableSet = array($table);
  	    
  	}
}


$gui->tproject_name = $args->tproject_name;
$gui->pageTitle = lang_get('report_free_testcases_on_testproject');
$gui->warning_msg = lang_get($msg_key);


$smarty = new TLSmarty();
$smarty->assign('gui',$gui);
$smarty->display($templateCfg->template_dir . $templateCfg->default_template);


/**
 * get Columns definition for table to display
 *
 */
function getColumnsDefinition()
{
	$colDef = array();
	
	$colDef[] = array('title' => lang_get('test_suite'), 'type' => 'text');
	$colDef[] = array('title' => lang_get('test_case'), 'type' => 'text');
	$colDef[] = array('title' => lang_get('importance'), 'width' => 20);

	return $colDef;
}

/**
 * init_args
 *
 * Collect all inputs (arguments) to page, that can be arrived via $_REQUEST,$_SESSION
 * and creates an stdClass() object where each property is result of mapping page inputs.
 * We have created some sort of 'namespace', thi way we can easy understand which variables
 * has been created for local use, and which have arrived on call.
 *
 */
function init_args()
{
    $iParams = array(
		"tplan_id" => array(tlInputParameter::INT_N),
		"format" => array(tlInputParameter::INT_N),
	);

	$args = new stdClass();
	$pParams = G_PARAMS($iParams,$args);

	$args->tproject_id = isset($_SESSION['testprojectID']) ? $_SESSION['testprojectID'] : 0;
    $args->tproject_name = isset($_SESSION['testprojectName']) ? $_SESSION['testprojectName'] : '';

    return $args;
}

function checkRights(&$db,&$user)
{
	return $user->hasRight($db,'testplan_metrics');
}
?>


