<?php

/*
 * This file is part of the Sociable package.
 *
 * Copyright 2013 by Sébastien Pujadas
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

namespace Sociable\Common;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Doctrine\ODM\MongoDB\DocumentManager;

class Configuration {
    protected $dbUser = null;
    protected $dbPassword = null;
    protected $dbHost = null;
    protected $dbPort = null;
    protected $dbName = null;
    protected $dmProxyDir = null;
    protected $dmProxyNamespace = null;
    protected $dmHydratorDir = null;
    protected $dmHydratorNamespace = null;
    protected $dmXMLModelDir = null;

    /** @var DocumentManager */
    protected static $dm = null;
    
    protected $twigTemplateDir = null;
    protected $twigEnvironment = null;
    protected $twigDebug = null;
    
    /** @var \Twig_Environment */
    protected static $twig = null;
    
    protected $loggerChannelName = null;
    protected $loggerLogPath = null;
    protected $loggerDefaultLogLevel = null;
    /** @var Logger */
    protected static $logger = null;
    
    protected $smtpServer = null;
    protected $smtpPort = null;
    protected $smtpSecurity = null;
    protected $smtpLogin = null;
    protected $smtpPassword = null;
    
    /** @var \Swift_Mailer */
    protected static $mailer = null;
    
    protected static $param = array();
    
    const EXCEPTION_MISSING_DM_PARAMS = 'missing document manager params';
    const EXCEPTION_MISSING_TWIG_PARAMS = 'missing Twig params';
    const EXCEPTION_MISSING_LOGGER_PARAMS = 'missing Logger params';
    const EXCEPTION_MISSING_SMTP_PARAMS = 'missing SMTP params';
    const EXCEPTION_MISSING_PARAM = 'missing param';
    const EXCEPTION_MISSING_TRANSLATOR_PARAMS = 'missing translator params';

    public function setParam($key, $value) {
        self::$param[$key] = $value;
    }
    
    public function getParam($key) {
        if (!isset(self::$param[$key])) {
            throw new ConfigurationException(self::EXCEPTION_MISSING_PARAM);
        }
        return self::$param[$key];
    }
    
    public function unsetParam($key) {
        unset(self::$param[$key]);
    }
    
    public function setTwigParams($twigTemplateDir, $twigEnvironment, $twigDebug = false) {
        $this->twigTemplateDir = $twigTemplateDir;
        $this->twigEnvironment = $twigEnvironment;
        $this->twigDebug = $twigDebug;
    }
    
    protected function initTwig() {
        if (in_array(null, array($this->twigTemplateDir, $this->twigEnvironment,
            $this->twigDebug))) {
            throw new ConfigurationException(self::EXCEPTION_MISSING_TWIG_PARAMS);
        }
        $loader = new \Twig_Loader_Filesystem($this->twigTemplateDir);
        
        if ($this->twigDebug) {
            $this->twigEnvironment['debug'] = $this->twigDebug;
        }

        self::$twig = new \Twig_Environment($loader, $this->twigEnvironment);
        
        if ($this->twigDebug) {
            self::$twig->addExtension(new \Twig_Extension_Debug());
        }
    }
    
    public function getTwig() {
        if (is_null(self::$twig)) {
            $this->initTwig();
        }
        return self::$twig;
    }
    
    public function setTwig(\Twig_Environment $twig = null) {
        self::$twig = $twig;
        return self::$twig;
    }
    
    public function setLoggerParams($loggerChannelName, $loggerLogPath, $loggerDefaultLogLevel) {
        $this->loggerChannelName = $loggerChannelName;
        $this->loggerLogPath = $loggerLogPath;
        $this->loggerDefaultLogLevel = $loggerDefaultLogLevel;
    }
    
    protected function initLogger() {
        if (in_array(null, array($this->loggerChannelName, $this->loggerLogPath,
            $this->loggerDefaultLogLevel))) {
            throw new ConfigurationException(self::EXCEPTION_MISSING_LOGGER_PARAMETERS);
        }
        self::$logger = new Logger($this->loggerChannelName);
        self::$logger->pushHandler(new StreamHandler($this->loggerLogPath, $this->loggerDefaultLogLevel));
    }
    
    public function getLogger() {
        if (is_null(self::$logger)) {
            $this->initLogger();
        }
        return self::$logger;
    }
    
    public function setLogger(Logger $logger = null) {
        self::$logger = $logger;
        return self::$logger;
    }
    
    public function setSmtpParams($smtpServer, $smtpPort, 
            $smtpSecurity, $smtpLogin, $smtpPassword) {
        $this->smtpServer = $smtpServer;
        $this->smtpPort = $smtpPort;
        $this->smtpSecurity = $smtpSecurity;
        $this->smtpLogin = $smtpLogin;
        $this->smtpPassword = $smtpPassword;
    }
        
    protected function initSwiftMailer() {
        if (in_array(null, array($this->smtpServer, $this->smtpPort, 
            $this->smtpSecurity, $this->smtpLogin, $this->smtpPassword))) {
            throw new ConfigurationException(self::EXCEPTION_MISSING_SMTP_PARAMS);
        }
        $transport = \Swift_SmtpTransport::newInstance($this->smtpServer, 
                $this->smtpPort, $this->smtpSecurity)
            ->setUsername($this->smtpLogin)
            ->setPassword($this->smtpPassword);

        // Create the Mailer using your created Transport
        self::$mailer = \Swift_Mailer::newInstance($transport);
    }
    
    public function getSwiftMailer() {
        if (is_null(self::$mailer)) {
            $this->initSwiftMailer();
        }
        return self::$mailer;
    }
    
    public function setSwiftMailer(\SwiftMailer $mailer = null) {
        self::$mailer = $mailer;
        return self::$mailer;
    }
    
    public function setDocumentManagerParams($dbUser, $dbPassword, $dbHost, 
        $dbPort, $dbName, $dmProxyDir, $dmProxyNamespace, $dmHydratorDir, 
        $dmHydratorNamespace, $dmXMLModelDir) {
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
        $this->dbHost = $dbHost;
        $this->dbPort = $dbPort;
        $this->dbName = $dbName;
        $this->dmProxyDir = $dmProxyDir;
        $this->dmProxyNamespace = $dmProxyNamespace;
        $this->dmHydratorDir = $dmHydratorDir;
        $this->dmHydratorNamespace = $dmHydratorNamespace;
        $this->dmXMLModelDir = $dmXMLModelDir;
    }

    public function setDocumentManager(DocumentManager $dm) {
        self::$dm = $dm;
    }

    public function getDocumentManager() {
        if (is_null(self::$dm)) {
            $this->initDocumentManager();
        }
        return self::$dm;
    }

    public function initDocumentManager() {
        if (in_array(null, array($this->dbUser, $this->dbPassword, 
            $this->dbHost, $this->dbPort, $this->dbName, $this->dmProxyDir, 
            $this->dmProxyNamespace, $this->dmHydratorDir, 
            $this->dmHydratorNamespace, $this->dmXMLModelDir))) {
            throw new ConfigurationException(self::EXCEPTION_MISSING_DM_PARAMS);
        }

        $dmConfig = new \Doctrine\ODM\MongoDB\Configuration();

        $dmConfig->setProxyDir($this->dmProxyDir);
        $dmConfig->setProxyNamespace($this->dmProxyNamespace);

        $dmConfig->setHydratorDir($this->dmHydratorDir);
        $dmConfig->setHydratorNamespace($this->dmHydratorNamespace);

        $dmConfig->setDefaultDB($this->dbName);

        $driver = new \Doctrine\ODM\MongoDB\Mapping\Driver\XmlDriver($this->dmXMLModelDir);
        $dmConfig->setMetadataDriverImpl($driver);

        $dsn = "mongodb://" . $this->dbUser . ":" . $this->dbPassword . "@"
            . $this->dbHost . ":" . $this->dbPort . "/" . $this->dbName;

        self::$dm = DocumentManager::create(new \Doctrine\MongoDB\Connection($dsn), $dmConfig);
    }
}


