<?php
/**
 * TestLink Open Source Project - http://testlink.sourceforge.net/ 
 * This script is distributed under the GNU General Public License 2 or later. 
 *
 * Filename $RCSfile: printDocument.php,v $
 *
 * @version $Revision: 1.44 $
 * @modified $Date: 2010/09/05 17:40:51 $ by $Author: franciscom $
 * @author Martin Havlat
 *
 * SCOPE:
 * Generate documentation Test report based on Test plan data.
 *
 * Revisions :
 *  20100723 - asimon - BUGID 3459 - added platform ID to calls of 
 *                                   renderTestPlanForPrinting() and renderTestSpecTreeForPrinting()
 *	20100520 - franciscom - BUGID 3451 - In the "Test reports and Metrics" 
 *                                       -> "Test report" the "Last Result" is always "Not Run"
 *  20100326 - asimon - BUGID 3067 - refactored to include requirement document printing
 *	20090906 - franciscom - added platform contribution
 *	20090922 - amkhullar - added a check box to enable/disable display of TC custom fields.
 *  20090309 - franciscom - BUGID 2205 - use test case execution while printing test plan
 * 	20090213 - havlatm - support for OpenOffice
 *	20081207 - franciscom - BUGID 1910 - fixed estimated execution time computation.  
 *
 */
require_once('../../config.inc.php');
require('../../cfg/reports.cfg.php');
require_once('common.php');
require_once('print.inc.php');
require_once('displayMgr.php');

$dummy = null;
$tree = null;
$docText = '';					
$topText = '';

$doc_info = new stdClass(); // gather title, author, product, test plan, etc.
$doc_data = new stdClass(); // gather content and tests related data

testlinkInitPage($db);
$args = init_args();
$doc_info->type = $args->doc_type;
$doc_info->content_range = $args->level;

// Elements in this array must be updated if $arrCheckboxes, in printDocOptions.php is changed.
$printingOptions = array ( 'toc' => 0,'body' => 0,'summary' => 0, 'header' => 0,'headerNumbering' => 1,
		                   'passfail' => 0, 'author' => 0, 'requirement' => 0, 'keyword' => 0, 
		                   'cfields' => 0, 'testplan' => 0, 'metrics' => 0,
		                    'req_spec_scope' => 0,'req_spec_author' => 0,
		                    'req_spec_overwritten_count_reqs' => 0,'req_spec_type' => 0,
		                    'req_spec_cf' => 0,'req_scope' => 0,'req_author' => 0,
		                    'req_status' => 0,'req_type' => 0,'req_cf' => 0,'req_relations' => 0,
		                    'req_linked_tcs' => 0,'req_coverage' => 0);
foreach($printingOptions as $opt => $val)
{
	$printingOptions[$opt] = (isset($_REQUEST[$opt]) && ($_REQUEST[$opt] == 'y'));
}					
$printingOptions['docType'] = $doc_info->type;
$printingOptions['tocCode'] = ''; // to avoid warning because of undefined index
$resultsCfg = config_get('results');
$status_descr_code = $resultsCfg['status_code'];
$status_code_descr = array_flip($status_descr_code);

$tproject = new testproject($db);
$tree_manager = &$tproject->tree_manager;
$hash_descr_id = $tree_manager->get_available_node_types();
$hash_id_descr = array_flip($hash_descr_id);
$decoding_hash = array('node_id_descr' => $hash_id_descr,'status_descr_code' =>  $status_descr_code,
                       'status_code_descr' =>  $status_code_descr);

// can not be null
$order_cfg = array("type" =>'spec_order'); // 20090309 - BUGID 2205
$pnOptionsAdd = null;
switch ($doc_info->type)
{
	case DOC_TEST_SPEC: 
		$doc_info->type_name = lang_get('title_test_spec');
		break;
	
	case DOC_TEST_PLAN: 
		$doc_info->type_name = lang_get('test_plan');
		$order_cfg = array("type" =>'exec_order',"tplan_id" => $args->tplan_id);
		break;
	
	case DOC_TEST_REPORT: 
		$doc_info->type_name = lang_get('test_report');
		
		// needed to filter spec by test plan
		$order_cfg = array("type" =>'exec_order',"tplan_id" => $args->tplan_id);
		
		// BUGID 3451
		$pnOptionsAdd = array('viewType' => 'executionTree');
		break;
		
	case DOC_REQ_SPEC:
		$doc_info->type_name = lang_get('req_spec');
		break;
}

