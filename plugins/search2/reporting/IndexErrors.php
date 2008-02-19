<?php
/**
 * $Id:$
 *
 * KnowledgeTree Open Source Edition
 * Document Management Made Simple
 * Copyright (C) 2004 - 2008 The Jam Warehouse Software (Pty) Limited
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
 * You can contact The Jam Warehouse Software (Pty) Limited, Unit 1, Tramber Place,
 * Blake Street, Observatory, 7925 South Africa. or email info@knowledgetree.com.
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

require_once(KT_LIB_DIR . '/dispatcher.inc.php');
require_once(KT_LIB_DIR . '/templating/templating.inc.php');
require_once(KT_LIB_DIR . '/mime.inc.php');

class IndexErrorsDispatcher extends KTAdminDispatcher {

    function check() {
        $this->aBreadcrumbs[] = array(
            'url' => $_SERVER['PHP_SELF'],
            'name' => _kt('Document Indexing Diagnostics'),
        );
        return parent::check();
    }

    function do_main() {

        //registerTypes registers the mime types and populates the needed tables.
        $indexer = Indexer::get();
        $indexer->registerTypes();

        if($_REQUEST['rescheduleValue'] == 'reschedule')
        {

			foreach(KTUtil::arrayGet($_REQUEST, 'index_error', array()) as $sDocId => $v)
			{
				Indexer::reindexDocument($sDocId);

			}

        }
        else if($_REQUEST['rescheduleValue'] == 'rescheduleall')
        {
        	$aIndexerValues = Indexer::getIndexingQueue();
        	foreach ($aIndexerValues as $sDocValues)
			{
				Indexer::reindexDocument($sDocValues['document_id']);
			}

        }

        $oTemplating =& KTTemplating::getSingleton();
        $oTemplating->addLocation('Index Errors', '/plugins/search2/reporting/templates');

        $oTemplate =& $oTemplating->loadTemplate('indexerrors');

        $aIndexerValues = Indexer::getIndexingQueue();
        foreach($aIndexerValues as $key=>$doc)
        {
        	$extractor=$indexer->getExtractor($doc['extractor']);
        	$doc['extractor'] = $extractor->getDisplayName();
        	$aIndexerValues[$key] = $doc;
        }



        $oTemplate->setData(array(
            'context' => $this,
            'index_errors' => $aIndexerValues

        ));
        return $oTemplate;
    }



}


?>
