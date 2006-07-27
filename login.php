<?php

// main library routines and defaults
require_once("config/dmsDefaults.php");
require_once(KT_LIB_DIR . '/templating/templating.inc.php');
require_once(KT_LIB_DIR . '/session/control.inc');
require_once(KT_LIB_DIR . '/session/Session.inc');
require_once(KT_LIB_DIR . '/users/User.inc');
require_once(KT_LIB_DIR . '/authentication/authenticationutil.inc.php');
require_once(KT_LIB_DIR . '/help/help.inc.php');
require_once(KT_LIB_DIR . '/help/helpreplacement.inc.php');

/**
 * $Id$
 *  
 * This page handles logging a user into the dms.
 * This page displays the login form, and performs the business logic login processing.
 *
 * The contents of this file are subject to the KnowledgeTree Public
 * License Version 1.1 ("License"); You may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.ktdms.com/KPL
 * 
 * Software distributed under the License is distributed on an "AS IS"
 * basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 * for the specific language governing rights and limitations under the
 * License.
 * 
 * The Original Code is: KnowledgeTree Open Source
 * 
 * The Initial Developer of the Original Code is The Jam Warehouse Software
 * (Pty) Ltd, trading as KnowledgeTree.
 * Portions created by The Jam Warehouse Software (Pty) Ltd are Copyright
 * (C) 2006 The Jam Warehouse Software (Pty) Ltd;
 * All Rights Reserved.
 *
 *
 * @version $Revision$
 * @author Michael Joseph <michael@jamwarehouse.com>, Jam Warehouse (Pty) Ltd, South Africa
 */

class LoginPageDispatcher extends KTDispatcher {

    function check() {
        $oKTConfig = KTConfig::getSingleton();
        $this->session = new Session();
        $sessionStatus = $this->session->verify();
        if ($sessionStatus === true) { // the session is valid
            if ($_SESSION['userID'] == -2 && $oKTConfig->get('allowAnonymousLogin', false)) {
                ; // that's ok - we want to login.
            }
            else {
                exit(redirect(generateControllerLink('dashboard')));
            }
        }
        return true;
    }

    function do_providerVerify() {
        $this->session = new Session();
        $sessionStatus = $this->session->verify();
        if ($sessionStatus !== true) { // the session is not valid
            $this->redirectToMain();
        }
        $this->oUser =& User::get($_SESSION['userID']);
        $oProvider =& KTAuthenticationUtil::getAuthenticationProviderForUser($this->oUser);
        $oProvider->subDispatch($this);
        exit(0);
    }

    function do_main() {
        global $default;
    
        $this->check(); // bounce here, potentially.
        header('Content-type: text/html; charset=UTF-8');
        
        $errorMessage = KTUtil::arrayGet($_REQUEST, 'errorMessage');
        $redirect = KTUtil::arrayGet($_REQUEST, 'redirect');

        $oReg =& KTi18nregistry::getSingleton();
        $aRegisteredLangs = $oReg->geti18nLanguages('knowledgeTree');
        $aLanguageNames = $oReg->getLanguages('knowledgeTree');
        $aRegisteredLanguageNames = array();
        foreach (array_keys($aRegisteredLangs) as $sLang) {
            $aRegisteredLanguageNames[$sLang] = $aLanguageNames[$sLang];
        }
        $sLanguageSelect = $default->defaultLanguage;

        // extra disclaimer, if plugin is enabled
        $oRegistry =& KTPluginRegistry::getSingleton();
        $oPlugin =& $oRegistry->getPlugin('ktstandard.disclaimers.plugin');
        if (!PEAR::isError($oPlugin) && !is_null($oPlugin)) {
            $sDisclaimer = $oPlugin->getLoginDisclaimer();
        }

        $oTemplating =& KTTemplating::getSingleton();
        $oTemplate = $oTemplating->loadTemplate("ktcore/login");
        $aTemplateData = array(
              "context" => $this,
              'errorMessage' => $errorMessage,
              'redirect' => $redirect,
              'systemVersion' => $default->systemVersion,
              'versionName' => $default->versionName,
              'languages' => $aRegisteredLanguageNames,
              'selected_language' => $sLanguageSelect,
	      'disclaimer' => $sDisclaimer,
        );
        return $oTemplate->render($aTemplateData);        
    }
    
    function simpleRedirectToMain($errorMessage, $url, $params) {
        $params[] = 'errorMessage='. urlencode($errorMessage);
        $url .= '?' . join('&', $params);
        redirect($url);
        exit(0);
    }
    
    function do_login() {
        $this->check();
        global $default;

        $language = KTUtil::arrayGet($_REQUEST, 'language');
        if (empty($language)) {
            $language = $default->defaultLanguage;
        }
        setcookie("kt_language", $language, 2147483647, '/');
        
        $redirect = KTUtil::arrayGet($_REQUEST, 'redirect');
        
        $url = $_SERVER["PHP_SELF"];
        $queryParams = array();
        
        if ($redirect !== null) {
            $queryParams[] = 'redirect=' . urlencode($redirect);
        }
        
        $username = KTUtil::arrayGet($_REQUEST,'username');
        $password = KTUtil::arrayGet($_REQUEST,'password');
        
        if (empty($username)) {
            $this->simpleRedirectToMain(_kt('Please enter your username.'), $url, $queryParams);
        }
        
        if (empty($password)) {
            $this->simpleRedirectToMain(_kt('Please enter your password.'), $url, $queryParams);
        }

        $oUser =& User::getByUsername($username);
        if (PEAR::isError($oUser) || ($oUser === false)) {
            $this->simpleRedirectToMain(_kt('Login failed.  Please check your username and password, and try again.'), $url, $queryParams);
            exit(0);
        }
        $authenticated = KTAuthenticationUtil::checkPassword($oUser, $password);

        if (PEAR::isError($authenticated)) {
            $this->simpleRedirectToMain(_kt('Authentication failure.  Please try again.'), $url, $queryParams);
            exit(0);
        }

        if ($authenticated !== true) {
            $this->simpleRedirectToMain(_kt('Login failed.  Please check your username and password, and try again.'), $url, $queryParams);
            exit(0);
        }

        $session = new Session();
        $sessionID = $session->create($oUser);

        // DEPRECATED initialise page-level authorisation array
        $_SESSION["pageAccess"] = NULL; 

        $cookietest = KTUtil::randomString();
        setcookie("CookieTestCookie", $cookietest, 0);

        $this->redirectTo('checkCookie', array(
            'cookieVerify' => $cookietest,
            'redirect' => $redirect,
        ));
        exit(0);
    }

    function do_checkCookie() {
        $cookieTest = KTUtil::arrayGet($_COOKIE, "CookieTestCookie", null);
        $cookieVerify = KTUtil::arrayGet($_REQUEST, 'cookieVerify', null);

        $url = $_SERVER["PHP_SELF"];
        $queryParams = array();
        $redirect = KTUtil::arrayGet($_REQUEST, 'redirect');

        if ($redirect !== null) {
            $queryParams[] = 'redirect='. urlencode($redirect);
        }
        
        if ($cookieTest !== $cookieVerify) {
            Session::destroy();
            $this->simpleRedirectToMain(_kt('You must have cookies enabled to use the document management system.'), $url, $queryParams);
            exit(0);
        }
        
        // check for a location to forward to
        if ($redirect !== null) {
            $url = $redirect;
        // else redirect to the dashboard if there is none
        } else {
            $url = generateControllerUrl("dashboard");
        }

        exit(redirect($url));
    }
}


$dispatcher =& new LoginPageDispatcher();
$dispatcher->dispatch();

?>
