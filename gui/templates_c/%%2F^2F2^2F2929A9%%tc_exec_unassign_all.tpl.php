<?php /* Smarty version 2.6.26, created on 2010-10-18 12:37:27
         compiled from plan/tc_exec_unassign_all.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang_get', 'plan/tc_exec_unassign_all.tpl', 10, false),array('modifier', 'escape', 'plan/tc_exec_unassign_all.tpl', 50, false),)), $this); ?>

<?php echo lang_get_smarty(array('var' => 'labels','s' => 'btn_remove_all_tester_assignments'), $this);?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_head.tpl", 'smarty_include_vars' => array('openHead' => 'yes')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $this->assign('ext_location', @TL_EXTJS_RELATIVE_PATH); ?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['basehref']; ?>
<?php echo $this->_tpl_vars['ext_location']; ?>
/css/ext-all.css" />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_del_onclick.tpl", 'smarty_include_vars' => array('openHead' => 'yes')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
<?php echo '

/**
 * submit the form to confirm deletion of all tester assignments
 *
 *
 */
function remove_testers(btn) {
	if (btn == "yes") {
		document.getElementById("delete_tc_exec_assignments").submit();
	}
}

/**
 * open popup message to ask for user\'s confirmation before deleting assignments
 *
 * 
 */
function warn_remove_testers(msgbox_title, msgbox_content) {
	Ext.Msg.confirm(msgbox_title, msgbox_content, function(btn) {
		remove_testers(btn);
	});
}					

'; ?>

</script>

</head>

<body>

<h1 class="title"><?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->title)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h1>

<div class="workBack">

<?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->message)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>


<?php if ($this->_tpl_vars['gui']->draw_tc_unassign_button): ?>
	<div class="groupBtn">
		<form id='delete_tc_exec_assignments' name='delete_tc_exec_assignments' method='post'>
			<input type="hidden" name="build_id" value="<?php echo $this->_tpl_vars['gui']->build_id; ?>
" />
			<input type="hidden" name="confirmed" value="yes" />
			<input type="button" 
			       name="remove_all_tester_assignments"
			       value="<?php echo lang_get_smarty(array('s' => 'btn_remove_all_tester_assignments'), $this);?>
"
			       onclick="javascript: warn_remove_testers('<?php echo $this->_tpl_vars['gui']->popup_title; ?>
', 
			                                                '<?php echo $this->_tpl_vars['gui']->popup_message; ?>
');" />
		</form>
	</div> <!-- groupBtn -->
<?php endif; ?>

<?php if ($this->_tpl_vars['gui']->refreshTree): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_refreshTreeWithFilters.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

</div> <!-- workback -->
  
</body>
</html>