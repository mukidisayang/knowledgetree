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

require_once(KT_DIR . '/search2/documentProcessor/documentProcessor.inc.php');
require_once(KT_DIR . '/search2/indexing/lib/XmlRpcLucene.inc.php');

/**
 * @todo Check if the trigger should be called on download
 *
 */
class pdfConverter extends BaseProcessor
{
    public $order = 2;
    protected $namespace = 'pdf.converter.processor';

    public function pdfConverter()
    {
        $config =& KTConfig::getSingleton();
		$javaServerUrl = $config->get('indexer/javaLuceneURL');

		$this->xmlrpc = XmlRpcLucene::get($javaServerUrl);
    }

    public function processDocument()
    {
        $oStorage = KTStorageManagerUtil::getSingleton();
        $path = $oStorage->temporaryFile($this->document);

        if(!file_exists($path)){
            global $default;
            $default->log->debug('Document, id: '.$this->document->iId.', does not exist at given storage path: '.$path);
            return false;
        }

        // do pdf conversion
        $res = $this->convertFile($path);

        if($res === false){
            global $default;
            $default->log->debug('Document, id: '.$this->document->iId.', could not be converted to pdf.');
            return false;
        }

        return true;
    }

    /**
     * The supported mime types for the converter.
     *
     * @return array
     */
	public function getSupportedMimeTypes()
	{
//	    $aAcceptedMimeTypes = array('doc', 'ods', 'odt', 'ott', 'txt', 'rtf', 'sxw', 'stw',
//            //                                    'html', 'htm',
//            'xml' , 'pdb', 'psw', 'ods', 'ots', 'sxc',
//            'stc', 'dif', 'dbf', 'xls', 'xlt', 'slk', 'csv', 'pxl',
//            'odp', 'otp', 'sxi', 'sti', 'ppt', 'pot', 'sxd', 'odg',
//            'otg', 'std', 'asc');

        // taken from the original list of accepted types in the pdf generator action
        $mime_types = array();
        $mime_types[] = 'text/plain';
        $mime_types[] = 'text/rtf';
        $mime_types[] = 'text/xml';
        $mime_types[] = 'text/csv';
        $mime_types[] = 'chemical/x-pdb';
        $mime_types[] = 'application/msword';
        $mime_types[] = 'application/vnd.ms-powerpoint';
        $mime_types[] = 'application/vnd.ms-excel';
        $mime_types[] = 'application/vnd.sun.xml.writer';
        $mime_types[] = 'application/vnd.sun.xml.writer.template';
        $mime_types[] = 'application/vnd.sun.xml.calc';
        $mime_types[] = 'application/vnd.sun.xml.calc.template';
        $mime_types[] = 'application/vnd.sun.xml.draw';
        $mime_types[] = 'application/vnd.sun.xml.draw.template';
        $mime_types[] = 'application/vnd.sun.xml.impress';
        $mime_types[] = 'application/vnd.sun.xml.impress.template';
        $mime_types[] = 'application/vnd.oasis.opendocument.text';
        $mime_types[] = 'application/vnd.oasis.opendocument.text-template';
        $mime_types[] = 'application/vnd.oasis.opendocument.graphics';
        $mime_types[] = 'application/vnd.oasis.opendocument.graphics-template';
        $mime_types[] = 'application/vnd.oasis.opendocument.presentation';
        $mime_types[] = 'application/vnd.oasis.opendocument.presentation-template';
        $mime_types[] = 'application/vnd.oasis.opendocument.spreadsheet';
        $mime_types[] = 'application/vnd.oasis.opendocument.spreadsheet-template';

        return $mime_types;
	}

	function convertFile($filename)
	{
	    global $default;

	    // Get contents and send to converter
        $buffer = file_get_contents($filename);
        $buffer = $this->xmlrpc->convertDocument($buffer, 'pdf');

        if($buffer === false){
            $default->log->error('PDF Converter Plugin: Conversion to PDF Failed');
            return false;
        }

        $dir = $default->pdfDirectory;

        // Ensure the PDF directory exists
        if(!file_exists($dir)){
            mkdir($dir, 0755);
        }

        $pdfFile = $dir .'/'. $this->document->iId.'.pdf';

        // if a previous version of the pdf exists - delete it
        if(file_exists($pdfFile)){
            @unlink($pdfFile);
        }

        file_put_contents($pdfFile, $buffer);
        unset($buffer);

        return $pdfFile;

    }
}
?>