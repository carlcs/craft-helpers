<?php
namespace Craft;

use Stringy\Stringy;
use Urodoz\Truncate\Truncator;

class Helpers_StringService extends BaseApplicationComponent
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the input string cut off after a character limit is reached.
     *
     * @param string $value
     * @param number $length
     * @param string $separator
     * @param boolean $preserve
     *
     * @return string
     */
    public function truncate($value, $length = 30, $separator = '…', $preserve = true)
    {
        if ($preserve) {
            return Stringy::create($value)->safeTruncate($length, $separator);
        } else {
            return Stringy::create($value)->truncate($length, $separator);
        }
    }

    /**
     * Returns the input string cut off after a character limit is reached.
     *
     * @param string $value
     * @param number $length
     * @param string $separator
     * @param boolean $preserve
     *
     * @return string
     */
    public function truncateHtml($value, $length = 30, $separator = '…', $preserve = true)
    {
        $charset = craft()->templates->getTwig()->getCharset();

        return Truncator::truncate($value, $length, [
            'ellipsis' => $separator,
            'length_in_chars' => true,
            'word_safe' => $preserve,
            'charset' => $charset,
        ]);
    }

    /**
     * Returns a comma separated list where the last two items are joined with “and”.
     *
     * @param array $items
     * @param string|null $and
     * @param string $separator
     *
     * @return string
     */
    public static function sentenceList($items, $and = null, $separator = ', ')
    {
        $and = $and ? Craft::t($and) : Craft::t(', and ');

        if (count($items) > 1) {
            $start = implode($separator, array_slice($items, null, -1));
            return $start.$and.array_pop($items);
        }

        return array_pop($items);
    }

    /**
     * Returns a string with the first letter of each word capitalized.
     *
     * @param string $value
     * @param array $ignore
     *
     * @return string
     */
    public function titleize($value, $ignore = null)
    {
        $ignore = $ignore ?: craft()->config->get('titleizeIgnore', 'helpers');

        return Stringy::create($value)->titleize($ignore);
    }

    /**
     * Trims the string and replaces consecutive whitespace characters with a single space.
     *
     * @param string $value
     *
     * @return string
     */
    public function collapseWhitespace($value)
    {
        return Stringy::create($value)->collapseWhitespace();
    }

    /**
     * Returns the input string stripped from all words of a given list of words.
     *
     * @param string $value
     * @param array $list
     * @param boolean $ignoreCase
     *
     * @return string
     */
    public function stripWords($value, $list, $ignoreCase = true)
    {
        foreach ($list as &$word) {
            $word = '/\b'.preg_quote($word, '/').'\b/';
            $word .= $ignoreCase ? 'i' : '';
        }

        return preg_replace($list, '', $value);
    }

    /**
     * Returns the input string stripped from all punctuation.
     *
     * @param string $value
     *
     * @return string
     */
    public function stripPunctuation($value)
    {
        return preg_replace('/[^\w\s]/u', '', $value);
    }

    /**
     * Returns the input string with all HTML entities converted to their
     * applicable characters.
     *
     * @param string $value
     *
     * @return string
     */
    public function htmlEntityDecode($value)
    {
        return html_entity_decode($value);
    }
}
