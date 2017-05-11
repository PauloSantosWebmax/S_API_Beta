<?php 

/**
 * Interface for PHP 7+ PDO connection wrapper to 
 * Microsoft Sql Server.
 *
 * @author Paulo Santos
 * @copyright 2016 Webmax.pt
 * @version 1.0.0
 * @link http://www.webmax.pt
 *
 */

namespace Sage\MsSqlConnector\Contracts;

interface SqlConnectorInterface
{   
    /**
     * Initialize the instance of PDO PHP Class,
     * It's a wrapper for PHP PDO. This method should be called 
     * immediatly after the instance of the current class.
     *
     * @return void
     */
    public function connect();

    /**
     * Change the default driver for Microsoft Sql Server.
     *
     * @param string $driver new driver
     * @return void
     */
    public function setDriver(string $driver);

    /**
     * This method will not change until a major release.
     *
     * @api
     *
     * @return void
     */
    public static function getVersion();

    /**
     * Overwrite the default fetch mode
     *
     * @param string $mode type
     * @return void
     */
    public function setFetchMode(string $mode);

    /**
     * Overwrite the default err mode
     *
     * @param string $mode type
     * @return void
     */
    public function setErrMode(string $mode);

    /**
     * Finish the connection instance.
     *
     * @return void
     */
    public function __destruct();
}
