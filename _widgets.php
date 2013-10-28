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
		
		if (($w->homeonly === 1 && $coreUrl->type !== 'default') ||
			($w->homeonly === 2 && $coreUrl->type === 'default')) {
			return;
		}
		$content_only = $w->content_only;
		$wclass = $w->class;
		$wtitle = $w->title;
		
		$class = $title = $divB = $divE = '';
		
		if ( $wclass ) {
			$class = html::escapeHTML($wclass);
		}
		if ( $wtitle ) {
			$title = '<h2>'.html::escapeHTML($wtitle).'</h2>';
		}
		if ( !$content_only) {
			$divB = '<div class="acronyms '.$class.'">';
			$divE = '</div>';
		}
		return	$divB.$title.'<ul><li><strong><a href="'.$core->blog->url.
				$coreUrl->getBase("acronyms").'">'.	__('List of Acronyms').
				'</a></strong></li></ul>'.$divE;
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
	}
}
