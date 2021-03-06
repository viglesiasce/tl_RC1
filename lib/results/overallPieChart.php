<?php
/** 
 * TestLink Open Source Project - http://testlink.sourceforge.net/
 * This script is distributed under the GNU General Public License 2 or later. 
 *
 * @package 	TestLink
 * @author 		franciscom
 * @copyright 	2005-2009, TestLink community
 * @copyright 	
 * @version    	CVS: $Id: overallPieChart.php,v 1.12 2010/09/12 17:09:17 franciscom Exp $
 * @link 		http://www.teamst.org/index.php
 *
 * @internal Revisions:
 *
 *
**/
require_once('../../config.inc.php');
require_once('common.php');
define('PCHART_PATH','../../third_party/pchart');
include(PCHART_PATH . "/pChart/pData.class");   
include(PCHART_PATH . "/pChart/pChart.class");   
testlinkInitPage($db,true,false,"checkRights");

$resultsCfg = config_get('results');
$chart_cfg = $resultsCfg['charts']['dimensions']['overallPieChart'];

$args = init_args();
$tplan_mgr = new testplan($db);
$totals = $tplan_mgr->getStatusTotals($args->tplan_id);
unset($totals['total']);

$values = array();
$labels = array();
foreach($totals as $key => $value)
{
    $values[] = $value;
    $labels[] = lang_get($resultsCfg['status_label'][$key]) . " ($value)"; 
    if( isset($resultsCfg['charts']['status_colour'][$key]) )
    {
    	$series_color[] = $resultsCfg['charts']['status_colour'][$key];
    }	
}

// Dataset definition    
$DataSet = new pData;   
$DataSet->AddPoint($values,"Serie1");   
$DataSet->AddPoint($labels,"Serie8");   
$DataSet->AddAllSeries();   
$DataSet->SetAbsciseLabelSerie("Serie8");   

// Initialise the graph
$pChartCfg = new stdClass(); 
$pChartCfg->XSize = $chart_cfg['XSize'];
$pChartCfg->YSize = $chart_cfg['YSize'];                    
$pChartCfg->radius = $chart_cfg['radius'];
$pChartCfg->legendX = $chart_cfg['legendX'];                    
$pChartCfg->legendY = $chart_cfg['legendY'];

$pChartCfg->centerX = intval($pChartCfg->XSize/2);                    
$pChartCfg->centerY = intval($pChartCfg->YSize/2);


$graph = new stdClass();
$graph->data = $DataSet->GetData();
$graph->description = $DataSet->GetDataDescription();

$Test = new pChart($pChartCfg->XSize,$pChartCfg->YSize);
foreach($series_color as $key => $hexrgb)
{
    $rgb = str_split($hexrgb,2);
    $Test->setColorPalette($key,hexdec($rgb[0]),hexdec($rgb[1]),hexdec($rgb[2]));  
}
 
// Draw the pie chart   
$Test->setFontProperties(config_get('charts_font_path'),config_get('charts_font_size'));
$Test->AntialiasQuality = 0;
$Test->drawBasicPieGraph($graph->data,$graph->description,
                         $pChartCfg->centerX,$pChartCfg->centerY,$pChartCfg->radius,PIE_PERCENTAGE,255,255,218);   
$Test->drawPieLegend($pChartCfg->legendX,$pChartCfg->legendY,$graph->data,$graph->description,250,250,250);                                
$Test->Stroke();


/**
 * 
 *
 */
function checkRights(&$db,&$user)
{
	return $user->hasRight($db,'testplan_metrics');
}


/**
 * 
 *
 */
function init_args()
{
    $_REQUEST = strings_stripSlashes($_REQUEST);
    $args = new stdClass();
    $args->tplan_id = $_REQUEST['tplan_id'];
    $args->tproject_id = $_SESSION['testprojectID'];
    return $args;
}
?>