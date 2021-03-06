<?php
/**
 * $Id$
 *
 * KnowledgeTree Community Edition
 * Document Management Made Simple
 * Copyright (C) 2008, 2009 KnowledgeTree Inc.
 * Portions copyright The Jam Warehouse Software (Pty) Limited
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 3 as published by the
 * Free Software Foundation.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * You can contact KnowledgeTree Inc., PO Box 7775 #87847, San Francisco, 
 * California 94120-7775, or email info@knowledgetree.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * KnowledgeTree" logo and retain the original copyright notice. If the display of the 
 * logo is not reasonably feasible for technical reasons, the Appropriate Legal Notices
 * must display the words "Powered by KnowledgeTree" and retain the original 
 * copyright notice.
 * Contributor( s): ______________________________________
 *
 */

require_once(KT_LIB_DIR . "/templating/templating.inc.php");
require_once(KT_LIB_DIR . "/dispatcher.inc.php");
require_once(KT_LIB_DIR . "/browse/Criteria.inc");
require_once(KT_LIB_DIR . "/search/savedsearch.inc.php");
require_once(KT_LIB_DIR . "/util/support.inc.php");

class KTSupportDispatcher extends KTAdminDispatcher {
    var $bAutomaticTransaction = true;
    var $sHelpPage = 'ktcore/admin/system information.html';

    function check() {
        $this->aBreadcrumbs[] = array('url' => $_SERVER['PHP_SELF'], 'name' => _kt('Support and System information'));
        return true;
    }

    function do_main() {
        $oTemplate =& $this->oValidator->validateTemplate('ktcore/support');
        $oTemplate->setData(array(
            'context' => $this,
        ));
        return $oTemplate->render();
    }

    function do_actualInfo() {

    	$supportutil = new SupportUtil();
    	$supportutil->capture();
    	$archivefile = $supportutil->archive();
    	$supportutil->cleanup();

    	$basename = basename($archivefile);

    	header("Content-Type: application/zip");
    	header("Content-Disposition: attachment; filename=\"$basename\"");
    	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    	header("Cache-Control: must-revalidate");

    	readfile($archivefile);
    	unlink($archivefile);
    	exit(0);
    }

}

?>
