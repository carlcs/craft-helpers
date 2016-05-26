<?php
namespace Craft;

class HelpersPlugin extends BasePlugin
{
    public function getName()
    {
        return 'Helpers';
    }

    public function getVersion()
    {
        return '1.1.0';
    }

    public function getSchemaVersion()
    {
        return '1.0.0';
    }

    public function getDeveloper()
    {
        return 'carlcs';
    }

    public function getDeveloperUrl()
    {
        return 'https://github.com/carlcs';
    }

    public function getDocumentationUrl()
    {
        return 'https://github.com/carlcs/craft-helpers';
    }

    public function getReleaseFeedUrl()
    {
        return 'https://github.com/carlcs/craft-helpers/raw/master/releases.json';
    }

    // Public Methods
    // =========================================================================

    public function init()
    {
        require_once(CRAFT_PLUGINS_PATH.'helpers/vendor/autoload.php');
    }

    public function addTwigExtension()
    {
        Craft::import('plugins.helpers.twigextensions.HelpersTwigExtension');
        return new HelpersTwigExtension();
    }
}
