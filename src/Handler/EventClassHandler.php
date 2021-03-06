<?php namespace Obrignoni\Webhooks\Handler;

use Obrignoni\Webhooks\Contract\EventClassHandlerInterface;
use Obrignoni\Webhooks\Contract\WebhooksInterface;

class EventClassHandler implements EventClassHandlerInterface
{

    /**
     * The webhook name.
     *
     * @var string
     */
    protected $webhookName;

    /**
     * The event name.
     *
     * @var string
     */
    protected $eventName;

    /**
     * The event map.
     *
     * @var array
     */
    protected $eventMap;

    /**
     * The webhooks object.
     *
     * @var WebhooksInterface
     */
    protected $webhooks;

    /**
     * EventClassHandler constructor.
     * @param WebhooksInterface $webhooks
     * @param $webhookName
     * @param string $eventName
     * @param array $eventMap
     */
    public function __construct(WebhooksInterface $webhooks, $webhookName, $eventName, $eventMap = [])
    {
        $this->eventName = $eventName;
        $this->webhookName = $webhookName;
        $this->eventMap = $eventMap;
        $this->webhooks = $webhooks;
    }

    /**
     * Get the event class.
     *
     * @return string
     */
    public function getEventClass()
    {
        return $this->appendClassToEventNamespace($this->getEventClassFromMap());
    }

    /**
     * Get the default event class.
     *
     * @return string
     */
    protected function getDefaultEventClass()
    {
        return $this->appendClassToEventNamespace($this->getWebhookName() . $this->getEventName());
    }

    /**
     * Get the event class from the event map array or return a default value.
     *
     * @return string|null
     */
    protected function getEventClassFromMap()
    {
        if ( ! $this->eventName)
        {
            return null;
        }

        return array_get($this->eventMap, $this->eventName, $this->getDefaultEventClass());
    }

    /**
     * Transform the event name.
     *
     * @return string
     */
    protected function getWebhookName()
    {
        return $this->transformName($this->webhookName);
    }


    /**
     * Transform the event name.
     *
     * @return string
     */
    protected function getEventName()
    {
        return $this->transformName($this->eventName);
    }

    /**
     * Transform a name.
     *
     * @param $name
     * @return string
     */
    protected function transformName($name)
    {
        return studly_case(str_replace([':', '-'], '_', $name));
    }

    /**
     * Append the class to the event namespace.
     *
     * @param string $class
     * @return string
     */
    protected function appendClassToEventNamespace($class)
    {
        if ( ! starts_with($class, $this->webhooks->getEventsNamespace()))
        {
            $class = $this->webhooks->getEventsNamespace() . '\\' . $class;
        }

        return $class;
    }

}