switch ($doc_info->type)
{
	case DOC_REQ_SPEC:
		$my['options']=array('recursive' => true, 'order_cfg' => $order_cfg );
		$my['filters'] = array('exclude_node_types' =>  array('testplan'=>'exclude me', 
                                                      'testsuite'=>'exclude me',
					                                  'testcase'=>'exclude me'),
                       'exclude_children_of' => array('testcase'=>'exclude my children',
		                                              'requirement'=>'exclude my children',
                                                      'testsuite'=> 'exclude my children'));
	break;
		
	default:
		$my['options']=array('recursive' => true, 'order_cfg' => $order_cfg );
		$my['filters'] = array('exclude_node_types' =>  array('testplan'=>'exclude me', 
                                                      'requirement_spec'=>'exclude me', 
					                                  'requirement'=>'exclude me'),
                       'exclude_children_of' => array('testcase'=>'exclude my children',
                                                      'requirement_spec'=> 'exclude my children'));     
	break;
}

$subtree = $tree_manager->get_subtree($args->itemID,$my['filters'],$my['options']);

$tproject_info = $tproject->get_by_id($args->tproject_id);
$doc_info->tproject_name = htmlspecialchars($tproject_info['name']);
$doc_info->tproject_scope = $tproject_info['notes'];

$user = tlUser::getById($db,$_SESSION['userID']);
if ($user)
{
	$doc_info->author = htmlspecialchars($user->getDisplayName());
}
$treeForPlatform = null;
switch ($doc_info->type)
{
	case DOC_REQ_SPEC:
		switch($doc_info->content_range)
		{
			case 'testproject':
				$tree = &$subtree;
				$doc_info->title = $doc_info->tproject_name;
			break;
    	      
			case 'reqspec':
    	      	$spec_mgr = new requirement_spec_mgr($db);
    	  	    $spec = $spec_mgr->get_by_id($args->itemID);
    	  	    $spec['childNodes'] = isset($subtree['childNodes']) ? $subtree['childNodes'] : null;
    	  	    $spec['node_type_id'] = $hash_descr_id['requirement_spec'];
    	  	    $tree['childNodes'][0] = &$spec;
				$doc_info->title = htmlspecialchars($args->tproject_name . 
    	  	                                        $tlCfg->gui_title_separator_2 . $spec['title']);  	               
			break;    
    	} // $doc_info->content_range
    	
    	$treeForPlatform[0] = $tree;
    	break;
	break;
		
    case DOC_TEST_SPEC: // test specification
		switch($doc_info->content_range)
		{
			case 'testproject':
				$tree = &$subtree;
				$doc_info->title = $doc_info->tproject_name;
				break;
    	      
			case 'testsuite':
    	      	$tsuite = new testsuite($db);
    	  	    $tInfo = $tsuite->get_by_id($args->itemID);
    	  	    $tInfo['childNodes'] = isset($subtree['childNodes']) ? $subtree['childNodes'] : null;
    	  	    $tree['childNodes'] = array($tInfo);
				$doc_info->title = htmlspecialchars(isset($tInfo['name']) ? $args->tproject_name .
    	  	      	               $tlCfg->gui_title_separator_2.$tInfo['name'] : $args->tproject_name);
    	  	  	break;    
    	} // $doc_info->content_range
    	$treeForPlatform[0] = $tree;
    	break;
    
    case DOC_TEST_PLAN:
    case DOC_TEST_REPORT:
		    $tplan_mgr = new testplan($db);
		    $tplan_info = $tplan_mgr->get_by_id($args->tplan_id);
		    $doc_info->testplan_name = htmlspecialchars($tplan_info['name']);
		    $doc_info->testplan_scope = $tplan_info['notes'];
		    $doc_info->title = $doc_info->testplan_name;

            // 20100112 - franciscom
            $getOpt = array('outputFormat' => 'map', 'addIfNull' => true);
            $platforms = $tplan_mgr->getPlatforms($args->tplan_id,$getOpt);   
			$tcase_filter = null;
			$execid_filter = null;
			$executed_qty = 0;
			$treeForPlatform = array();


			switch($doc_info->content_range)
			{
				case 'testproject':
					foreach ($platforms as $platform_id => $platform_name)
					{
					  $filters = array('platform_id' => $platform_id);	
    	   	    	  $tp_tcs = $tplan_mgr->get_linked_tcversions($args->tplan_id,$filters);
    	   	    	  
    	   	    	  // IMPORTANTE NOTE:
    	   	    	  // We are in a loop and we use tree on prepareNode, that changes it,
    	   	    	  // then we can not use anymore a reference to test_spec
    	   	    	  // $tree = &$subtree;
    	   	    	  $tree = $subtree;
    	   	    	  if (!$tp_tcs)
    	   	    	  {
    	   	    		   $tree['childNodes'] = null;
    	   	    	  }

    	   	    	  //@TODO:REFACTOR	
    	   	    	  // prepareNode($db,$tree,$decoding_hash,$dummy,$dummy,$tp_tcs,
    	   	    	  //              SHOW_TESTCASES,null,null,0,1,0);

    	   	    	  $dummy = null;
                      $pnFilters = null;
                      $pnOptions =  array('hideTestCases' => 0, 'showTestCaseID' => 1,
		                                  'getExternalTestCaseID' => 0, 'ignoreInactiveTestCases' => 0);
    	   	    	  prepareNode($db,$tree,$decoding_hash,$dummy,$dummy,$tp_tcs,$pnFilters,$pnOptions);
    	   	    	 
    	   	    	  $treeForPlatform[$platform_id] = $tree;            
    	   	    	  
    	   	    	  
    	   	    	}              
            	break;
    	       
				case 'testsuite':
					foreach ($platforms as $platform_id => $platform_name)
					{

						$tsuite = new testsuite($db);
						$tInfo = $tsuite->get_by_id($args->itemID);
                    	
						$children_tsuites = $tree_manager->get_subtree_list($args->itemID,$hash_descr_id['testsuite']);
						if( !is_null($children_tsuites) and trim($children_tsuites) != "")
						{
							$branch_tsuites = explode(',',$children_tsuites);
						}
						$branch_tsuites[]=$args->itemID;
    	   	        	
    	   	        	$filters = array( 'tsuites_id' => $branch_tsuites,'platform_id' => $platform_id);
	                	$tp_tcs = $tplan_mgr->get_linked_tcversions($args->tplan_id, $filters); 
						$tcase_filter=!is_null($tp_tcs) ? array_keys((array)$tp_tcs): null;
    	            	
						$tInfo['node_type_id'] = $hash_descr_id['testsuite'];
						$tInfo['childNodes'] = isset($subtree['childNodes']) ? $subtree['childNodes'] : null;
    	   	        	
						//@TODO: schlundus, can we speed up with NO_EXTERNAL?
						$dummy = null;
						$pnFilters = null;
                        $pnOptions =  array('hideTestCases' => 0);
						
						// BUGID 3624
                        $pnOptions = array_merge($pnOptions, $pnOptionsAdd);
						
						// prepareNode($db,$tInfo,$decoding_hash,$dummy,$dummy,$tp_tcs,SHOW_TESTCASES);
						prepareNode($db,$tInfo,$decoding_hash,$dummy,$dummy,$tp_tcs,$pnFilters,$pnOptions);
						
						$doc_info->title = htmlspecialchars(isset($tInfo['name']) ? $tInfo['name'] : $doc_info->testplan_name);
						$tree['childNodes'] = array($tInfo);
    	   	    	    $treeForPlatform[$platform_id] = $tree;            
                    }
				break;
			}  // switch($doc_info->content_range)
         
			// Create list of execution id, that will be used to compute execution time if
			// CF_EXEC_TIME custom field exists and is linked to current testproject
			$doc_data->statistics = null;                                            
			if ($printingOptions['metrics'])
			{
				$executed_qty=0;
    	 		if ($tp_tcs)
    	 		{
    	 			foreach($tp_tcs as $tcase_id => $info)
			    	{
	    	         	if( $info['exec_status'] != $status_descr_code['not_run'] )
	        	     	{  
	            	 	    $execid_filter[] = $info['exec_id'];
	                		 $executed_qty++;
		             	}    
		         	}    
    			}

				$timeEstimatedDuration = $tplan_mgr->get_estimated_execution_time($args->tplan_id,$tcase_filter);
				if ($timeEstimatedDuration != "0")
				{
		        	$doc_data->statistics['estimated_execution']['minutes'] = $timeEstimatedDuration; 
    		    	$doc_data->statistics['estimated_execution']['tcase_qty'] = count($tp_tcs);
				}
         
				if( $executed_qty > 0)
        		{ 
					$doc_data->statistics['real_execution']['minutes'] = 
						$tplan_mgr->get_execution_time($args->tplan_id,$execid_filter);
             		$doc_data->statistics['real_execution']['tcase_qty'] = $executed_qty;
         		}
 			} // if ($printingOptions['metrics'])
    break;
}


