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
  return null;
}

$this->registerModule(
    'acronyms',
    'Add, remove and modify acronyms for the wiki syntax',
    'Vincent Garnier, Pierre Van Glabeke, Bernard Le Roux',
    '1.7.7',
    [
        'requires' => [['core', '2.19']],
        'permissions' => 'usage,contentadmin',
        'type' => 'plugin',
        'support' => 'http://forum.dotclear.org/viewtopic.php?pid=323174#p323174',
        'details' => 'http://plugins.dotaddict.org/dc2/details/acronyms'
    ]
);
