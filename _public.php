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
if (!defined('DC_RC_PATH')) { 
	return; 
}

require dirname(__FILE__).'/_widgets.php';

l10n::set(dirname(__FILE__).'/locales/'.$_lang.'/public');

$tpl = $core->tpl;
$tpl->addBlock('Acronyms',array('tplAcronyms','Acronyms'));
$tpl->addBlock('AcronymsHeader',array('tplAcronyms','AcronymsHeader'));
$tpl->addBlock('AcronymsFooter',array('tplAcronyms','AcronymsFooter'));
$tpl->addValue('Acronym',array('tplAcronyms','Acronym'));
$tpl->addValue('AcronymTitle',array('tplAcronyms','AcronymTitle'));
$core->addBehavior('publicBreadcrumb',array('extAcronyms','publicBreadcrumb'));

/*******************************************
 * tplAcronyms
 *******************************************/
class tplAcronyms {
	public static function Acronyms( $attr, $content) {
		return
		"<?php\n".
		'$objAcronyms = new dcAcronyms($core); '.
		'$arrayAcronyms = array(); '.
		'foreach ($objAcronyms->getList() as $acronym=>$title) {'.
		"	\$arrayAcronyms[] = array('acronym'=>\$acronym,'title'=>\$title);".
		'}'.
		'$_ctx->acronyms = staticRecord::newFromArray($arrayAcronyms); '.
		'?>'.
		'<?php while ($_ctx->acronyms->fetch()) : ?>'.$content.'<?php endwhile; '.
		'$_ctx->acronyms = null; unset($objAcronyms,$arrayAcronyms); ?>';
	}

	public static function AcronymsHeader( $attr, $content) {
		return
		"<?php if (\$_ctx->acronyms->isStart()) : ?>".
		$content.
		"<?php endif; ?>";
	}

	public static function AcronymsFooter( $attr, $content) {
		return
		"<?php if (\$_ctx->acronyms->isEnd()) : ?>".
		$content.
		"<?php endif; ?>";
	}

	public static function Acronym( $attr) {
		return '<?php echo '.
				sprintf($GLOBALS['core']->tpl->getFilters($attr),'$_ctx->acronyms->acronym').
				'; ?>';
	}

	public static function AcronymTitle( $attr) {
		return '<?php echo '.
				sprintf($GLOBALS['core']->tpl->getFilters($attr),'$_ctx->acronyms->title').
				'; ?>';
	}

} 
/*******************************************
 * acronymsURL
 *******************************************/
class acronymsURL extends dcUrlHandlers
{
        public static function acronyms($args)
        {
        	$core = $GLOBALS['core'];
			$ns = $core->blog->settings->addNamespace('acronyms');
        	if (!$ns->get('acronyms_public_enabled')) {
				self::p404();
				exit;
        	}

			$tplset = $core->themes->moduleInfo($core->blog->settings->system->theme,'tplset');
        if (!empty($tplset) && is_dir(dirname(__FILE__).'/default-templates/'.$tplset)) {
            $core->tpl->setPath($core->tpl->getPath(), dirname(__FILE__).'/default-templates/'.$tplset);
        } else {
            $core->tpl->setPath($core->tpl->getPath(), dirname(__FILE__).'/default-templates/'.DC_DEFAULT_TPLSET);
        }
			self::serveDocument('acronyms.html');
			exit;
        }

} 

class extAcronyms
{
  public static function publicBreadcrumb($context,$separator)
  {
    if ($context == 'acronyms') {
      return __('List of Acronyms');
    }
  }
}