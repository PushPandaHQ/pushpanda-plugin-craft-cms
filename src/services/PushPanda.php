<?php

namespace pushpandaio\pushpandawebpush\services;

use pushpandaio\pushpandawebpush\PushPanda as Plugin;
use craft\base\Component;
use craft\web\View;
use craft\models\Site;
use yii\base\Event;

class PushPanda extends Component
{
    public string $ppDirectory = "";
    public string $ppId = "";

    /**
     * Register PushPanda Embed code
     */
    public function registerPushPanda()
    {
        $site = \Craft::$app->sites->getCurrentSite();

        if (!$this->getIsActive($site)) {
            return;
        }

        $this->ppDirectory = $this->getServiceWorker($site);
        $this->ppId = $this->getProjectId($site);

        $this->embedPushPanda();
    }

    /**
     * Register embed code for PushPanda.io
     */
    protected function embedPushPanda() {
        Event::on(View::class, View::EVENT_END_BODY, function () {
            echo("<script type='text/javascript' data-cfasync='false'>
var _pushpanda = _pushpanda || [];
_pushpanda.push(['_project', '" . $this->ppId . "']);
_pushpanda.push(['_path', '" . $this->ppDirectory . "']);

(function () {
    var pushPanda = document.createElement('script');
    pushPanda.src = '//cdn.pushpanda.io/sdk/sdk.js';
    pushPanda.type = 'text/javascript';
    pushPanda.async = 'true';
    var push_s = document.getElementsByTagName('script')[0];
    push_s.parentNode.insertBefore(pushPanda, push_s);
})();
</script>");
        });
    }

    /**
     * Get isActive setting for current site
     *
     * @param  \craft\models\Site $site
     * @return bool
     */
    protected function getIsActive($site): bool
    {
        $settings = Plugin::$plugin->settings;
        return $settings->getIsActive($site);
    }

    /**
     * Get ServiceWorker directory for current site
     *
     * @param  \craft\models\Site $site
     * @return bool
     */
    protected function getServiceWorker($site): string
    {
        $settings = Plugin::$plugin->settings;
        return $settings->getServiceWorker($site);
    }

    /**
     * Get ServiceWorker directory for current site
     *
     * @param  \craft\models\Site $site
     * @return string
     */
    protected function getProjectId($site): string
    {
        $settings = Plugin::$plugin->settings;
        return $settings->getProjectId($site);
    }

}