<?php /* Smarty version 2.6.26, created on 2010-10-18 12:40:39
         compiled from testcases/inc_tcbody.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'testcases/inc_tcbody.tpl', 13, false),array('function', 'localize_timestamp', 'testcases/inc_tcbody.tpl', 26, false),)), $this); ?>
<table class="simple">
  <?php if ($this->_tpl_vars['inc_tcbody_show_title'] == 'yes'): ?>
	<tr>
		<th colspan="<?php echo $this->_tpl_vars['inc_tcbody_tableColspan']; ?>
">
		<?php echo $this->_tpl_vars['inc_tcbody_testcase']['tc_external_id']; ?>
<?php echo @TITLE_SEP; ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['inc_tcbody_testcase']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</th>
	</tr>
  <?php endif; ?>

	  <tr>
	  	<td class="bold" colspan="<?php echo $this->_tpl_vars['inc_tcbody_tableColspan']; ?>
"><?php echo $this->_tpl_vars['inc_tcbody_labels']['version']; ?>

	  	<?php echo ((is_array($_tmp=$this->_tpl_vars['inc_tcbody_testcase']['version'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

	  	</td>
	  </tr>
	  
	<?php if ($this->_tpl_vars['inc_tcbody_author_userinfo'] != ''): ?>  
	<tr class="time_stamp_creation">
  		<td colspan="<?php echo $this->_tpl_vars['inc_tcbody_tableColspan']; ?>
">
      		<?php echo $this->_tpl_vars['inc_tcbody_labels']['title_created']; ?>
&nbsp;<?php echo localize_timestamp_smarty(array('ts' => $this->_tpl_vars['inc_tcbody_testcase']['creation_ts']), $this);?>
&nbsp;
      		<?php echo $this->_tpl_vars['inc_tcbody_labels']['by']; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['inc_tcbody_author_userinfo']->getDisplayName())) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

  		</td>
  </tr>
  <?php endif; ?>
  
 <?php if ($this->_tpl_vars['inc_tcbody_testcase']['updater_id'] != ''): ?>
	<tr class="time_stamp_creation">
  		<td colspan="<?php echo $this->_tpl_vars['inc_tcbody_tableColspan']; ?>
">
    		<?php echo $this->_tpl_vars['inc_tcbody_labels']['title_last_mod']; ?>
&nbsp;<?php echo localize_timestamp_smarty(array('ts' => $this->_tpl_vars['inc_tcbody_testcase']['modification_ts']), $this);?>

		  	&nbsp;<?php echo $this->_tpl_vars['inc_tcbody_labels']['by']; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['inc_tcbody_updater_userinfo']->getDisplayName())) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

    	</td>
  </tr>
 <?php endif; ?>

	<tr>
		<td class="bold" colspan="<?php echo $this->_tpl_vars['inc_tcbody_tableColspan']; ?>
"><?php echo $this->_tpl_vars['inc_tcbody_labels']['summary']; ?>
</td>
	</tr>
	<tr>
		<td colspan="<?php echo $this->_tpl_vars['inc_tcbody_tableColspan']; ?>
"><?php echo $this->_tpl_vars['inc_tcbody_testcase']['summary']; ?>
</td>
	</tr>

	<tr>
		<td class="bold" colspan="<?php echo $this->_tpl_vars['inc_tcbody_tableColspan']; ?>
"><?php echo $this->_tpl_vars['inc_tcbody_labels']['preconditions']; ?>
</td>
	</tr>
	<tr>
		<td colspan="<?php echo $this->_tpl_vars['inc_tcbody_tableColspan']; ?>
"><?php echo $this->_tpl_vars['inc_tcbody_testcase']['preconditions']; ?>
</td>
	</tr>

		<?php if ($this->_tpl_vars['inc_tcbody_cf']['before_steps_results'] != ''): ?>
	<tr>
	  <td>
    <?php echo $this->_tpl_vars['inc_tcbody_cf']['before_steps_results']; ?>

    </td>
	</tr>
	<?php endif; ?>
<?php if ($this->_tpl_vars['inc_tcbody_close_table']): ?>	
</table>
<?php endif; ?>