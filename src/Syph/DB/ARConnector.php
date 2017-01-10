<?php
/**
 * Created by PhpStorm.
 * User: btlou
 * Date: 09/01/2017
 * Time: 19:36
 */

namespace Syph\DB;


use ActiveRecord\Config;

class ARConnector extends SyphDBConnector
{
    private $configurator;

    /**
     * ARConnector constructor.
     */
    public function __construct($env,$connections,$modelDirectories)
    {
        $this->configurator = $this->configureAR($connections);
        $this->configurator = $this->configureAR($connections);
        foreach ($modelDirectories as $modelDirectory) {
            $this->configurator->set_model_directory($modelDirectory);
        }
        $this->configurator->set_default_connection($env);
    }

    private function configureAR($connections)
    {
        require_once VENDOR_DIR.'php-activerecord/php-activerecord/ActiveRecord.php';
        $config = \ActiveRecord\Config::instance();
        $config->set_connections($connections);
        return $config;
    }

    public function getConfigurator()
    {
        return $this->configurator;
    }
}