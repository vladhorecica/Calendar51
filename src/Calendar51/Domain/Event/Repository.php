<?php

namespace Calendar51\Domain\Event;

/**
 * Interface Repository
 *
 * @package Calendar51\Domain\Event
 */
interface Repository
{
    /**
     * @param array $data
     * @param \PDO $con
     *
     * @return int
     */
    public function add(\PDO $con, array $data);

    /**
     * @param \PDO $con
     * @param int  $id
     *
     * @return void
     */
    public function delete(\PDO $con, $id);

    /**
     * @param \PDO $con
     * @param array $data
     *
     * @return void
     */
    public function update(\PDO $con, array $data);

    /**
     * @param int  $id
     * @param \PDO $con
     *
     * @return Event|array
     */
    public function findById(\PDO $con, $id);

    /**
     * @param  \PDO $con
     *
     * @return array
     */
    public function findAll(\PDO $con);
}
