<?php

require_once("../../../../../config/dmsDefaults.php");
require_once("$default->fileSystemRoot/lib/documentmanagement/Document.inc");
require_once("$default->fileSystemRoot/lib/documentmanagement/DocumentTransaction.inc");
require_once("$default->fileSystemRoot/lib/documentmanagement/PhysicalDocumentManager.inc");
require_once("$default->fileSystemRoot/lib/visualpatterns/PatternMainPage.inc");
require_once("$default->fileSystemRoot/lib/visualpatterns/PatternCustom.inc");
require_once("$default->fileSystemRoot/lib/visualpatterns/PatternTableSqlQuery.inc");
require_once("$default->uiDirectory/documentmanagement/documentUI.inc");
require_once("$default->fileSystemRoot/presentation/lookAndFeel/knowledgeTree/foldermanagement/folderUI.inc");
require_once("restoreDeletedDocumentsUI.inc");
require_once("$default->fileSystemRoot/presentation/Html.inc");
/**
 * $Id$
 *
 * Business logic for restoring deleted documents.
 *
 * Copyright (c) 2003 Jam Warehouse http://www.jamwarehouse.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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
 * @version $Revision$
 * @author Michael Joseph <michael@jamwarehouse.com>, Jam Warehouse (Pty) Ltd, South Africa
 * @package presentation.lookAndFeel.knowledgeTree.administration.documentmanagement
 */

if (checkSession()) {	
	global $default;
	
    $oContent = new PatternCustom();
    
    if ($fDocumentID && $fFolderID) {
		if (isset($fForMove)) {
	    	$oDocument = Document::get($fDocumentID);
	    	$oFolder = Folder::get($fFolderID);
	    	if ($oDocument && $oFolder) {
				if ($fConfirmed) {
    				require_once("$default->fileSystemRoot/presentation/webpageTemplate.inc");					
					// restore the document
					$oDocument->setStatusID(LIVE);
					$oDocument->setFolderID($oFolder->getID());
					
					// first try moving the document on the filesystem
					if (PhysicalDocumentManager::restore($oDocument)) {
						// now update the db
						if ($oDocument->update(true)) {
							// display confirmation page
							$oContent->setHtml(renderStatusPage($oDocument));
						} else {
							$default->log->error("restoreDeletedDocumentBL.php couldn't update db for " . arrayToString($oDocument));
							// TODO: display error
							$oContent->setHtml(renderErrorPage("The document could not be restored.  Please try again later"));
						}
					} else {
						$default->log->error("restoreDeletedDocumentBL.php filesystem restore failed for " . arrayToString($oDocument));
						// TODO: display error
						$oContent->setHtml(renderErrorPage("The document could not be restored.  Please try again later"));
					}
				} else {
					require_once("$default->fileSystemRoot/presentation/webpageTemplate.inc");					
			    	if (!Document::documentExists($oDocument->getFileName(), $fFolderID)) {
						$oContent->setHtml(renderConfirmationPage($fDocumentID, $fFolderID));
		    		} else {
		    			// there is already a document with that filename here
		    			$oContent->setHtml(statusPage("Restore Deleted Document", "", "A document with this file name (" . $oDocument->getFileName() . ") already exists in that folder.", "restoreDeletedDocument", "fDocumentID=$fDocumentID&fFolderID=$fFolderID"));
		    		}
				}
			} else {
	    		// no document
	    		$default->log->error("restoreDeletedDocumentBL.php documentID=$fDocumentID folderID=$fFolderID instantiation failed");
	    		// TODO: redirect to list page with error
	    		controllerRedirect("deletedDocuments", "");
			}
    	} else {
    		require_once("$default->fileSystemRoot/presentation/webpageTemplate.inc");
			// display browse page
			$oContent->setHtml(renderFolderBrowsePage($fDocumentID, $fFolderID));
			$main->setFormAction($_SERVER["PHP_SELF"] . "?fForMove=1&fDocumentID=$fDocumentID&fFolderID=$fFolderID");
    	}
    } else { 
    	// no document
    	$default->log->error("restoreDeletedDocumentBL.php no document ID supplied");
    	// TODO: redirect to list page with error
    	controllerRedirect("deletedDocuments", "");
    }
    	
	$main->setCentralPayload($oContent);
	if ($main->getFormAction() == "") {
		$main->setFormAction($_SERVER["PHP_SELF"]);
	}
	$main->render();
}
?>