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
if (!defined('DC_RC_PATH')) { return; }

l10n::set(dirname(__FILE__).'/locales/'.$_lang.'/main');

$ns = $core->blog->settings->addNamespace('acronyms');
if (!$ns->get('acronyms_public_enabled')) {
	return;
}
unset($ns);

$core->addBehavior('initWidgets',array('widgetsAcronyms','initWidgets'));

class widgetsAcronyms
{
	# Widget function
	public static function acronymsWidgets($w)
	{
		$core = $GLOBALS['core'];
		$coreUrl = $core->url;

		if ($w->offline)
			return;
		
		if (($w->homeonly == 1 && $core->url->type != 'default') ||
			($w->homeonly == 2 && $core->url->type == 'default')) {
			return;
		}

		$res =
		($w->title ? $w->renderTitle(html::escapeHTML($w->title)) : '').
		'<p><a href="'.$core->blog->url.
		$coreUrl->getBase("acronyms").'">'.	__('List of Acronyms').
		'</a></p>';

		return $w->renderDiv($w->content_only,'acronyms '.$w->class,'',$res);
	}

	public static function initWidgets($w) {
		$w->create('acronyms',__('List of Acronyms'),array('widgetsAcronyms','acronymsWidgets'));
		$acro = $w->acronyms;
		
		$acro->setting('title',__('Title:'),__('List of Acronyms'),'text');
		$acro->setting('homeonly',__('Display on:'),0,'combo',
			array(
				__('All pages') => 0,
				__('Home page only') => 1,
				__('Except on home page') => 2
				)
		);
		$acro->setting('content_only',__('Content only'),0,'check');
		$acro->setting('class',__('CSS class:'),'');
		$acro->setting('offline',__('Offline'),0,'check');
	}
}
