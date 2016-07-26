<?php

namespace Calendar51\Domain\Event;

use Calendar51\Domain\Db;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class SchemaUpdate
 *
 * @package Calendar51\Domain\Event
 */
class SchemaUpdate
{
    /**
     * @var string
     */
    protected $setupDir;

    /**
     * @var \PDO
     */
    protected $connection;

    /**
     * SchemaUpdate constructor.
     *
     * @param Db     $con
     * @param string $setupDir
     */
    public function __construct(Db $con, $setupDir)
    {
        $this->setupDir = $setupDir;
        $this->connection = $con->getCon();
    }

    /**
     * Load sql data and execute it.
     *
     * @param string $filename
     *
     * @return int
     *
     * @throws \Exception
     */
    public function update($filename)
    {
        $fs = new Filesystem();
        $filePath = $this->setupDir.'/'.$filename;

        if (!$fs->exists($filePath)) {
            throw new \Exception(
                sprintf('There is no setup file `%s`', $filename)
            );
        }

        $sqlContent = file_get_contents($filePath);
        $this->connection->exec($sqlContent);
    }
}
