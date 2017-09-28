<?php declare(strict_types=1);

namespace Shopware\Framework\Event;

use Shopware\Context\Struct\TranslationContext;

class StatisticRefererWrittenEvent extends NestedEvent
{
    const NAME = 'statistic_referer.written';

    /**
     * @var string[]
     */
    protected $statisticRefererUuids;

    /**
     * @var NestedEventCollection
     */
    protected $events;

    /**
     * @var array
     */
    protected $errors;

    /**
     * @var TranslationContext
     */
    protected $context;

    public function __construct(array $statisticRefererUuids, TranslationContext $context, array $errors = [])
    {
        $this->statisticRefererUuids = $statisticRefererUuids;
        $this->events = new NestedEventCollection();
        $this->context = $context;
        $this->errors = $errors;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): TranslationContext
    {
        return $this->context;
    }

    /**
     * @return string[]
     */
    public function getStatisticRefererUuids(): array
    {
        return $this->statisticRefererUuids;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function addEvent(NestedEvent $event): void
    {
        $this->events->add($event);
    }

    public function getEvents(): NestedEventCollection
    {
        return $this->events;
    }
}