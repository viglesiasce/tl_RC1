<?php
/** 
 * TestLink Open Source Project - http://testlink.sourceforge.net/
 * This script is distributed under the GNU General Public License 2 or later. 
 *
 * Direct links to testlink items from the outside with frames for navigation and tree.
 *
 * IMPORTANT - LIMITATIONS:
 * User has to login before clicking the link!
 * If user is not logged in he is redirected to login page. 
 * After login main page is shown, Clicking the link again then it works!
 *
 * How this feature works:
 * 
 * - direct link to testcase KAOS-4 in test project KAOS:
 * http://<testlink_home>/linkto.php?tprojectPrefix=KAOS&item=testcase&id=KAOS-4
 * 
 * - direct link to requirement REQ-002 in test project KAOS:
 * http://<testlink_home>/linkto.php?tprojectPrefix=KAOS&item=req&id=REQ-002
 *
 * - direct link to requirement specification REQ-SPEC-AK89 in test project KAOS:
 * http://<testlink_home>/linkto.php?tprojectPrefix=KAOS&item=reqspec&id=REQ-SPEC-AK89
 * 
 * Anchors:
 * If anchors are set (in scope, etc.) in the linked document, you can specify these
 * by using &anchor=anchorname, e.g.
 * http://<testlink_home>/linkto.php?tprojectPrefix=KAOS&item=testcase&id=KAOS-4&anchor=importantpart
 * 
 * Specials:
 * - tree for requirement specification or test specification are expanded to the level of the item you created the link to
 * - if a user has no right to view item he is redirected to main page
 * - if item does not exist an errormessage shows
 * 
 * @package 	TestLink
 * @author 		asimon
 * @copyright 	2007-2010, TestLink community 
 * @version    	CVS: $Id: linkto.php,v 1.7 2010/09/14 12:39:13 mx-julian Exp $
 * @link 		http://www.teamst.org/index.php
 *
 * @internal Revisions:
 *  20100223 - asimon - added anchor functionality
 *  20091215 - asimon - refactored process_req() with new method in requirement_mgr class
 *	20091215 - franciscom - refactored
 *	20091214 - asimon83 - refactoring like requested in issue comments
 */

// use output buffer to prevent headers/data from being sent before 
// cookies are set, else it will fail
ob_start();

// some session and settings stuff from original index.php 
require_once('lib/functions/configCheck.php');
checkConfiguration();
require_once('config.inc.php');
require_once('common.php');
require_once('attachments.inc.php');
require_once('requirements.inc.php');
require_once('testcase.class.php');
require_once('testproject.class.php');
require_once('users.inc.php');
testlinkInitPage($db, true);

$smarty = new TLSmarty();
$smarty->assign('title', lang_get('main_page_title'));

// display outer or inner frame?
if (!isset($_GET['load'])) {
	// display outer frame, pass parameters to next script call for inner frame
	$item = isset($_GET['item']) ? "item=" . $_GET['item'] : '';
	$id = isset($_GET['id']) ? "id=" . $_GET['id'] : '';
	$tprojectPrefix = isset($_GET['tprojectPrefix']) ? "tprojectPrefix=" . $_GET['tprojectPrefix'] : '';
	$anchor = isset($_GET['anchor']) ? 'anchor=' . $_GET['anchor'] : "";
	$smarty->assign('titleframe', 'lib/general/navBar.php');
	$smarty->assign('mainframe', 'linkto.php?' . $item . '&' . $id . '&' . $tprojectPrefix . '&load&' . $anchor);
	$smarty->display('main.tpl');
} 
else 
{
	// inner frame, parameters passed 
	
	// add anchor
	$anchor = isset($_GET['anchor']) ? '#' . $_GET['anchor'] : "";
	
	// figure out what to display 
	//
	// key: item, value: url to tree management page
	$itemCode = array('req' => 'lib/requirements/reqSpecListTree.php', 
	                  'reqspec' => 'lib/requirements/reqSpecListTree.php',
	                  'testcase' => 'lib/testcases/listTestCases.php?feature=edit_tc' );
	                  
	$op = array('status_ok' => true, 'msg' => '');
	
	// First check for keys in _GET that MUST EXIST
	// key: key on _GET, value: labelID defined on strings.txt
    $mustKeys = array('tprojectPrefix' => 'testproject_not_set', 
                      'item' => 'item_not_set', 'id' => 'id_not_set');
                      
    
    $tprojectPrefix = isset($_GET['tprojectPrefix']) ? $_GET['tprojectPrefix'] : '';
    
	foreach($mustKeys as $key => $labelID)
	{
		$op['status_ok'] = isset($_GET[$key]);
		if( !$op['status_ok'])
		{
			$op['msg'] = lang_get($labelID);
			break;
		}
	} 

	if($op['status_ok'])
	{
		$tproject = new testproject($db);
		$tproject_data = $tproject->get_by_prefix($tprojectPrefix);
		if(($op['status_ok'] = !is_null($tproject_data))) 
		{
			$tproject->setSessionProject($tproject_data['id']);
            $op['status_ok'] = isset($itemCode[$_GET['item']]);
			$op['msg'] = sprintf(lang_get('invalid_item'),$_GET['item']);
        } 	    
		else 
		{
			$op['msg'] = sprintf(lang_get('testproject_not_found'),$_GET['tprojectPrefix']);
		}
	} 

     
	if($op['status_ok'])
	{
		// Build  name of function to call for doing the job.
		$pfn = 'process_' . $_GET['item'];
		$jump_to = $pfn($db,$_GET['id'],$tproject_data['id'], $tprojectPrefix);
		
		$op['status_ok'] = !is_null($jump_to['url']);
		$op['msg'] = $jump_to['msg'];
    }
	
	if($op['status_ok'])
	{
		//add anchor
		$url = $jump_to['url'] . $anchor;
		$smarty->assign('treewidth', TL_FRMWORKAREA_LEFT_FRAME_WIDTH);
		$smarty->assign('workframe', $url);
		$smarty->assign('treeframe', $itemCode[$_GET['item']]);
		$smarty->display('frmInner.tpl');
    }
    else
	{
		echo $op['msg'];
		ob_end_flush();
		exit();
	}
}
ob_end_flush();



