<?php

namespace Calendar51\Infrastructure\Repository;

use Calendar51\Domain\Event\Event;
use Calendar51\Domain\Event\Repository;
use Calendar51\Infrastructure\Mapper\EventMapper;

/**
 * Class EventRepository
 *
 * @package Calendar51\Infrastructure\Repository
 */
class EventRepository implements Repository
{
    const STANDARD_DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * Create a new event.
     *
     * @param \PDO  $con
     * @param array $data
     *
     * @return int
     */
    public function add(\PDO $con, array $data)
    {
        $sql = 'INSERT INTO `event` (`description`, `date_format`, `from_date`, `to_date`, `location`, `comment`)
                VALUES (:description, :date_format, :from_date, :to_date, :location, :comment)';
        $stmt = $con->prepare($sql);

        $data = $this->prepareDates($data, true);

        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':date_format', $data['date_format']);
        $stmt->bindParam(':from_date', $data['from_date']);
        $stmt->bindParam(':to_date', $data['to_date']);
        $stmt->bindParam(':location', $data['location']);
        // The comment is not mandatory.
        $comment = isset($data['comment']) ? $data['comment'] : null;
        $stmt->bindParam(':comment', $comment);

        if (!$stmt->execute()) {
            throw new \PDOException('There was a problem adding this event.');
        }

        return (int)$con->lastInsertId();
    }

    /**
     * Delete an event.
     *
     * @param \PDO $con
     * @param int  $id
     *
     * @return void
     */
    public function delete(\PDO $con, $id)
    {
        $sql = 'DELETE FROM `event` WHERE `id`=:id';
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':id', $id);

        if (!$stmt->execute()) {
            throw new \PDOException('Unable to delete the requested event.');
        }
    }

    /**
     * Update an existing event.
     *
     * @param \PDO  $con
     * @param array $data
     *
     * @return void
     */
    public function update(\PDO $con, array $data)
    {
        // Create dynamic query string.
        $fields = array();
        $eventId = $data['id'];

        // Unset id since we don't need to change its value.
        unset($data['id']);
        foreach ($data as $field => $value) {
            $fields[] = "`$field`=:$field";
        }
        $fields = implode(',', $fields);

        $sql = "UPDATE `event` SET $fields WHERE `id`=:id";
        $stmt = $con->prepare($sql);

        $data = $this->prepareDates($data, true);
        foreach($data as $column => $newVal) {
            $stmt->bindValue(":$column", $newVal);
        }
        $stmt->bindParam(':id', $eventId);

        if (!$stmt->execute()) {
            throw new \PDOException('Unable to update the requested event.');
        }
    }

    /**
     * Return an existing event by a given id.
     *
     * @param \PDO $con
     * @param int $id
     *
     * @return Event|array
     */
    public function findById(\PDO $con, $id)
    {
        $sql = 'SELECT * FROM `event` WHERE `id`= :id';
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            $result = $this->prepareDates($result);
            $mapper = new EventMapper();
            $result = $mapper->map($result);

        } else {
            $result = array();
        }

        return $result;
    }

    /**
     * Return existing events ordered chronologically.
     *
     * @param  \PDO $con
     *
     * @return array
     */
    public function findAll(\PDO $con)
    {
        $sql = 'SELECT * FROM `event` ORDER BY `from_date` ASC';
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if ($result) {
            foreach ($result as &$row) {
                $row = $this->prepareDates($row);
                $mapper = new EventMapper();
                $row = $mapper->map($row);
            }
        } else {
            $result = array();
        }

        return $result;
    }

    /**
     * Convert timestamp to date.
     *
     * @param  array $data
     * @param  bool $toTimestamp
     *
     * @return array
     */
    protected function prepareDates(array $data, $toTimestamp = false)
    {
        if ($toTimestamp) {
            // When converting to timestamp we need to convert to the standard datetime format `Y-m-d H:i:s`.
            // This way we can accept any datetime format from the client.
            if (isset($data['from_date']) && isset($data['date_format'])) {
                $data['from_date'] = strtotime(date_format(date_create_from_format($data['date_format'], $data['from_date']), self::STANDARD_DATE_FORMAT));
            }

            if (isset($data['to_date']) && isset($data['date_format'])) {
                $data['to_date']   = strtotime(date_format(date_create_from_format($data['date_format'], $data['to_date']), self::STANDARD_DATE_FORMAT));
            }
        } else {
            $data['from_date'] = date($data['date_format'], $data['from_date']);
            $data['to_date']   = date($data['date_format'], $data['to_date']);
        }

        return $data;
    }
}
