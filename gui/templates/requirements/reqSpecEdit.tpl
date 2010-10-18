{*
TestLink Open Source Project - http://testlink.sourceforge.net/
$Id: reqSpecEdit.tpl,v 1.23 2010/10/06 10:26:22 asimon83 Exp $
Purpose: smarty template - create a new req document

rev:
  20101006 - asimon - BUGID 3854
  20100810 - asimon - BUGID 3317: disabled total count of requirements by default
  20100808 - asimon - added logic to refresh filtered tree on changes
  20091230 - franciscom - req spec type
*}
{* ------------------------------------------------------------------------- *}

{lang_get var="labels"
          s='warning,warning_empty_req_spec_title,title,scope,req_total,type,
             doc_id,cancel,show_event_history,warning_empty_doc_id,warning_countreq_numeric'}
{assign var="cfg_section" value=$smarty.template|basename|replace:".tpl":"" }
{config_load file="input_dimensions.conf" section=$cfg_section}

{include file="inc_head.tpl" openHead="yes" jsValidate="yes" editorType=$gui->editorType}
{include file="inc_del_onclick.tpl"}

<script type="text/javascript">
	var alert_box_title = "{$labels.warning}";
	var warning_empty_req_spec_title = "{$labels.warning_empty_req_spec_title}";
	var warning_empty_doc_id = "{$labels.warning_empty_doc_id}";
	var warning_countreq_numeric = "{$labels.warning_countreq_numeric}";
	{literal}
	function validateForm(f)
	{
   
		if (isWhitespace(f.doc_id.value)) 
  	{
    	alert_message(alert_box_title,warning_empty_doc_id);
			selectField(f, 'doc_id');
			return false;
		}

		if (isWhitespace(f.title.value))
		{
			alert_message(alert_box_title,warning_empty_req_spec_title);
			selectField(f,'title');
			return false;
		}

		{/literal}
		{if $gui->external_req_management}
		{literal}
		if (isNaN(parseInt(f.countReq.value)))
		{
			alert_message(alert_box_title,warning_countreq_numeric);
			selectField(f,'countReq');
			return false;
		}
		{/literal}
		{/if}
		{literal}
		
		return true;
	}
	{/literal}
	</script>
</head>

<body>
<h1 class="title">
	{if $gui->action_descr != ''}{$gui->action_descr|escape}{/if} {$gui->main_descr|escape}
	{include file="inc_help.tpl" helptopic="hlp_requirementsCoverage" show_help_icon=true}
</h1>

{include file="inc_update.tpl" user_feedback=$gui->user_feedback}

<div class="workBack">
	<form name="reqSpecEdit" id="reqSpecEdit" method="post" onSubmit="javascript:return validateForm(this);">
	    <input type="hidden" name="req_spec_id" value="{$gui->req_spec_id}" />

  	<div class="labelHolder"><label for="doc_id">{$labels.doc_id}</label>
  	</div>
	  <div><input type="text" name="doc_id" id="doc_id"
  		        size="{#REQSPEC_DOCID_SIZE#}" maxlength="{#REQSPEC_DOCID_MAXLEN#}"
  		        value="{$gui->req_spec_doc_id|escape}" />
  				{include file="error_icon.tpl" field="doc_id"}
  	</div>
	
		<div class="labelHolder"><label for="req_spec_title">{$labels.title}</label>
	   		{if $mgt_view_events eq "yes" and $gui->req_spec_id}
				<img style="margin-left:5px;" class="clickable" src="{$smarty.const.TL_THEME_IMG_DIR}/question.gif" 
				     onclick="showEventHistoryFor('{$gui->req_spec_id}','req_specs')" 
				     alt="{$labels.show_event_history}" title="{$labels.show_event_history}"/>
			{/if}
	   	</div>
	   	<div>
		    <input type="text" id="title" name="title"
		           size="{#REQ_SPEC_TITLE_SIZE#}"
				   maxlength="{#REQ_SPEC_TITLE_MAXLEN#}"
		           value="{$gui->req_spec_title|escape}" />
		  	{include file="error_icon.tpl" field="req_spec_title"}
	   	</div>
	   	<br />
		<div class="labelHolder">
			<label for="scope">{$labels.scope}</label>
		</div>
		<div>
			{$gui->scope}
	   	</div>
	   	
	   	{if $gui->external_req_management}
	   	<br />
	   	<div class="labelHolder"><label for="countReq">{$labels.req_total}</label>
			<input type="text" id="countReq" name="countReq" size="{#REQ_COUNTER_SIZE#}" 
			      maxlength="{#REQ_COUNTER_MAXLEN#}" value="{$gui->total_req_counter}" />
		</div>
		{/if}
		
	  <br />
		
  	<div class="labelHolder"> <label for="reqSpecType">{$labels.type}</label>
     	<select name="reqSpecType">
  			{html_options options=$gui->reqSpecTypeDomain selected=$gui->req_spec.type}
  		</select>
  	</div>

		
	    <br />
		{if $gui->cfields neq ""}
			<div class="custom_field_container">
		    	{$gui->cfields}
		    </div>
		<br />
		{/if}

		{* BUGID 3854 *}
		<div class="groupBtn">
			<input type="hidden" name="doAction" value="" />
			<input type="submit" name="createSRS" value="{$gui->submit_button_label}"
		       onclick="doAction.value='{$gui->operation}';parent.frames['treeframe'].document.forms['filter_panel_form'].submit();" />
			<input type="button" name="go_back" value="{$labels.cancel}" 
				onclick="javascript: history.back();"/>
		</div>
	</form>
</div>

<script type="text/javascript" defer="1">
   	document.forms[0].doc_id.focus()
</script>

{if isset($gui->refreshTree) && $gui->refreshTree}
	{include file="inc_refreshTreeWithFilters.tpl"}
{/if}

</body>
</html>