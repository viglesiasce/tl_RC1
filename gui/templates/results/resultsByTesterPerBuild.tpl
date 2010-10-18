{* 
 * TestLink Open Source Project - http://testlink.sourceforge.net/ 
 * $Id: resultsByTesterPerBuild.tpl,v 1.2 2010/09/20 14:10:37 mx-julian Exp $
 *
 * Lists results and progress by tester per build in a grouping ExtJS table.
 * 
 * revisions:
 * 20100731 - asimon - initial commit
 *
 *}
 
{lang_get var="labels"
         s="generated_by_TestLink_on,hlp_results_by_tester_per_build_table"}

{include file="inc_head.tpl" openHead="yes"}

{foreach from=$gui->tableSet key=idx item=matrix name="initializer"}
	{assign var=tableID value=table_$idx}
	{if $smarty.foreach.initializer.first}
		{$matrix->renderCommonGlobals()}
		{if $matrix instanceof tlExtTable}
			{include file="inc_ext_js.tpl" bResetEXTCss=1}
			{include file="inc_ext_table.tpl"}
		{/if}
	{/if}
	{$matrix->renderHeadSection($tableID)}
{/foreach}

</head>
<body>
<h1 class="title">{$gui->pageTitle|escape}</h1>
<div class="workBack" style="overflow-y: auto;">

{include file="inc_result_tproject_tplan.tpl" 
         arg_tproject_name=$gui->tproject_name arg_tplan_name=$gui->tplan_name}

{foreach from=$gui->tableSet key=idx item=matrix}
	{assign var=tableID value=table_$idx}
   	{$matrix->renderBodySection($tableID)}
{/foreach}

<br/>
<p>{$labels.hlp_results_by_tester_per_build_table}</p>
<br /><br />
{$labels.generated_by_TestLink_on} {$smarty.now|date_format:$gsmarty_timestamp_format}

</div>
</body>
</html>