<?php 

/**
 * Wrapper for PHP 7+ PDO connection to 
 * Microsoft Sql Server.
 *
 * @author Paulo Santos <tm.paulo.santos@gmail.com>
 * @copyright 2016 Webmax.pt
 * @version 1.0.0
 * @link https://www.webmax.pt
 *
 */

namespace Sage\MsSqlConnector;

use PDO;
use Sage\MsSqlConnector\Exceptions\InvalidParametersException;
use Sage\MsSqlConnector\Contracts\SqlConnectorInterface;

final class SqlConnector implements SqlConnectorInterface
{
    /**
     * @var default driver for database conncection
     */
    private $driver = 'sqlsrv';

    /**
     * @var server IP.
     */
    private $server;

    /**
     * @var server port.
     */
    private $port;

    /**
     * @var database name.
     */
    private $database;

    /**
     * @var username credential.
     */
    private $username;

    /**
     * @var password credential.
     */
    private $password;

    /**
     * @var instance of \PDO.
     */
    private $pdo; 

    /**
     * @var default fetch mode.
     */
    private $fetchMode = PDO::FETCH_OBJ;

    /**
     * @var default errmode.
     */
    private $errmode = PDO::ERRMODE_EXCEPTION;

    /**
     * @var current version of the class.
     */
    const version = '1.0.0';

    /**
     * Set the PDO config variables,
     *
     * @throws InvalidParametersException
     * @return object instance of \App\MsSqlConnector\SqlConnector
     */
    public function __construct(array $params)
    {
        if (!is_array($params)) {
            throw new InvalidParametersException;
        }
        foreach ($params as $param => $value) {
            $this->$param = (string) $value;
        }
        return $this;
    }

    /**
     * Initialize the instance of PDO PHP Class,
     * It's a wrapper for PHP PDO. This method should be called 
     * immediatly after the instance of the current class.
     *
     * @return void
     */
    public function connect()
    {   
        $pdo = new PDO(
            $this->driver . 
            ':Server=' . $this->server . ',' . $this->port . 
            ';Database=' . $this->database, 
            $this->username, $this->password
        );
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $this->fetchMode);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, $this->errmode);
        $this->pdo = $pdo;
        return $this->pdo;
    }

    /**
     * Change the default driver for Microsoft Sql Server.
     *
     * @param string $driver new driver
     * @return void
     */
    public function setDriver(string $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Overwrite the default fetch mode
     *
     * @param string $mode type
     * @return void
     */
    public function setFetchMode(string $mode)
    {
        $this->fetchMode = $mode;
    }

    /**
     * Overwrite the default err mode
     *
     * @param string $mode type
     * @return void
     */
    public function setErrMode(string $mode)
    {
        $this->errmode = $mode;
    }

    /**
     * Destruct the PDO instance at the end of each usage.
     * 
     * @return void
     */
    public function __destruct()
    {
        if (isset($this->pdo)) {
            unset($this->pdo);
        }
    }

    /**
     * This method will not change until a major release.
     *
     * @api
     *
     * @return void
     */
    public static function getVersion()
    {
        return self::version;
    }
}
