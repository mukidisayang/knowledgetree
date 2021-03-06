<?php
/*
 * $Id: $
 *
 * The contents of this file are subject to the KnowledgeTree
 * Commercial Editions On-Premise License ("License");
 * You may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.knowledgetree.com/about/legal/
 * The terms of this license may change from time to time and the latest
 * license will be published from time to time at the above Internet address.
 *
 * This edition of the KnowledgeTree software
 * is NOT licensed to you under Open Source terms.
 * You may not redistribute this source code.
 * For more information please see the License above.
 *
 * (c) 2008 KnowledgeTree Inc.
 * Portions copyright The Jam Warehouse Software (Pty) Ltd;
 * All Rights Reserved.
 *
 */

$kt_dir = $_REQUEST['kt_dir'];
require_once($kt_dir.'/config/dmsDefaults.php');

class DocumentPreview {
    var $_oDocument;
    var $_IDocId;
    var $_iMimeId;
    var $_oFolder;
    var $_iFolderId;

    /**
     * Constructer - creates the document object
     *
     * @param int $iDocumentId The document Id
     * @return
     */
    function DocumentPreview($iId, $type = 'document'){
        if($type == 'folder'){
            // $type should never be a folder.
            $this->_oDocument = false;
            return;
        }
        $oDocument = Document::get($iId);

        if(PEAR::isError($oDocument)){
            $this->_oDocument = false;
            return;
        }

        $this->_oDocument = $oDocument;
        $this->_IDocId = $iId;
        $this->_iMimeId = $oDocument->getMimeTypeID();
        $this->imageMimeTypes = array(10, 27, 37, 38, 39, 71);
    }

    /**
     * Get the document title for the preview
     *
     * @return string The document title and mime icon
     */
    function getTitle(){
        if($this->_oDocument === false){
            return '<b>'._kt('Error').'</b>';
        }
        GLOBAL $default;
        $sIcon = '';

        $sTitle = htmlentities($this->_oDocument->getName(), ENT_NOQUOTES, 'utf-8');
        $iLen = strlen($sTitle);

        if($iLen > 60){
            $sFull = $sTitle;
            if($iLen >= 99){
                $sTitle = substr($sTitle, 0, 97).'...';
            }
            $sTitle = '<h4 title="'.$sFull.'">'.$sTitle.'</h4>';
        }else{
            $sTitle = '<h2>'.$sTitle.'</h2>';
        }

        // Get the icon
        $sIcon = $this->getMimeIcon();

        $sTitle = '<div class="previewhd">
                <div style="float:left">'.$sIcon.'</div>
                <div style="float:left; width: 375px;">'.$sTitle.'</div>
            </div>';
        return $sTitle;
    }

    /**
     * Display the mime type icon.
     *
     * @param unknown_type $iMimeId
     * @return unknown
     */
    function getMimeIcon() {
        global $default;
        $iMimeId = $this->_iMimeId;

        $sIconPath = $this->getIconPath();
        $sIconPath = $default->rootUrl.$sIconPath;
        return "<img src='$sIconPath' title='$sTitle' />&nbsp;&nbsp;";
    }

    /**
     * If there isn't an icon for the given extension, find a generic icon for the type else return the default icon.
     *
     * @param string $ext
     * @return string
     */
    function checkForGeneric($ext) {
        if(in_array($ext, array('py','php'))){
            return 'generic/source';
        }
        if(in_array($ext, array('odt','sxw', 'ott', 'sxt'))){
            return 'generic/wordprocessing';
        }
        if(in_array($ext, array('ods','ots', 'sxc', 'stc'))){
            return 'spreadsheet';
        }
        if(in_array($ext, array('odp','otp', 'sxi', 'sti'))){
            return 'generic/pres';
        }
        if(in_array($ext, array('mp3','m4a'))){
            return 'generic/sound';
        }
        if(in_array($ext, array('m4v'))){
            return 'generic/video';
        }
        return 'default';
    }

    /**
     * Get the path to the correct icon for the mime type
     *
     * @return string
     */
    function getIconPath() {

        $sIconPath = KTMime::getIconPath($this->_iMimeId);

        // Get mime type icon
        $sIconPath = '/resources/mimetypes/big/'.$sIconPath.'.png';

        if(!file_exists(KT_DIR.$sIconPath)){
            // See if there is an icon for the extension
            $sMimeType = KTMime::getMimeTypeName($this->_iMimeId);
            $aMimeInfo = KTMime::getFriendlyNameAndExtension($sMimeType);
            if(!PEAR::isError($aMimeInfo) && !empty($aMimeInfo)){
                $sExt = $aMimeInfo[0]['filetypes'];
                $sIconPath = '/resources/mimetypes/big/'.$sExt.'.png';

                if(!file_exists(KT_DIR.$sIconPath)){
                    $generic = $this->checkForGeneric($sExt);
                    // if all else fails, use the default icon
                    $sIconPath = '/resources/mimetypes/big/'.$generic.'.png';
                }
            }
        }
        return $sIconPath;
    }

