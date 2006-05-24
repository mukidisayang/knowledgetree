<?php

/**
 * $Id$
 *
 * Copyright (c) 2006 Jam Warehouse http://www.jamwarehouse.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; using version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * -------------------------------------------------------------------------
 *
 * You can contact the copyright owner regarding licensing via the contact
 * details that can be found on the KnowledgeTree web site:
 *
 *         http://www.ktdms.com/
 */

require_once(KT_LIB_DIR . '/plugins/pluginregistry.inc.php');
require_once(KT_LIB_DIR . '/plugins/plugin.inc.php');

class KTCoreLanguagePlugin extends KTPlugin {
    var $bAlwaysInclude = true;
    var $sNamespace = "ktcore.language.plugin";
    var $iOrder = -75;
    var $sFriendlyName = null;

    function KTCoreLanguagePlugin($sFilename = null) {
        $res = parent::KTPlugin($sFilename);
        $this->sFriendlyName = _kt('Core Language Support');
        return $res;
    }    

    function setup() {
        $this->registeri18n('knowledgeTree', KT_DIR . '/i18n');
        $this->registeri18nLang('knowledgeTree', "en", "default");
        $this->registerLanguage('en', 'English (United States)');
        $this->registerHelpLanguage('ktcore', 'en', sprintf("%s/kthelp/ktcore/EN/", KT_DIR));
    }
}

$oRegistry =& KTPluginRegistry::getSingleton();
$oRegistry->registerPlugin('KTCoreLanguagePlugin', 'ktcore.language.plugin', __FILE__);
