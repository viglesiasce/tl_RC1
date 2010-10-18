{* 
TestLink Open Source Project - http://testlink.sourceforge.net/
$Id: inc_btn_reqSpecView.tpl,v 1.19 2010/10/07 12:15:37 asimon83 Exp $

rev:
     20101007 - asimon - BUGID 3866 - removed analyse button
     20100326 - asimon - removed print button
     20090321 - franciscom 
     20080925 - franciscom - child requirements/folder management 
     20080924 - franciscom - if req spec has no requirements then disable certain features
*}
{lang_get var='labels'
          s='btn_req_create,btn_new_req_spec,btn_export_req_spec,
             req_select_create_tc,btn_import_req_spec,btn_import_reqs,
             btn_export_reqs,btn_edit_spec,btn_delete_spec,
             btn_show_direct_link,btn_copy_requirements,btn_copy_req_spec'}
             
{assign var="cfg_section" value=$smarty.template|basename|replace:".tpl":"" }
{config_load file="input_dimensions.conf" section=$cfg_section}

<!--- inc_btn_reqSpecView.tpl -->
<div class="groupBtn">
  	<form id="req_spec" name="req_spec" action="{$req_module}reqSpecEdit.php" method="post">
  	<div style="margin-bottom: 5px;">
  		<input type="hidden" name="req_spec_id" value="{$gui->req_spec_id}" />
  		<input type="hidden" name="doAction" value="" />
  	
  		{if $gui->grants->req_mgmt == "yes"}
            	{if $tlCfg->req_cfg->child_requirements_mgmt == $smarty.const.ENABLED}
  	        <input type="button" name="btn_new_req_spec" 
  	               value="{$labels.btn_new_req_spec}"
		               onclick="location='{$req_spec_new_url}'" />  
        	{/if}
  		<input type="submit" name="edit_req_spec"  value="{$labels.btn_edit_spec}" 
  	         onclick="doAction.value='edit'"/>
  		<input type="button" name="deleteSRS" value="{$labels.btn_delete_spec}"
  	       onclick="delete_confirmation({$gui->req_spec.id},'{$gui->req_spec.title|escape:'javascript'|escape}',
                                        '{$del_msgbox_title}','{$warning_msg}');"	/>

		    <input type="button" name="importReqSpec" value="{$labels.btn_import_req_spec}"
		           onclick="location='{$req_spec_import_url}'" />
 		    <input type="button" name="exportReq" value="{$labels.btn_export_req_spec}"
		           onclick="location='{$req_spec_export_url}'" />

  		{/if}

	</div>
	<div>
  		{if $gui->grants->req_mgmt == "yes"}
	  	  <input type="button" name="create_req" value="{$labels.btn_req_create}"
		           onclick="location='{$req_edit_url}'" />  
		    <input type="button" name="importReq" value="{$labels.btn_import_reqs}"
		           onclick="location='{$req_import_url}'" />
 		    <input type="button" name="exportReq" value="{$labels.btn_export_reqs}"
		           onclick="location='{$req_export_url}'" />

	      {if $gui->requirements_count > 0}
  		  	      <input type="button" name="create_tcases" value="{$labels.req_select_create_tc}"
		                   onclick="location='{$req_create_tc_url}'" />
        
  		  	      <input type="button" name="copy_req" value="{$labels.btn_copy_requirements}"
		                   onclick="location='{$req_spec_copy_req_url}'" />
    	  {/if}    
	  	{/if}

	</div>
  </form>
</div>