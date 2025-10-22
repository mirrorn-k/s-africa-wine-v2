<?php

namespace Customize\EventListener;

use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CustomerRegisterListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            EccubeEvents::FRONT_ENTRY_INDEX_COMPLETE => 'onRegisterComplete',
        ];
    }

    public function onRegisterComplete(EventArgs $event)
    {
        $Customer = $event->getArgument('Customer');
        if (!$Customer) {
            return;
        }

        if (!$Customer->getRankId()) {
            // デフォルト会員ランクをセット
            $Customer->setRankId(1);
        }
    }
}
