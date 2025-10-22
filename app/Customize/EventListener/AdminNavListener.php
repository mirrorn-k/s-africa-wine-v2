<?php
namespace Customize\EventListener;

use Eccube\Event\EventArgs;

class AdminNavListener
{
    public function onAdminNavInitialize(EventArgs $event)
    {
        file_put_contents('/var/www/html/var/log/rank_nav.log', 'called', FILE_APPEND);

        // 既存のナビゲーション取得
        $nav = $event->getArgument('nav');

        // 「会員管理」メニュー配下にランクを追加
        $nav['customer']['children'][] = [
            'id' => 'admin_customer_rank',
            'name' => 'ランク',
            'url' => 'admin_customer_rank',
            'icon' => 'fa fa-star',
        ];

        // 更新を反映
        $event->setArgument('nav', $nav);
    }

    public static function getSubscribedEvents()
    {
        return [
            EccubeEvents::ADMIN_NAV_INITIALIZE => ['onAdminNavInitialize', 0],
        ];
    }
}
