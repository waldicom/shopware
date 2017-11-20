<?php declare(strict_types=1);

namespace Shopware\Framework\Event;

use Shopware\Api\Write\WrittenEvent;

class CrontabWrittenEvent extends WrittenEvent
{
    const NAME = 's_crontab.written';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getEntityName(): string
    {
        return 's_crontab';
    }
}