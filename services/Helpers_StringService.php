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
     * Returns the input string stripped from all words of a given list of words.
     *
     * @param string $value
     * @param array $wordlist
     * @param boolean $ignoreCase
     *
     * @return string
     */
    public function stripWords($value, $wordlist, $ignoreCase = true)
    {
        foreach ($wordlist as &$word) {
            $word = '/\b'.preg_quote($word, '/').'\b/';
            $word .= $ignoreCase ? 'i' : '';
        }

        return preg_replace($wordlist, '', $value);
    }

    /**
     * Returns the input string stripped from all punctuation.
     *
     * @param string $value
     * @param boolean $removeMultiSpaces
     *
     * @return string
     */
    public function stripPunctuation($value, $removeMultiSpaces = false)
    {
        $value = preg_replace('/[^\w\s]/u', '', $value);

        if ($removeMultiSpaces) {
            $value = preg_replace('/\s+/', ' ', $value);
            $value = trim($value);
        }

        return $value;
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
