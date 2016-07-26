<?php

namespace Calendar51\Domain\Event;

use Calendar51\Domain\Exception\InvalidDomainData;
use Respect\Validation\Validator;

/**
 * Class Service
 *
 * @package Calendar51\Domain\Event
 */
class EventValidator
{
    /**
     * @var Validator
     */
    protected $v;

    /**
     * Prepare validator.
     */
    public function initValidator()
    {
        $this->v = new Validator();
    }

    /**
     * Validate new event request data.
     *
     * @param array $data
     *
     * @throws InvalidDomainData
     */
    public function validateNewEventData(array $data)
    {
        // On add we don't have the event id yet.
        if (isset($data['id'])) {
            $this->validateEventId($data['id']);
        }

        $this->validateEventDescription($data['description']);

        $this->validateEventDates($data['date_format'], $data['from_date'], $data['to_date']);

        $this->validateEventLocation($data['location']);

        // Comment is an optional parameter.
        if (isset($data['comment'])) {
            $this->validateEventComment($data['comment']);
        }
    }

    /**
     * Validate update event request data.
     *
     * @param array $data
     *
     * @throws InvalidDomainData
     */
    public function validateUpdateEventData(array $data)
    {
        $this->validateEventId($data['id']);

        if (isset($data['description'])) {
            $this->validateEventDescription($data['description']);
        }

        if (isset($data['date_format']) && isset($data['from_date']) && isset($data['to_date'])) {
            $this->validateEventDates($data['date_format'], $data['from_date'], $data['to_date']);
        }

        if (isset($data['location'])) {
            $this->validateEventLocation($data['location']);
        }

        if (isset($data['comment'])) {
            $this->validateEventComment($data['comment']);
        }
    }

    /**
     * Validate Event id.
     *
     * @param int $id
     *
     * @throws InvalidDomainData
     */
    public function validateEventId($id)
    {
        if (!$id) {
            throw new InvalidDomainData('Please provide an event id.');
        }

        if (!$this->v->int()->validate($id)) {
            throw new InvalidDomainData(sprintf('`%s` is not a valid id', $id));
        }
    }

    /**
     * Validate Event description.
     *
     * @param $desc
     *
     * @throws InvalidDomainData
     */
    public function validateEventDescription($desc)
    {
        $this->v->removeRules();
        if (!Validator::string()->validate($desc)) {
            throw new InvalidDomainData(sprintf('`%s` is not a valid description', $data['description']));
        }
    }

    /**
     * Validate Event dates.
     *
     * @param $dateFormat
     * @param $dateFrom
     * @param $dateTo
     *
     * @throws InvalidDomainData
     */
    public function validateEventDates($dateFormat, $dateFrom, $dateTo)
    {
        $this->v->removeRules();
        if (!$this->v->date($dateFormat)->validate($dateFrom)) {
            throw new InvalidDomainData(sprintf('`%s` is not a valid date for `%s` format', $dateFrom, $dateFormat));
        }

        $this->v->removeRules();
        if (!$this->v->date($dateFormat)->validate($dateTo)) {
            throw new InvalidDomainData(sprintf('`%s` is not a valid date for `%s` format', $dateTo, $dateFormat));
        }

        $this->v->removeRules();
        $dateFromTs = strtotime(date_format(date_create_from_format($dateFormat, $dateFrom), 'Y-m-d H:i:s'));
        $dateToTs   = strtotime(date_format(date_create_from_format($dateFormat, $dateTo), 'Y-m-d H:i:s'));
        if (!$this->v->int()->max($dateToTs)->validate($dateFromTs)) {
            throw new InvalidDomainData('To Date param must be greater that From Date param');
        }
    }

    /**
     * Validate Event location.
     *
     * @param $location
     *
     * @throws InvalidDomainData
     */
    public function validateEventLocation($location)
    {
        $this->v->removeRules();
        if (!$this->v->string()->validate($location)) {
            throw new InvalidDomainData(sprintf('`%s` is not a valid location', $location));
        }
    }

    /**
     * Validate Event comment.
     *
     * @param $comment
     *
     * @throws InvalidDomainData
     */
    public function validateEventComment($comment)
    {
        $this->v->removeRules();
        if (!$this->v->string()->validate($comment)) {
            throw new InvalidDomainData(sprintf('`%s` is not a valid comment', $comment));
        }
    }
}
