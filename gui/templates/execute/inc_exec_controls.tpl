{* 
TestLink Open Source Project - http://testlink.sourceforge.net/
$Id: inc_exec_controls.tpl,v 1.14 2010/06/24 17:25:53 asimon83 Exp $
Purpose: draw execution controls (input for notes and results)
Author : franciscom

Rev: 
  BUGID 3479: Bulk Execution - Custom Fields Bulk Assignment
*}	
      {assign var="ResultsStatusCode" value=$tlCfg->results.status_code}
      {if $args_save_type == 'bulk'}
        {assign var="radio_id_prefix" value="bulk_status"}
      {else}
        {assign var="radio_id_prefix" value="status"}
      {/if}

  		<table class="invisible">
  		<tr>
  			<td style="text-align: center;">
  				<div class="title">{$args_labels.test_exec_notes}</div>
          {$args_webeditor} 
  			</td>
  			<td valign="top" style="width: 30%;">			
    				{* status of test *}
      			<div class="title" style="text-align: center;">{$args_labels.test_exec_result}</div>
    				
    				<div class="resultBox">
			Command<br />
			<input type="text" name="collect_command_{$args_tcversion_id}" id="collect_command_{$args_tcversion_id}" size="16"  maxlength="255" value="show version" onkeypress="return disableEnterKey(event)"><br />
                        IP<br />
			<input type="text" name="collect_ip_{$args_tcversion_id}" id="collect_ip_{$args_tcversion_id}" size="16"  maxlength="255" value="" onkeypress="return disableEnterKey(event)"><br />
			<input type="submit" name="save_and_collect[{$args_tcversion_id}]"
                                                    {$args_input_enable_mgmt}
                        onclick="document.getElementById('save_button_clicked').value={$args_tcversion_id};return checkCollectForStatus(document.getElementById('collect_ip_{$args_tcversion_id}').value, {$args_tcversion_id}, document.getElementById('collect_command_{$args_tcversion_id}').value)"  value="Collect Info" />
                        <HR>

                {foreach key=verbose_status item=locale_status from=$tlCfg->results.status_label_for_exec_ui}
    						      <input type="radio" {$args_input_enable_mgmt} name="{$radio_id_prefix}[{$args_tcversion_id}]" 
    						      id="{$radio_id_prefix}_{$args_tcversion_id}_{$ResultsStatusCode.$verbose_status}" 
    							    value="{$ResultsStatusCode.$verbose_status}"
    						      {if $args_save_type == 'bulk'}
            							onclick="javascript:set_combo_group('execSetResults','status_','{$ResultsStatusCode.$verbose_status}');"
    						      {/if}
    							    {if $verbose_status eq $tlCfg->results.default_status}
    							        checked="checked" 
    							    {/if} /> &nbsp;{lang_get s=$locale_status}<br />
    					  {/foreach}		
    					  <br />		
    		 			
    		 			  {if $args_save_type == 'single'}
			    <input type="submit" name="save_results[{$args_tcversion_id}]" 
    		 			            {$args_input_enable_mgmt}
                          onclick="document.getElementById('save_button_clicked').value={$args_tcversion_id};return checkSubmitForStatus('{$ResultsStatusCode.not_run}')"
    		 			            value="{$args_labels.btn_save_tc_exec_results}" />
    		 			         
    		 			      <input type="submit" name="save_and_next[{$args_tcversion_id}]" 
    		 			            {$args_input_enable_mgmt}
                          onclick="document.getElementById('save_button_clicked').value={$args_tcversion_id};return checkSubmitForStatus('{$ResultsStatusCode.not_run}')"
    		 			            value="{$args_labels.btn_save_exec_and_movetonext}" /><br />
    		 			  {else}
     	    	        <input type="submit" id="do_bulk_save" name="do_bulk_save"
      	    	             value="{$args_labels.btn_save_tc_exec_results}"/>

    		 			  {/if}       
    				</div>
    			</td>
    		</tr>
        {if $args_save_type == 'bulk' && $args_execution_time_cfields != ''}
          <tr><td colspan="2">
  					<div id="cfields_exec_time_tcversionid_{$tcversion_id}" class="custom_field_container" 
  						style="background-color:#dddddd;">
            {$args_labels.testcase_customfields}
            {$args_execution_time_cfields.0} {* 0 => bulk *}
            </div> 
          </td></tr>
        {/if}
  		</table>
      <div class="messages" style="align:center;">
      {$labels.exec_not_run_result_note}
      </div>
