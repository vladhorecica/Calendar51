<?php

namespace Calendar51\Domain\Event;

/**
 * Class Event
 *
 * @package Calendat51\Event
 */
class Event
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $dateFormat;

    /**
     * @var string
     */
    private $fromDate;

    /**
     * @var string
     */
    private $toDate;

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $comment;

    /**
     * Event constructor.
     *
     * There's no need for validation since this is an immutable objesct and
     * we use a service designed for that once we add a new event.
     *
     * @param $id
     * @param $description
     * @param $dateFormat
     * @param $fromDate
     * @param $toDate
     * @param $location
     * @param $comment
     */
    public function __construct($id ,$description, $dateFormat, $fromDate, $toDate, $location, $comment)
    {
        $this->id          = $id;
        $this->description = $description;
        $this->dateFormat  = $dateFormat;
        $this->fromDate    = $fromDate;
        $this->toDate      = $toDate;
        $this->location    = $location;
        $this->comment     = $comment;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * @return string
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * @return string
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }
}
