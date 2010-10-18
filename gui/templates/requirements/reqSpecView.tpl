{* TestLink Open Source Project - http://testlink.sourceforge.net/ *}
{* $Id: reqSpecView.tpl,v 1.43 2010/10/08 11:15:27 asimon83 Exp $ *}
{*
   Purpose: view a requirement specification
   Author: Martin Havlat

   rev:
        20101008 - asimon - BUGID 3311
        20101006 - asimon - BUGID 3854
        20100810 - asimon - BUGID 3317: disabled total count of requirements by default
        20100321 - franciscom - req_spec_import/export url
        20071226 - franciscom - fieldset class added (thanks ext je team)
        20071106 - franciscom - added ext js library
        20070102 - franciscom - added javascript validation of checked requirements
*}
{lang_get var="labels" s="type_not_configured,type,scope,req_total,by,title,
							            title_last_mod,title_created,no_records_found"}

{assign var="cfg_section" value=$smarty.template|basename|replace:".tpl":"" }
{config_load file="input_dimensions.conf" section=$cfg_section}

{assign var="bn" value=$smarty.template|basename}
{assign var="buttons_template" value=$smarty.template|replace:"$bn":"inc_btn_$bn"}

{assign var="reqSpecID" value=$gui->req_spec_id}
{assign var="req_module" value='lib/requirements/'}
{assign var="url_args" value="reqEdit.php?doAction=create&amp;req_spec_id="}
{assign var="req_edit_url" value="$basehref$req_module$url_args$reqSpecID"}

{assign var="url_args" value="reqImport.php?req_spec_id="}
{assign var="req_import_url"  value="$basehref$req_module$url_args$reqSpecID"}

{assign var="url_args" value="reqExport.php?req_spec_id="}
{assign var="req_export_url"  value="$basehref$req_module$url_args$reqSpecID"}

{assign var="url_args" value="reqImport.php?scope=branch&req_spec_id="}
{assign var="req_spec_import_url"  value="$basehref$req_module$url_args$reqSpecID"}

{assign var="url_args" value="reqExport.php?scope=branch&req_spec_id="}
{assign var="req_spec_export_url"  value="$basehref$req_module$url_args$reqSpecID"}

{assign var="url_args" value="reqEdit.php?doAction=reorder&amp;req_spec_id="}
{assign var="req_reorder_url"  value="$basehref$req_module$url_args$reqSpecID"}

{assign var="url_args" value="reqEdit.php?doAction=createTestCases&amp;req_spec_id="}
{assign var="req_create_tc_url"  value="$basehref$req_module$url_args$reqSpecID"}

{assign var="url_args" value="reqSpecEdit.php?doAction=createChild&amp;reqParentID="}
{assign var="req_spec_new_url" value="$basehref$req_module$url_args$reqSpecID"}

{assign var="url_args" value="reqSpecEdit.php?doAction=copyRequirements&amp;req_spec_id="}
{assign var="req_spec_copy_req_url" value="$basehref$req_module$url_args$reqSpecID"}

{assign var="url_args" value="reqSpecEdit.php?doAction=copy&amp;req_spec_id="}
{assign var="req_spec_copy_url" value="$basehref$req_module$url_args$reqSpecID"}


{* used on inc_btn_reqSpecView.tpl *}
{lang_get s='warning_delete_req_spec' var="warning_msg" }
{lang_get s='delete' var="del_msgbox_title" }

{include file="inc_head.tpl" openHead="yes" jsValidate="yes"}
{include file="inc_del_onclick.tpl"}

<script type="text/javascript">
	/* All this stuff is needed for logic contained in inc_del_onclick.tpl */
	var del_action=fRoot+'{$req_module}reqSpecEdit.php?doAction=doDelete&req_spec_id=';
</script>
</head>

{* 20101008 - asimon - BUGID 3311 *}
<body {$body_onload} onUnload="storeWindowSize('ReqSpecPopup')" >
<h1 class="title">
  {if isset($gui->direct_link)}
  {$toggle_direct_link_img} &nbsp;
  {/if}
	{$gui->main_descr|escape}
	{if $gui->req_spec.id}
	{include file="inc_help.tpl" helptopic="hlp_requirementsCoverage" show_help_icon=true}
	{/if}
</h1>

<div class="workBack">
   {if isset($gui->direct_link)}
   <div class="direct_link" style='display:none'><a href="{$gui->direct_link}" target="_blank">{$gui->direct_link}</a></div>
   {/if}
{* contribution by asimon *}
{if $gui->req_spec.id}
{* end contribution by asimon *}
	
{include file="$buttons_template"}
<table class="simple" style="width: 90%">
	<tr>
		<th>{$gui->main_descr|escape}</th>
	</tr>
	<tr>
	  <td>{$labels.type}{$smarty.const.TITLE_SEP}
	  {assign var="req_spec_type" value=$gui->req_spec.type}
	  {if isset($gui->reqSpecTypeDomain.$req_spec_type)}
	    {$gui->reqSpecTypeDomain.$req_spec_type}
	  {else}
	    {$labels.type_not_configured}  
	  {/if}
	  </td>
	</tr>
	<tr>
		<td>
			<fieldset class="x-fieldset x-form-label-left"><legend class="legend_container">{$labels.scope}</legend>
			{$gui->req_spec.scope}
			</fieldset>
		</td>
	</tr>
  {if $gui->external_req_management && $gui->req_spec.total_req != 0}
  	<tr>
  		<td>{$labels.req_total}{$smarty.const.TITLE_SEP}{$gui->req_spec.total_req}</td>
   	</tr>
  {/if}
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
  			{$gui->cfields}
  		</td>
	</tr>
	<tr class="time_stamp_creation">
		<td colspan="2">
	    	  {$labels.title_created}&nbsp;{localize_timestamp ts=$gui->req_spec.creation_ts}&nbsp;
	      	{$labels.by}&nbsp;{$gui->req_spec.author|escape}
	  	</td>
	 </tr>
  {if $gui->req_spec.modifier != ""}
    <tr class="time_stamp_creation">
    	<td colspan="2">
    		{$labels.title_last_mod}&nbsp;{localize_timestamp ts=$gui->req_spec.modification_ts}
		  	&nbsp;{$labels.by}&nbsp;{$gui->req_spec.modifier|escape}
    	</td>
    </tr>
  {/if}
</table>

{assign var="bDownloadOnly" value=true}
{if $gui->grants->req_mgmt == 'yes'}
	{assign var="bDownloadOnly" value=false}
{/if}
{include file="inc_attachments.tpl" 
         attach_id=$gui->req_spec.id  
         attach_tableName="req_specs"
         attach_attachmentInfos=$gui->attachments  
         attach_downloadOnly=$bDownloadOnly}

{else}
	{$labels.no_records_found}
{/if}

</div>
{if isset($gui->refreshTree) && $gui->refreshTree}
   {include file="inc_refreshTreeWithFilters.tpl"}
{/if}
</body>
</html>
