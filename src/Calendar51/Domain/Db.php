<?php

namespace Calendar51\Domain;

/**
 * Class Db
 *
 * @package Calendar51\Domain
 */
class Db
{
    /**
     * @var string
     */
    private $dbName;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $dbHost;

    /**
     * @param string $dbName
     */
    public function setDbName($dbName)
    {
        $this->dbName = $dbName;
    }

    /**
     * @param string $dbHost
     */
    public function setDbHost($dbHost)
    {
        $this->dbHost = $dbHost;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Returns PDO connection
     *
     * @return \PDO
     */
    public function getCon()
    {
        $options = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION);

        return new \PDO("mysql:host=$this->dbHost;dbname=$this->dbName", $this->username, $this->password, $options);
    }
}
