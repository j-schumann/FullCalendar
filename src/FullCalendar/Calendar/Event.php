<?php
/*
 *  @copyright   (c) 2014-2015, Vrok
 *  @license     http://customlicense CustomLicense
 *  @author      Jakob Schumann <schumann@vrok.de>
 */

namespace FullCalendar\Calendar;

/**
 * Basic implementation of an fullCalendar compatible calendar event.
 * Use this if your events are stored in other formats/entites and need to be converted.
 */
class Event implements EventInterface
{
    /**
     * @var string|int
     */
    protected $id = null;

    /**
     * @var string
     */
    protected $title = null;

    /**
     * @var bool
     */
    protected $allDay = false;

    /**
     * @var bool
     */
    protected $editable = false;

    /**
     * @var string
     */
    protected $start = null;

    /**
     * @var string
     */
    protected $end = null;

    /**
     * @var string
     */
    protected $url = null;

    /**
     * @var string|array
     */
    protected $className = null;

    /**
     * {@inheritdoc}
     */
    public function getAllDay()
    {
        return $this->allDay;
    }

    /**
     * Sets if this is an all-day event.
     *
     * @param string $value
     */
    public function setAllDay($value)
    {
        $this->allDay = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Sets the events css class.
     *
     * @param string|array $value
     */
    public function setClassName($value)
    {
        $this->className = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getEditable()
    {
        return $this->editable;
    }

    /**
     * Sets if the event can be edited or not.
     *
     * @param bool $value
     */
    public function setEditable($value)
    {
        $this->editable = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Sets the events end date.
     *
     * @param \DateTime $value
     */
    public function setEnd(\DateTime $value)
    {
        $this->end = $value->format(\DateTime::ISO8601);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the events id.
     *
     * @param string|int $value
     */
    public function setId($value)
    {
        $this->id = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Sets the events start date.
     *
     * @param \DateTime $value
     */
    public function setStart(\DateTime $value)
    {
        $this->start = $value->format(\DateTime::ISO8601);
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the events title.
     *
     * @param string $value
     */
    public function setTitle($value)
    {
        $this->title = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the events url.
     *
     * @param string $value
     */
    public function setUrl($value)
    {
        $this->url = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array(
            'id'        => $this->getId(),
            'allDay'    => $this->getAllDay(),
            'title'     => $this->getTitle(),
            'start'     => $this->getStart(),
            'end'       => $this->getEnd(),
            'editable'  => $this->getEditable(),
            'className' => $this->getClassName(),
            'url'       => $this->getUrl(),
        );
    }
}
