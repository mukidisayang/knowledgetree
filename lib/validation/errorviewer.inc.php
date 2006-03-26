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

class KTErrorViewerRegistry {
    var $aViewers = array();

    function &getSingleton() {
        $oRegistry =& KTUtil::arrayGet($GLOBALS, 'KTErrorViewerRegistry');
        if ($oRegistry) {
            return $oRegistry;
        }
        $GLOBALS['KTErrorViewerRegistry'] =& new KTErrorViewerRegistry();
        return $GLOBALS['KTErrorViewerRegistry'];
    }

    function register($sViewerClassName, $sHandledClass) {
        $this->aViewers[strtolower($sHandledClass)] = $sViewerClassName;
    }

    function getViewer($oError) {
        $sErrorClass = strtolower(get_class($oError));

        // Try for direct hit first
        $sClass = $sErrorClass;
        $sHandlerClass = KTUtil::arrayGet($this->aViewers, $sClass);
        if ($sHandlerClass) {
            return new $sHandlerClass($oError);
        }

        // PHP 4.0.5 added get_parent_class - this offers us the
        // best/first match
        if (function_exists('get_parent_class')) {
            while ($sClass = get_parent_class($sClass)) {
                $sHandlerClass = KTUtil::arrayGet($this->aViewers, $sClass);
                if ($sHandlerClass) {
                    return new $sHandlerClass($oError);
                }
            }
        }

        // Now try things the hard way - no best/first match (ick!)
        // Reverse, since hopefully the best/first match will be added
        // after the other matches.
        foreach (array_reverse($this->aViewers) as $sHandlerClass => $sClass) {
            if (is_a($oError, $sClass)) {
                return new $sHandlerClass($oError);
            }
        }

        // Just in case we have an unhandled i18n-friendly error
        if (method_exists($oError, 'geti18nMessage')) {
            return new KTStringErrorViewer($oError->getMessage());
        }

        // PEAR_Error should have caught things above, but just in case,
        // check if getMessage is there, and use that:
        if (method_exists($oError, 'getMessage')) {
            return new KTStringErrorViewer($oError->getMessage());
        }

        // Check if we are a string
        if (is_string($oError)) {
            return new KTStringErrorViewer($oError);
        }

        // Give up.
        return new KTStringErrorViewer(_kt("Unknown error"));
    }
}

$oEVRegistry =& KTErrorViewerRegistry::getSingleton();
class KTErrorViewer {
    function KTErrorViewer($oError) {
        $this->oError = $oError;
    }
    function view() {
        return $this->oError->getMessage();
    }

    function viewFull() {
        return $this->oError->toString();
    }

    function page() {
        $ret  = "<h2>Error</h2>\n\n";
        $ret .= "<dl>\n";
        $ret .= "\t<dt>Error type</dt>\n";
        $ret .= "\t<dd>" . $this->oError->getMessage() . "</dd>\n";
        $sInfo = $this->parseUserInfo();
        if ($sInfo) {
            $ret .= "\t<dt>Additional information</dt>\n";
            $ret .= "\t<dd>" . $sInfo . "</dd>\n";
        }
        $ret .= "</dl>\n";
        return $ret;
    }

    function parseUserInfo() {
        $sUserInfo = $this->oError->getUserInfo();
        return $sUserInfo;
    }
}
$oEVRegistry->register("KTErrorViewer", "PEAR_Error");

class KTDBErrorViewer extends KTErrorViewer {
    function view() {
        return _kt("Database error:") . " " . $this->oError->getMessage();
    }

    function page() {
        $ret  = "<h2>Database Error</h2>\n\n";
        $ret .= "<dl>\n";
        $ret .= "\t<dt>Error type</dt>\n";
        $ret .= "\t<dd>" . $this->oError->getMessage() . "</dd>\n";
        $sInfo = $this->parseUserInfo();
        if ($sInfo) {
            $ret .= "\t<dt>Additional information</dt>\n";
            $ret .= "\t<dd>" . $sInfo . "</dd>\n";
        }
        $ret .= "</dl>\n";
        return $ret;
    }

    function parseUserInfo() {
        $sUserInfo = $this->oError->getUserInfo();
        $aMatches = array();
        if (preg_match("#^ ?\[nativecode=(Can't connect to local.*) \(13\)#", $sUserInfo, $aMatches)) {
            return $aMatches[1];
        }
        return $sUserInfo;
    }
}
$oEVRegistry->register("KTDBErrorViewer", "DB_Error");

class KTStringErrorViewer extends KTErrorViewer {
    function view() {
        return $this->oError;
    }
    
    function viewFull() {
        return $this->oError;
    }
}
