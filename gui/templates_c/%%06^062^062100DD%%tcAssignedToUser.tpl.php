<?php /* Smarty version 2.6.26, created on 2010-10-18 11:09:34
         compiled from testcases/tcAssignedToUser.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'dirname', 'testcases/tcAssignedToUser.tpl', 31, false),array('modifier', 'date_format', 'testcases/tcAssignedToUser.tpl', 62, false),array('function', 'lang_get', 'testcases/tcAssignedToUser.tpl', 32, false),)), $this); ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_head.tpl", 'smarty_include_vars' => array('openHead' => 'yes')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_from = $this->_tpl_vars['gui']->tableSet; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['initializer'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['initializer']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['idx'] => $this->_tpl_vars['matrix']):
        $this->_foreach['initializer']['iteration']++;
?>
	<?php $this->assign('tableID', "table_".($this->_tpl_vars['idx'])); ?>
	<?php if (($this->_foreach['initializer']['iteration'] <= 1)): ?>
		<?php echo $this->_tpl_vars['matrix']->renderCommonGlobals(); ?>

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
	<?php echo $this->_tpl_vars['matrix']->renderHeadSection($this->_tpl_vars['tableID']); ?>

<?php endforeach; endif; unset($_from); ?>

</head>

<?php $this->assign('this_template_dir', ((is_array($_tmp='testcases/tcAssignedToUser.tpl')) ? $this->_run_mod_handler('dirname', true, $_tmp) : dirname($_tmp))); ?>
<?php echo lang_get_smarty(array('var' => 'labels','s' => 'no_records_found,testplan,testcase,version,assigned_on,due_since,platform,goto_testspec,priority,
             high_priority,medium_priority,low_priority,build,testsuite,generated_by_TestLink_on,show_closed_builds_btn'), $this);?>


<body onUnload="storeWindowSize('AssignmentOverview')">
<h1 class="title"><?php echo $this->_tpl_vars['gui']->pageTitle; ?>
</h1>
<div class="workBack">

<?php if ($this->_tpl_vars['gui']->warning_msg == ''): ?>
	<?php if ($this->_tpl_vars['gui']->resultSet): ?>
		<p><form method="post">
		<input type="checkbox" name="show_closed_builds" value="show_closed_builds"
			   <?php if ($this->_tpl_vars['gui']->show_closed_builds): ?> checked="checked" <?php endif; ?>
			   onclick="this.form.submit();" /> <?php echo $this->_tpl_vars['labels']['show_closed_builds_btn']; ?>

		<input type="hidden"
			   name="show_closed_builds_hidden"
			   value="<?php echo $this->_tpl_vars['gui']->show_closed_builds; ?>
" />
		</form></p><br />

		<?php $_from = $this->_tpl_vars['gui']->tableSet; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['idx'] => $this->_tpl_vars['matrix']):
?>
		
			<p>
			<?php $this->assign('tableID', "table_".($this->_tpl_vars['idx'])); ?>
			<?php echo $this->_tpl_vars['matrix']->renderBodySection($this->_tpl_vars['tableID']); ?>

			<br /></p>
		
		<?php endforeach; endif; unset($_from); ?>
		
		<br />
		<?php echo $this->_tpl_vars['labels']['generated_by_TestLink_on']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, $this->_tpl_vars['gsmarty_timestamp_format']) : smarty_modifier_date_format($_tmp, $this->_tpl_vars['gsmarty_timestamp_format'])); ?>

    <?php else: ?>
        	<?php echo $this->_tpl_vars['labels']['no_records_found']; ?>

    <?php endif; ?>
<?php else: ?>
    <?php echo $this->_tpl_vars['gui']->warning_msg; ?>

<?php endif; ?>   
</div>
</body>
</html>