/**
 * process_testcase
 *
 */
function process_testcase(&$dbHandler,$externalID,$tprojectID, $tprojectPrefix)
{
	$ret = array();
	$ret['url'] = null;
	$ret['msg'] = sprintf(lang_get('testcase_not_found'), $externalID, $tprojectPrefix);
    
	$tcase_mgr = new testcase($dbHandler);
	$tcaseID = $tcase_mgr->getInternalID($externalID);
	if($tcaseID > 0)
	{
		$ret['url'] = "lib/testcases/archiveData.php?edit=testcase&id={$tcaseID}";
        $cookie = buildCookie($dbHandler,$tcaseID,$tprojectID,'ys-tproject_');
		setcookie($cookie['value'], $cookie['path'], TL_COOKIE_KEEPTIME, '/');
	}
    return $ret;
}


/**
 * process_req
 *
 */
function process_req(&$dbHandler,$docID,$tprojectID,$tprojectPrefix)
{
	$tables = tlObjectWithDB::getDBTables(array('requirements','nodes_hierarchy', 
	                                            'req_specs', 'tcversions'));

	$ret = array();
	$ret['url'] = null;
    $ret['msg'] = sprintf(lang_get('req_not_found'), $docID,$tprojectPrefix);

    $req_mgr = new requirement_mgr($dbHandler);
    $req = $req_mgr->getByDocID($docID,$tprojectID);

    if( !is_null($req) )
    {    
		// link to open in requirement frame
		$req = current($req);
		$ret['url'] = "lib/requirements/reqView.php?item=requirement&requirement_id={$req['id']}";

        $cookie = buildCookie($dbHandler,$req['id'],$tprojectID,'ys-requirement_spec');
		setcookie($cookie['value'], $cookie['path'], TL_COOKIE_KEEPTIME, '/');
	} 
	return $ret;
}



/**
 * process_reqspec
 *
 */
function process_reqspec(&$dbHandler,$docID,$tprojectID,$tprojectPrefix)
{
	$ret = array();
	$ret['url'] = null;
    $ret['msg'] = sprintf(lang_get('req_spec_not_found'), $docID,$tprojectPrefix);

    $reqspec_mgr = new requirement_spec_mgr($dbHandler);
    $reqSpec = $reqspec_mgr->getByDocID($docID,$tprojectID);
    
    if( !is_null($reqSpec) )
    {
    	$reqSpec = current($reqSpec);
    	$id = $reqSpec['id'];
		$ret['url'] = "lib/requirements/reqSpecView.php?req_spec_id={$id}";

        $cookie = buildCookie($dbHandler,$id,$tprojectID,'ys-requirement_spec');
		setcookie($cookie['value'], $cookie['path'], TL_COOKIE_KEEPTIME, '/');
    }
	return $ret;
}



/**
 * 
 *
 */
function buildCookie(&$dbHandler,$itemID,$tprojectID,$cookiePrefix)
{
	$tree_mgr = new tree($dbHandler);
	$path = $tree_mgr->get_path($itemID);
	$parents = array();
	$parents[] = $tprojectID;
	foreach($path as $node) {
		$parents[] = $node['id'];
	}
	array_pop($parents);
	$cookieInfo['path'] = 'a:s%3A/' . implode("/", $parents);
	$cookieInfo['value'] = $cookiePrefix . $tprojectID . '_ext-comp-1001' ;
	return $cookieInfo;
}
?>