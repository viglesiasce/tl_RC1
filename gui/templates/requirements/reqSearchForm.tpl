{* 
TestLink Open Source Project - http://testlink.sourceforge.net/
$Id: reqSearchForm.tpl,v 1.3 2010/07/07 08:46:24 mx-julian Exp $
Purpose: show form for requirement search.

rev:
  20100707 - Julian - BUGID 3584: replaced cf names by cf labels
  20100323 - asimon - added searching for req relation types (BUGID 1748)
*}

{assign var="cfg_section" value=$smarty.template|basename|replace:".tpl":"" }
{config_load file="input_dimensions.conf" section=$cfg_section}

{lang_get var="labels" 
          s='caption_search_form, custom_field, search_type_like,
             custom_field_value,btn_find,requirement_document_id, req_expected_coverage,
             title_search_req, reqid, reqversion, caption_search_form_req, title, scope,
             coverage, status, type, version, th_tcid, has_relation_type'}


{include file="inc_head.tpl"}
<body>

<h1 class="title">{$gui->mainCaption|escape}</h1>

<div style="margin: 1px;">
<form method="post" action="lib/requirements/reqSearch.php" target="workframe">
	<table class="smallGrey" style="width:100%">
		<caption>{$labels.caption_search_form_req}</caption>
		<tr>
			<td>{$labels.requirement_document_id}</td>
			<td><input type="text" name="requirement_document_id" size="{#REQDOCID_SIZE#}" maxlength="{#REQDOCID_MAXLEN#}" /></td>
		</tr>
		
		<tr>
			<td>{$labels.version}</td>
			<td><input type="text" name="version" 
			           size="{#VERSION_SIZE#}" maxlength="{#VERSION_MAXLEN#}" /></td>
		</tr>
		
		<tr>
			<td>{$labels.title}</td>
			<td><input type="text" name="name" size="{#REQNAME_SIZE#}" maxlength="{#REQNAME_MAXLEN#}" /></td>
		</tr>
		
		<tr>
			<td>{$labels.scope}</td>
			<td><input type="text" name="scope" 
			           size="{#SCOPE_SIZE#}" maxlength="{#SCOPE_MAXLEN#}" /></td>
		</tr>
		
		<tr>
			<td>{$labels.status}</td>
     		<td><select name="reqStatus">
     		<option value="nostatus">&nbsp;</option>
  			{html_options options=$gui->reqStatus}
  			</select></td>
  		</tr>
		
		<tr>
			<td>{$labels.type}</td>
			<td>
				<select name="reqType" id="reqType">
					<option value="notype">&nbsp;</option>
  					{html_options options=$gui->types}
  				</select>
  			</td>
		</tr>
	
		{if $gui->filter_by.expected_coverage}
			<tr>
				<td>{$labels.req_expected_coverage}</td>
				<td><input type="text" name="coverage" size="{#COVERAGE_SIZE#}" maxlength="{#COVERAGE_MAXLEN#}" /></td>
			</tr>
		{/if}		
		
		{if $gui->filter_by.relation_type}
			<tr>
				<td>{$labels.has_relation_type}</td>
				<td>
					<select id="relation_type" name="relation_type" />
						<option value="notype">&nbsp;</option>
						{html_options options=$gui->req_relation_select.items}
					</select>
				</td>				
			</tr>
		{/if}
		
		{if $gui->filter_by.design_scope_custom_fields}
		    <tr>
   	    	<td>{$labels.custom_field}</td>
		    	<td><select name="custom_field_id">
		    			<option value="0">&nbsp;</option>
		    			{foreach from=$gui->design_cf key=cf_id item=cf}
		    				<option value="{$cf_id}">{$cf.label|escape}</option>
		    			{/foreach}
		    		</select>
		    	</td>
	      	</tr>
		    <tr>
	       		<td>{$labels.custom_field_value}</td>
         		<td>
		    		<input type="text" name="custom_field_value" 
		    	         size="{#CFVALUE_SIZE#}" maxlength="{#CFVALUE_MAXLEN#}"/>
		    	</td>
	      </tr>
	  {/if}
	  
		<tr>
			<td>{$labels.th_tcid}</td>
			<td><input type="text" name="tcid" value="{$gui->tcasePrefix}" 
			           size="{#TC_ID_SIZE#}" maxlength="{#TC_ID_MAXLEN#}" /></td>
		</tr>
		
	  		
		
  			      
	</table>
	
	<p style="padding-left: 20px;">
		
		<input type="submit" name="doSearch" value="{$labels.btn_find}" />
	</p>
</form>

</div>
</body>
</html>