// ----- rendering logic -----
$topText = renderHTMLHeader($doc_info->type.' '.$doc_info->title,$_SESSION['basehref']);
$topText .= renderFirstPage($doc_info);

// Init table of content (TOC) data
renderTOC($printingOptions);
$tocPrefix = null;
if( ($showPlatforms = !isset($treeForPlatform[0]) ? true : false) )
{
	$tocPrefix = 0;
}
if ($treeForPlatform)
{
	foreach ($treeForPlatform as $platform_id => $tree)            
	{
		if($tree)
		{
			$tree['name'] = $args->tproject_name;
			$tree['id'] = $args->tproject_id;
			$tree['node_type_id'] = $hash_descr_id['testproject'];
			switch ($doc_info->type)
			{
				case DOC_REQ_SPEC:	
					$docText .= renderSimpleChapter(lang_get('testproject') . " " . lang_get('scope'), 
					                                $doc_info->tproject_scope);
					                                
					$docText .= renderSimpleChapter(lang_get('requirement_specification_report'), " ");
					                                
					$docText .= renderReqSpecTreeForPrinting($db, $tree, $printingOptions, 
					                                         null, 0, 1, $args->user_id,0,$args->tproject_id);
				break;
				
				case DOC_TEST_SPEC:
					$docText .= renderSimpleChapter(lang_get('scope'), $doc_info->tproject_scope);
					// BUGID 3459 - added platform ID
					$docText .= renderTestSpecTreeForPrinting($db, $tree, $doc_info->content_range,
					            $printingOptions, null, 0, 1, $args->user_id,0,null,$args->tproject_id,$platform_id);
				break;
			
				case DOC_TEST_PLAN:
					if ($printingOptions['testplan'])
					{
						$docText .= renderSimpleChapter(lang_get('scope'), $doc_info->testplan_scope);
					}
						
				case DOC_TEST_REPORT:
				    $tocPrefix++;
			    	if ($showPlatforms)
					{
						$docText .= renderPlatformHeading($tocPrefix, $platform_id, $platforms[$platform_id], 
						                                  $printingOptions);
					}
					// 3459 - added platform ID
					$docText .= renderTestPlanForPrinting($db, $tree, $doc_info->content_range, 
					                                      $printingOptions, $tocPrefix, 0, 1, $args->user_id,
					                                      $args->tplan_id, $args->tproject_id, $platform_id);
					if (($doc_info->type == DOC_TEST_REPORT) && ($printingOptions['metrics']))
					{
						$docText .= buildTestPlanMetrics($doc_data->statistics);
					}	
				break;
			}
		}
	}
}
$docText .= renderEOF();

