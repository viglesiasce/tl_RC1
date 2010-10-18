<?php /* Smarty version 2.6.26, created on 2010-10-18 12:21:54
         compiled from results/resultsBugs.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang_get', 'results/resultsBugs.tpl', 8, false),array('modifier', 'escape', 'results/resultsBugs.tpl', 30, false),array('modifier', 'date_format', 'results/resultsBugs.tpl', 71, false),)), $this); ?>
<?php echo lang_get_smarty(array('var' => 'labels','s' => 'title,date,printed_by,bugs_open,
		         title_test_suite_name,title_test_case_title,
		         title_test_case_bugs, info_bugs_per_tc_report,
             generated_by_TestLink_on,bugs_resolved,bugs_total,tcs_with_bugs'), $this);?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_head.tpl", 'smarty_include_vars' => array()));
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
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_ext_js.tpl", 'smarty_include_vars' => array('bResetEXTCss' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_ext_table.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
  <?php endif; ?>
  <?php echo $this->_tpl_vars['matrix']->renderHeadSection(); ?>

<?php endforeach; endif; unset($_from); ?>

<body>

<?php if ($this->_tpl_vars['gui']->printDate == ''): ?>
<h1 class="title"><?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->title)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h1>

<?php else: ?><table style="font-size: larger;font-weight: bold;">
	<tr><td><?php echo $this->_tpl_vars['labels']['title']; ?>
</td><td><?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->title)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td><tr>
	<tr><td><?php echo $this->_tpl_vars['labels']['date']; ?>
</td><td><?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->printDate)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td><tr>
	<tr><td><?php echo $this->_tpl_vars['labels']['printed_by']; ?>
</td><td><?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->user->getDisplayName())) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td><tr>
</table>
<?php endif; ?>

<div class="workBack">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_result_tproject_tplan.tpl", 'smarty_include_vars' => array('arg_tproject_name' => $this->_tpl_vars['gui']->tproject_name,'arg_tplan_name' => $this->_tpl_vars['gui']->tplan_name)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>	

<?php if ($this->_tpl_vars['gui']->warning_msg == ''): ?>
	<table class="simple" style="width: 100%; text-align: center; margin-left: 0px;">
	     <tr>
	         <th><?php echo $this->_tpl_vars['labels']['bugs_open']; ?>
</th>
	         <th><?php echo $this->_tpl_vars['labels']['bugs_resolved']; ?>
</th>
	         <th><?php echo $this->_tpl_vars['labels']['bugs_total']; ?>
</th>
	         <th><?php echo $this->_tpl_vars['labels']['tcs_with_bugs']; ?>
</th>
	     </tr>
	     
	     <tr>
	         <td><?php echo $this->_tpl_vars['gui']->totalOpenBugs; ?>
</td>
	         <td><?php echo $this->_tpl_vars['gui']->totalResolvedBugs; ?>
</td>
	         <td><?php echo $this->_tpl_vars['gui']->totalBugs; ?>
</td>
	         <td><?php echo $this->_tpl_vars['gui']->totalCasesWithBugs; ?>
</td>
	     </tr>
	</table>
	
	<br />
	
	<?php $_from = $this->_tpl_vars['gui']->tableSet; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['idx'] => $this->_tpl_vars['matrix']):
?>
		<?php $this->assign('tableID', "table_".($this->_tpl_vars['idx'])); ?>
   		<?php echo $this->_tpl_vars['matrix']->renderBodySection($this->_tpl_vars['tableID']); ?>

	<?php endforeach; endif; unset($_from); ?>
	
	<br />
	<p class="italic"><?php echo $this->_tpl_vars['labels']['info_bugs_per_tc_report']; ?>
</p>
	<br />
	<?php echo $this->_tpl_vars['labels']['generated_by_TestLink_on']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, $this->_tpl_vars['gsmarty_timestamp_format']) : smarty_modifier_date_format($_tmp, $this->_tpl_vars['gsmarty_timestamp_format'])); ?>

<?php else: ?>
	<div class="user_feedback">
    <?php echo $this->_tpl_vars['gui']->warning_msg; ?>

    </div>
<?php endif; ?>
</div>

</body>
</html>