    /**
     * Render the info box content
     *
     * @return string
     */
    function renderPreview(){
        if($this->_oDocument === false){
            return '<p>'._kt('A problem occured while loading the property preview.').'</p>';
        }

        $sInfo = $this->getMetadata();

        return '<div id="preview" class="preview" onclick="javascript: destroyPanel();">'.$sInfo.'</div>';
    }

    /**
     * Create a table of the document metadata.
     * Hard coded for the moment
     *
     * @return unknown
     */
    function getMetadata(){
        /* Get document info */

        // Filename
        $sFilenameLb = _kt('Document Filename: ');
        $sFilename = $this->_oDocument->getFileName();

        // Mime type
        $sMimeTypeLb = _kt('File is a: ');
        $iMimeId = $this->_oDocument->getMimeTypeID();
        $sMimeType = KTMime::getMimeTypeName($iMimeId);
        $sMimeType = KTMime::getFriendlyNameForString($sMimeType);

        // Version
        $sVersionLb = _kt('Document Version: ');
        $iVersion = $this->_oDocument->getVersion();

        // Created by
        $sCreatedByLb = _kt('Created by: ');
        $iCreatorId = $this->_oDocument->getCreatorID();
        $sCreated = $this->_oDocument->getCreatedDateTime();
        $oCreator = User::get($iCreatorId);
        $sCreatedBy = $oCreator->getName().' ('.$sCreated.')';

        // Owned by
        $sOwnedByLb =  _kt('Owned by: ');
        $iOwnedId = $this->_oDocument->getOwnerID();
        $oOwner = User::get($iOwnedId);
        $sOwnedBy = $oOwner->getName();

        // Last update by
        $iModifiedId = $this->_oDocument->getModifiedUserId();
        $sLastUpdatedByLb = ''; $sLastUpdatedBy = '';
        if(!empty($iModifiedId)){
            $sLastUpdatedByLb = _kt('Last updated by: ');
            $sModified = $this->_oDocument->getLastModifiedDate();
            $oModifier = User::get($iModifiedId);
            $sLastUpdatedBy = $oModifier->getName().' ('.$sModified.')';
        }

        // Document type
        $sDocTypeLb = _kt('Document Type: ');
        $iDocTypeId = $this->_oDocument->getDocumentTypeID();
        $oDocType = DocumentType::get($iDocTypeId);
        $sDocType = $oDocType->getName();

        // Workflow
        $iWFId = $this->_oDocument->getWorkflowId();
        $sWF = ''; $sWFLb = '';
        if(!empty($iWFId)){
            $sWFLb = _kt('Workflow: ');
            $iWFStateId = $this->_oDocument->getWorkflowStateId();
            $oWF = KTWorkflow::get($iWFId);
            $sWF = $oWF->getHumanName();
            $oWFState = KTWorkflowState::get($iWFStateId);
            $sWF .= ' ('.$oWFState->getHumanName().')';
        }

        // Checked out by
        $sCheckedLb = ''; $sCheckedOutBy = '';
        if($this->_oDocument->getIsCheckedOut()){
            $sCheckedLb = _kt('Checked out by: ');
            $iCheckedID = $this->_oDocument->getCheckedOutUserID();
            $oCheckedUser = User::get($iCheckedID);
            $sCheckedOutBy = $oCheckedUser->getName();
        }

        // Id
        $sIdLb = _kt('Document ID: ');
        $sId = $this->_IDocId;

        /* Create table */

        $sInfo = "<table cellspacing='3px' cellpadding='3px'>
            <tr><td>{$sFilenameLb}</td><td><b>{$sFilename}</b></td></tr>
            <tr><td>{$sMimeTypeLb}</td><td><b>{$sMimeType}</b></td></tr>
            <tr><td>{$sVersionLb}</td><td><b>{$iVersion}</b></td></tr>
            <tr><td>{$sCreatedByLb}</td><td><b>{$sCreatedBy}</b></td></tr>
            <tr><td>{$sOwnedByLb}</td><td><b>{$sOwnedBy}</b></td></tr>";

        if(!empty($sLastUpdatedBy)){
            $sInfo .= "<tr><td>{$sLastUpdatedByLb}</td><td><b>{$sLastUpdatedBy}</b></td></tr>";
        }
            $sInfo .= "<tr><td>{$sDocTypeLb}</td><td><b>{$sDocType}</b></td></tr>";
        if(!empty($sWF)){
            $sInfo .= "<tr><td>{$sWFLb}</td><td><b>{$sWF}</b></td></tr>";
        }
        if(!empty($sCheckedOutBy)){
            $sInfo .= "<tr><td>{$sCheckedLb}</td><td><b>{$sCheckedOutBy}</b></td></tr>";
        }

            $sInfo .= "<tr><td>{$sIdLb}</td><td><b>{$sId}</b></td></tr>
        </table>";

        return $sInfo;
    }
}

/**
 * Get the document id and render the preview / info box
 */

$iDocumentId = $_REQUEST['fDocumentId'];

$oPreview = new DocumentPreview($iDocumentId);

$sTitle = $oPreview->getTitle();
$sContent = $oPreview->renderPreview();

echo $sTitle.'<br />'.$sContent;
exit;
?>
