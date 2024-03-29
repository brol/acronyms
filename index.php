<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of acronyms, a plugin for DotClear2.
#
# Copyright (c) 2008 Vincent Garnier and contributors
# Licensed under the GPL version 2.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------
if (!defined('DC_CONTEXT_ADMIN')) { exit; }

$page_title = __('Acronyms Manager');

# Url de base
$p_url = 'plugin.php?p=acronyms';

$acronyms = new dcAcronyms($core);
$acronyms_list = $acronyms->getList();

$a_acro = '';
$a_title = '';

# modfication des parametres 
if (!empty($_POST['p_param']))
{
		$ns = $core->blog->settings->addNamespace('acronyms');
		$ns->put('acronyms_button_enabled',!empty($_POST['acronyms_button_enabled']),'boolean');
		$ns->put('acronyms_public_enabled',!empty($_POST['acronyms_public_enabled']),'boolean');
		$core->blog->settings->addNamespace('acronyms');
		http::redirect($p_url.'&param=1');
}
# modfication de la liste des acronymes
if (!empty($_POST['p_edit']))
{
	$p_acronyms = !empty($_POST['p_acronyms']) && is_array($_POST['p_acronyms']) ? array_map('trim', $_POST['p_acronyms']) : array();

	$acronyms_list = array();
	foreach ($p_acronyms as $nk=>$nv)
	{
		if ($nv != '') {
			$acronyms_list[$nk] = $nv;
		}
		else {
			unset($acronyms_list[$nk]);
		}
	}
	ksort($acronyms_list);

	$acronyms->writeFile($acronyms_list);
	http::redirect($p_url.'&edited=1');
}

# ajout d'un acronyme
if (!empty($_POST['p_add']))
{
	try
	{
		$a_acro = !empty($_POST['a_acro']) ? trim($_POST['a_acro']) : '';
		$a_title = !empty($_POST['a_title']) ? trim($_POST['a_title']) : '';

		if ($a_acro == '') {
			throw new Exception(__('You must give an acronym'));
		}

		if ($a_title == '') {
			throw new Exception(__('You must give a title'));
		}

		if (isset($acronyms_list[$a_acro])) {
			throw new Exception(__('This acronym already exists'));
		}

		$acronyms_list[$a_acro] = $a_title;
		ksort($acronyms_list);

		$acronyms->writeFile($acronyms_list);
		http::redirect($p_url.'&added=1');
	}
	catch (Exception $e)
	{
		$core->error->add($e->getMessage());
	}
}
$ns = $core->blog->settings->addNamespace('acronyms');
$ret = $ns->get('acronyms_button_enabled');
$button_enabled = ($ret == null || $ret === false ? false : true);
$ret = $ns->get('acronyms_public_enabled');
$public_enabled =  ($ret == null || $ret === false ? false : true);

?>
<html>
<head>
	<title><?php echo $page_title; ?></title>
	<style type="text/css">
	#edit_acronyms {margin-top:3.5em;}
	#add_acronyms div { position:relative; }
	.acroleft { display:block; width:14em; }
	.acroright { display:inline; left:15em; position:absolute; top:43px; }
	#listacro { height:200px; overflow:auto; }
	</style>
	<?php echo dcPage::jsModal(); ?>
	<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$('#post-preview').modalWeb($(window).width()-40,$(window).height()-40);
	});
	//]]>
	</script>
</head>
<body>
<?php

	echo dcPage::breadcrumb(
		array(
			html::escapeHTML($core->blog->name) => '',
			'<span class="page-title">'.$page_title.'</span>' => ''
		));

if (!empty($_GET['param'])) {
  dcPage::success(__('Acronyms parameters successfully updated.'));
}
if (!empty($_GET['edited'])) {
  dcPage::success(__('Acronyms list successfully updated.'));
}
if (!empty($_GET['added'])) {
  dcPage::success(__('Acronym successfully added.'));
}
?>
<form id="param_acronyms" action="plugin.php" method="post">
		<div class="fieldset"><h4><?php echo __('Activation')?></h4>
		<p><label class="classic">
		<?php 
echo form::checkbox('acronyms_button_enabled','1',  $button_enabled);  
		echo __('Enable acronyms button on toolbar')?></label></p>
		<p><label class="classic">
		<?php 
		echo form::checkbox('acronyms_public_enabled','1', $public_enabled) ;
		echo __('Enable acronyms public page')?></label></p>
		</div>
	<p class="clear"><?php echo form::hidden('p_param', '1');
	echo form::hidden(array('p'),'acronyms');
	echo $core->formNonce(); ?>
	<input type="submit" class="submit" value="<?php echo __('Save'); ?>" />
	</p>
</form>

<?php
if ($core->blog->settings->acronyms->acronyms_public_enabled) {
	echo '<p><a class="onblog_link outgoing" href="'.$core->blog->url.$core->url->getBase('acronyms').'" title="'.__('View the acronyms page').'">'.__('View the acronyms page').' <img src="images/outgoing-blue.png" alt="" /></a></p>';
}
?>
	
<form id="edit_acronyms" action="plugin.php" method="post">
	<div class="fieldset">
		<h4><?php echo __('Edit acronyms'); ?></h4>
		<div id="listacro">
		<?php
		$i = 1;
		foreach ($acronyms_list as $k=>$v)
		{
			echo
			'<p class="field">'."\n".
			'<label for="acronym_'.$i.'"><acronym title="'.$v.'">'.html::escapeHTML($k).'</acronym></label>'."\n".
			form::field(['p_acronyms['.$k.']','acronym_'.$i],60,'200',html::escapeHTML($v))."\n".
			'</p>'."\n\n";

			++$i;
		}
		?>
		</div><!-- #listacro -->
	</div>
	<p class="clear"><?php echo form::hidden('p_edit', '1');
	echo form::hidden(array('p'),'acronyms');
	echo $core->formNonce(); ?>
	<input type="submit" class="submit" value="<?php echo __('Edit'); ?>" />
	</p>
</form>

<form id="add_acronyms" action="plugin.php" method="post">
	<div class="fieldset">
		<h4><?php echo __('Add an acronym'); ?></h4>

		<p class="acroleft"><label for="a_acro" class="required"><abbr title="'.__('Required field').'">*</abbr> <?php echo __('Acronym'); ?></label>
		<?php echo form::field('a_acro',10,'200',$a_acro,'',''); ?></p>

		<p class="acroright"><label for="a_title" class="required"><abbr title="'.__('Required field').'">*</abbr> <?php echo __('Entitled'); ?></label>
		<?php echo form::field('a_title',60,'200',$a_title,'',''); ?></p>

	</div>
	<p class="clear"><?php echo form::hidden('p_add', '1');
	echo form::hidden(array('p'),'acronyms');
	echo $core->formNonce(); ?>
	<input type="submit" class="submit" value="<?php echo __('Add'); ?>" /></p>
</form>
<?php dcPage::helpBlock('acronyms'); ?>
</body>
</html>
