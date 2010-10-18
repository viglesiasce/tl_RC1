<?php /* Smarty version 2.6.26, created on 2010-10-18 12:44:22
         compiled from events/eventviewer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'basename', 'events/eventviewer.tpl', 10, false),array('modifier', 'replace', 'events/eventviewer.tpl', 10, false),array('modifier', 'escape', 'events/eventviewer.tpl', 35, false),array('modifier', 'date_format', 'events/eventviewer.tpl', 174, false),array('function', 'config_load', 'events/eventviewer.tpl', 11, false),array('function', 'lang_get', 'events/eventviewer.tpl', 13, false),)), $this); ?>
<?php $this->assign('cfg_section', ((is_array($_tmp=((is_array($_tmp='events/eventviewer.tpl')) ? $this->_run_mod_handler('basename', true, $_tmp) : basename($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, ".tpl", "") : smarty_modifier_replace($_tmp, ".tpl", ""))); ?>
<?php echo smarty_function_config_load(array('file' => "input_dimensions.conf",'section' => $this->_tpl_vars['cfg_section']), $this);?>


<?php echo lang_get_smarty(array('var' => 'labels','s' => 'event_viewer,th_loglevel,th_timestamp,th_description,title_eventdetails,
             title_eventinfo,label_startdate,label_enddate,btn_apply,click_on_event_info,
             btn_clear_events,btn_clear_all_events,th_event_description,select_user,clear_tip,
             message_please_wait,btn_close,th_role_description,th_user,generated_by_TestLink_on'), $this);?>



<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_head.tpl", 'smarty_include_vars' => array('openHead' => 'yes','jsValidate' => 'yes')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_ext_js.tpl", 'smarty_include_vars' => array('bResetEXTCss' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_from = $this->_tpl_vars['gui']->tableSet; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['initializer'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['initializer']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['idx'] => $this->_tpl_vars['matrix']):
        $this->_foreach['initializer']['iteration']++;
?>
  <?php $this->assign('tableID', $this->_tpl_vars['matrix']->tableID); ?>
  <?php if (($this->_foreach['initializer']['iteration'] <= 1)): ?>
    <?php echo $this->_tpl_vars['matrix']->renderCommonGlobals(); ?>

    <?php if ($this->_tpl_vars['matrix'] instanceof tlExtTable): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_ext_table.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
  <?php endif; ?>
  <?php echo $this->_tpl_vars['matrix']->renderHeadSection(); ?>

<?php endforeach; endif; unset($_from); ?>

<script type="text/javascript">
var strPleaseWait = "<?php echo ((is_array($_tmp=$this->_tpl_vars['labels']['message_please_wait'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
var strCloseButton = "<?php echo ((is_array($_tmp=$this->_tpl_vars['labels']['btn_close'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
<?php echo '
var progressBar = null;

function showEventDetails(id)
{
	progressBar = Ext.Msg.wait(strPleaseWait);
	Ext.Ajax.request(
				{
					url : \'lib/events/eventinfo.php\' ,
					params : { id : id },
					method: \'POST\',
					success: function(result, request)
							 {
								showDetailWindow(result.responseText);
							 },
					failure: function (result, request)
						{
							if (progressBar)
							{
								progressBar.hide();
							}	
						}
				}
			);
}
var infoWin;
function showDetailWindow(info)
{
	var item = document.getElementById(\'eventDetails\');
	item.innerHTML = info;
	if (progressBar)
	{
		progressBar.hide();
	}
	if(!infoWin)
	{
		infoWin = new Ext.Window({
					el:\'eventDetailWindow\',
					modal:true,
					autoTabs: true,
					layout:\'fit\',
					width:700,
					height:500,
					items: new Ext.TabPanel({
						el: \'detailTabs\',
						autoTabs:true,
						activeTab:0,
						deferredRender:false,
						border:false
					}),
					closeAction:\'hide\',
					plain: true,
					buttons: [{
						text: strCloseButton,
						handler: function(){
							infoWin.hide();
						}
					}]
			});
	}
	infoWin.show();
}
</script>
<style type="text/css">
fieldset
{
	height:100%;

}
</style>
'; ?>


</head>
<body <?php echo $this->_tpl_vars['body_onload']; ?>
>
<h1 class="title"><?php echo $this->_tpl_vars['labels']['event_viewer']; ?>
</h1>

<div class="workBack">
		<form method="post" action="lib/events/eventviewer.php">
			<input type="hidden" name="object_id" value="<?php echo $this->_tpl_vars['gui']->object_id; ?>
" />
			<input type="hidden" name="object_type" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->object_type)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
			<input type="hidden" name="doAction" id="doAction" value="filter" />
			
			<div style="height:125px;">
			<fieldset class="x-fieldset" style="float:left"><legend><?php echo $this->_tpl_vars['labels']['th_loglevel']; ?>
</legend>
				<select name="logLevel[]" size="5" multiple="multiple" >
					<?php $_from = $this->_tpl_vars['gui']->logLevels; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value'] => $this->_tpl_vars['desc']):
?>
					<?php if (in_array ( ( string ) $this->_tpl_vars['value'] , $this->_tpl_vars['gui']->selectedLogLevels ) != false): ?>
						<option selected="selected" value="<?php echo $this->_tpl_vars['value']; ?>
"><?php echo $this->_tpl_vars['desc']; ?>
</option>
					<?php else: ?>
						<option value="<?php echo $this->_tpl_vars['value']; ?>
"><?php echo $this->_tpl_vars['desc']; ?>
</option>
					<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
				</select>
			</fieldset>

			<fieldset class="x-fieldset" style="float:left"><legend><?php echo $this->_tpl_vars['labels']['select_user']; ?>
</legend>
        <select name="testers[]" size="5" multiple="multiple">
        	<?php $_from = $this->_tpl_vars['gui']->testers; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row'] => $this->_tpl_vars['userid']):
?>
			      <?php if (in_array ( ( string ) $this->_tpl_vars['row'] , $this->_tpl_vars['gui']->selectedTesters ) != false): ?>
        	    <option value="<?php echo $this->_tpl_vars['row']; ?>
" selected="selected"><?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->testers[$this->_tpl_vars['row']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
        	  <?php else: ?>
        	    <option value="<?php echo $this->_tpl_vars['row']; ?>
" ><?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->testers[$this->_tpl_vars['row']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
        	  <?php endif; ?>  
        	<?php endforeach; endif; unset($_from); ?>
        </select>
			</fieldset>

			<fieldset class="x-fieldset"><legend><?php echo $this->_tpl_vars['labels']['th_timestamp']; ?>
</legend>
			<?php echo $this->_tpl_vars['labels']['label_startdate']; ?>
:&nbsp;<input type="text" name="startDate" id="startDate" value="<?php echo $this->_tpl_vars['gui']->startDate; ?>
" />
			<input type="button" style="cursor:pointer" onclick="showCal('startDate-cal','startDate');" value="^" />
			<div id="startDate-cal" style="position:absolute;width:240px;left:300px;z-index:1;"></div>
			<?php echo $this->_tpl_vars['labels']['label_enddate']; ?>
:&nbsp;<input type="text" name="endDate" id="endDate" value="<?php echo $this->_tpl_vars['gui']->endDate; ?>
" />
			<input type="button" style="cursor:pointer" onclick="showCal('endDate-cal','endDate');" value="^" />
			<div id="endDate-cal" style="position:absolute;width:240px;left:540px;z-index:1;"></div>
			<input type="submit" value="<?php echo $this->_tpl_vars['labels']['btn_apply']; ?>
" onclick="doAction.value='filter'" />
			<br />
			<?php if ($this->_tpl_vars['gui']->canDelete): ?>
			  <br />
			  <input type="submit"  value="<?php echo $this->_tpl_vars['labels']['btn_clear_events']; ?>
" onclick="doAction.value='clear'" />
			  <img src="<?php echo @TL_THEME_IMG_DIR; ?>
/sym_question.gif" title="<?php echo $this->_tpl_vars['labels']['clear_tip']; ?>
">
			<?php endif; ?>
			</fieldset>
			<br />
			</div>
		</form>
		<br/>
		<br/>
		
				
		<?php if ($this->_tpl_vars['gui']->warning_msg == ''): ?>
		    <?php $_from = $this->_tpl_vars['gui']->tableSet; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['idx'] => $this->_tpl_vars['matrix']):
?>
		       <?php $this->assign('tableID', "table_".($this->_tpl_vars['idx'])); ?>
		       <?php echo $this->_tpl_vars['matrix']->renderBodySection($this->_tpl_vars['tableID']); ?>

		    <?php endforeach; endif; unset($_from); ?>
		    <br />
		    <p class="italic"><?php echo $this->_tpl_vars['labels']['click_on_event_info']; ?>
</p>
		    <br />
		    <?php echo $this->_tpl_vars['labels']['generated_by_TestLink_on']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, $this->_tpl_vars['gsmarty_timestamp_format']) : smarty_modifier_date_format($_tmp, $this->_tpl_vars['gsmarty_timestamp_format'])); ?>

		<?php else: ?>
		    <br />
		    <div class="user_feedback">
		    <?php echo $this->_tpl_vars['gui']->warning_msg; ?>

		    </div>
		<?php endif; ?> 

</div>
<div id="eventDetailWindow" class="x-hidden">
	<div class="x-window-header"><?php echo $this->_tpl_vars['labels']['title_eventinfo']; ?>
</div>
	<div id="detailTabs">
		<div class="x-tab" title="<?php echo $this->_tpl_vars['labels']['title_eventdetails']; ?>
">
			<div id="eventDetails" class="inner-tab"></div>
		</div>
	</div>
</div>
</body>