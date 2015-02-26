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
if (!defined('DC_CONTEXT_ADMIN')) {
	return;
}

require dirname(__FILE__).'/_widgets.php';

l10n::set(dirname(__FILE__).'/locales/'.$_lang.'/main');

$_menu['Blog']->addItem(__('Acronyms Manager'),'plugin.php?p=acronyms','index.php?pf=acronyms/icon.png',
		preg_match('/plugin.php\?p=acronyms(&.*)?$/',$_SERVER['REQUEST_URI']),
		$core->auth->check('acronyms',$core->blog->id));

$core->auth->setPermissionType('acronyms',__('manage acronyms'));

$core->addBehavior('coreInitWikiPost',array('acronymsAdminBehaviors','coreInitWikiPost'));

$core->addBehavior('adminPostHeaders',array('acronymsAdminBehaviors','jsLoad'));
$core->addBehavior('adminPageHeaders',array('acronymsAdminBehaviors','jsLoad'));
$core->addBehavior('adminRelatedHeaders',array('acronymsAdminBehaviors','jsLoad'));
$core->addBehavior('adminDashboardHeaders',array('acronymsAdminBehaviors','jsLoad'));

class acronymsAdminBehaviors
{
	public static function coreInitWikiPost($wiki2xhtml)
	{
		$acronyms = new dcAcronyms($GLOBALS['core']);

		$wiki2xhtml->setOpt('acronyms_file',$acronyms->file);
		$wiki2xhtml->acro_table = $acronyms->getList();
	}

	public static function jsLoad()
	{
		$ns = $GLOBALS['core']->blog->settings->addNamespace('acronyms');
		if ($ns->get('acronyms_button_enabled')) {
			return
			'<script type="text/javascript" src="index.php?pf=acronyms/post.js"></script>'.
			'<script type="text/javascript">'."\n".
			"//<![CDATA[\n".
			dcPage::jsVar('jsToolBar.prototype.elements.acronyms.title',__('Acronym'))."\n".
			dcPage::jsVar('jsToolBar.prototype.elements.acronyms.msg_title',__('Title?'))."\n".
			dcPage::jsVar('jsToolBar.prototype.elements.acronyms.msg_lang',__('Lang?')).
			"\n//]]>\n".
			"</script>\n";
		}
		return '';
	}

} # class acronymsAdminBehaviors

$core->addBehavior('adminDashboardFavorites','acronymsDashboardFavorites');

function acronymsDashboardFavorites($core,$favs)
{
	$favs->register('acronyms', array(
		'title' => __('Acronyms'),
		'url' => 'plugin.php?p=acronyms',
		'small-icon' => 'index.php?pf=acronyms/icon.png',
		'large-icon' => 'index.php?pf=acronyms/icon-big.png',
		'permissions' => 'usage,contentadmin'
	));
}