// Needed for platform feature
if ($printingOptions['toc'])
{
	$printingOptions['tocCode'] .= '</div>';
	$topText .= $printingOptions['tocCode'];
}
$docText = $topText . $docText;


// add application header to HTTP 
if (($args->format == FORMAT_ODT) || ($args->format == FORMAT_MSWORD))
{
	flushHttpHeader($args->format, $doc_info->type);
}

// send out the data
echo $docText;


/** 
 * Process input data
 * @return singleton list of input parameters 
 **/
function init_args()
{
	$args = new stdClass();
	$args->doc_type = $_REQUEST['type'];
	$args->level = isset($_REQUEST['level']) ?  $_REQUEST['level'] : null;
	$args->format = isset($_REQUEST['format']) ? $_REQUEST['format'] : null;
	$args->itemID = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$args->tplan_id = isset($_REQUEST['docTestPlanId']) ? $_REQUEST['docTestPlanId'] : 0;
	
	
	$args->tproject_id = isset($_SESSION['testprojectID']) ? $_SESSION['testprojectID'] : 0;
	$args->tproject_name = isset($_SESSION['testprojectName']) ? $_SESSION['testprojectName'] : 'xxx';
	$args->user_id = isset($_SESSION['userID']) ? intval($_SESSION['userID']) : null;

	return $args;
}
?>