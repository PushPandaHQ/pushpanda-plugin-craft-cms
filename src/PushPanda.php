<?php
/**
 * PushPanda - Web Push plugin for Craft CMS 4.x
 *
 * Easly integrate Web Push Notifications into your Craft CMS project.
 *
 * @link      https://www.pushpanda.io
 * @copyright Copyright (c) 2022 PushPanda.io
 */

namespace pushpandaio\pushpandawebpush;

use Craft;
use craft\base\Plugin;
use pushpandaio\pushpandawebpush\models\Settings;
use pushpandaio\pushpandawebpush\services\PushPanda as PushPandaService;

class PushPanda extends \craft\base\Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * PushPanda::$plugin
     *
     * @var PushPanda
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public string $schemaVersion = '1.0.2';

    /**
     * Set to `true` if the plugin should have a settings view in the control panel.
     *
     * @var bool
     */
    public bool $hasCpSettings = true;

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        //register service as component
        $this->setComponents([
            'pp' => PushPandaService::class
        ]);

        //render pushpanda embed code if is site request
        if (Craft::$app->request->getIsSiteRequest() and !Craft::$app->request->getIsActionRequest()) {
            PushPanda::$plugin->pp->registerPushPanda();
        }

    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return Settings
     */
    protected function createSettingsModel() : Settings
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'pushpanda-web-push/settings',
            [
                'settings' => $this->getSettings(),
                'sites' => \Craft::$app->sites->getAllSites()
            ]
        );
    }
}