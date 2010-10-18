{* 
TestLink Open Source Project - http://testlink.sourceforge.net/
$Id: tcAssignedToUser.tpl,v 1.19 2010/10/08 11:15:26 asimon83 Exp $
Purpose: smarty template - view test case in test specification
rev:
20101008 - asimon - BUGID 3311
20101004 - asimon - added checkbox to enable displaying of closed builds
20100825 - eloff - remove redundant headers
20100731 - asimon - replaced table (changed to ExtJS format) and included some more data
20100722 - asimon - BUGID 3406 - added columns for build ID and testsuite
20100708 - Julian - BUGID 3591 - Column priority added
20100708 - franciscom - BUGID 3575
20100326 - amitkhullar - BUGID 3345
20080322 - franciscom - php errors clean up
*}

{include file="inc_head.tpl" openHead='yes'}

{foreach from=$gui->tableSet key=idx item=matrix name="initializer"}
	{assign var=tableID value=table_$idx}
	{if $smarty.foreach.initializer.first}
		{$matrix->renderCommonGlobals()}
		{include file="inc_ext_js.tpl" bResetEXTCss=1}
		{include file="inc_ext_table.tpl"}
	{/if}
	{$matrix->renderHeadSection($tableID)}
{/foreach}

</head>

{assign var=this_template_dir value=$smarty.template|dirname}
{lang_get var='labels' 
          s='no_records_found,testplan,testcase,version,assigned_on,due_since,platform,goto_testspec,priority,
             high_priority,medium_priority,low_priority,build,testsuite,generated_by_TestLink_on,show_closed_builds_btn'}

{* 20101008 - asimon - BUGID 3311 *}
<body onUnload="storeWindowSize('AssignmentOverview')">
<h1 class="title">{$gui->pageTitle}</h1>
<div class="workBack">

{if $gui->warning_msg == ''}
	{if $gui->resultSet}
		<p><form method="post">
		<input type="checkbox" name="show_closed_builds" value="show_closed_builds"
			   {if $gui->show_closed_builds} checked="checked" {/if}
			   onclick="this.form.submit();" /> {$labels.show_closed_builds_btn}
		<input type="hidden"
			   name="show_closed_builds_hidden"
			   value="{$gui->show_closed_builds}" />
		</form></p><br />

		{foreach from=$gui->tableSet key=idx item=matrix}
		
			<p>
			{assign var=tableID value=table_$idx}
			{$matrix->renderBodySection($tableID)}
			<br /></p>
		
		{/foreach}
		
		<br />
		{$labels.generated_by_TestLink_on} {$smarty.now|date_format:$gsmarty_timestamp_format}
    {else}
        	{$labels.no_records_found}
    {/if}
{else}
    {$gui->warning_msg}
{/if}   
</div>
</body>
</html>
