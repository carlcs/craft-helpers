<?php
namespace Craft;

use Twig_SimpleFunction;
use Twig_SimpleFilter;

class HelpersTwigExtension extends \Twig_Extension
{
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'Helpers';
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('randomString', [craft()->helpers_misc, 'randomString']),

            new Twig_SimpleFunction('setNotice', [craft()->helpers_misc, 'setNotice']),
            new Twig_SimpleFunction('setError', [craft()->helpers_misc, 'setError']),
        ];
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        $options = [];

        if (!craft()->config->get('escapeHtml', 'helpers')) {
            $options['is_safe'] = ['html'];
        }

        return [
            new Twig_SimpleFilter('truncate', [craft()->helpers_string, 'truncate'], $options),
            new Twig_SimpleFilter('stripWords', [craft()->helpers_string, 'stripWords'], $options),
            new Twig_SimpleFilter('stripPunctuation', [craft()->helpers_string, 'stripPunctuation'], $options),
            new Twig_SimpleFilter('htmlEntityDecode', [craft()->helpers_string, 'htmlEntityDecode'], $options),

            new Twig_SimpleFilter('numbersToWords', [craft()->helpers_number, 'numbersToWords']),
            new Twig_SimpleFilter('currencyToWords', [craft()->helpers_number, 'currencyToWords']),

            new Twig_SimpleFilter('numeralSystem', [craft()->helpers_number, 'numeralSystem']),
            new Twig_SimpleFilter('unitPrefix', [craft()->helpers_number, 'unitPrefix']),
            new Twig_SimpleFilter('fractionToFloat', [craft()->helpers_number, 'fractionToFloat']),
            new Twig_SimpleFilter('floatToFraction', [craft()->helpers_number, 'floatToFraction']),

            new Twig_SimpleFilter('jsonDecode', [craft()->helpers_misc, 'jsonDecode']),
            new Twig_SimpleFilter('json_decode', [craft()->helpers_misc, 'jsonDecode']),
            new Twig_SimpleFilter('md5', [craft()->helpers_misc, 'md5'], $options),
        ];
    }
}
