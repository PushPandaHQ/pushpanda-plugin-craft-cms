<?php

namespace pushpandaio\pushpandawebpush\models;

use Craft;
use craft\base\Model;

class Settings extends Model
{
    public $projectId = [];
    public $isActive = [];
    public $serviceWorker = [];

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['projectId', 'each', 'rule' => ['string']],
            ['serviceWorker', 'each', 'rule' => ['string']],
            ['isActive', 'each', 'rule' => ['boolean']],
            /*['projectId', 'required', 'when' => static function($model) {
                return $model->isActive === true; //TODO missing site parameter
            }],*/
        ];
    }

    /**
     * Project ID getter
     *
     * @param  Site $site
     * @return ?string
     */
    public function getProjectId($site): ?string
    {
        if (!$site) {
            return null;
        }
        if (isset($this->projectId[$site->uid])) {
            return \Craft::parseEnv($this->projectId[$site->uid]);
        }

        return null;
    }

    /**
     * ServiceWorker Url getter
     *
     * @param  Site $site
     * @return ?string
     */
    public function getServiceWorker($site): ?string
    {
        if (!$site) {
            return null;
        }
        if (isset($this->serviceWorker[$site->uid])) {
            return \Craft::parseEnv($this->serviceWorker[$site->uid]);
        } else {
            //get sw url from assets
            $url = \Craft::$app->assetManager->getPublishedUrl(
                '@pushpandaio/pushpandawebpush/resources/PushPandaWorker.js',
                true
            );
            return str_replace("PushPandaWorker.js","", parse_url($url, PHP_URL_PATH));
        }
    }

    /**
     * Only production getter
     *
     * @param  Site $site
     * @return bool
     */
    public function getIsActive($site): bool
    {
        if (!$site) {
            return false;
        }
        if (isset($this->isActive[$site->uid])) {
            return $this->isActive[$site->uid];
        }
        return false;
    }

}