<?php

namespace Calendar51\Infrastructure\Mapper;

use Calendar51\Domain\Event\Event;

/**
 * Class EventMapper
 *
 * @package Calendar51\Infrastructure\Mapper
 */
class EventMapper
{
    /**
     * @param array $data
     *
     * @return Event
     */
    public function map($data)
    {
        return new Event(
            $data['id'],
            $data['description'],
            $data['date_format'],
            $data['from_date'],
            $data['to_date'],
            $data['location'],
            $data['comment']
        );
    }
}
