<?php
namespace Craft;

class Helpers_MiscService extends BaseApplicationComponent
{
    // Public Methods
    // =========================================================================

    /**
     * Decodes a JSON string.
     *
     * @param string $value
     * @param bool $assoc
     * @param int $depth
     * @param int $options
     *
     * @return array
     */
    public function jsonDecode($value, $assoc = false, $depth = 512, $options = 0)
    {
        return json_decode(html_entity_decode($value), $assoc, $depth, $options);
    }

    /**
     * Generates a random string of a given length.
     *
     * @param int  $length
     * @param bool $extendedChars
     *
     * @return string
     */
    public function randomString($length = 36, $extendedChars = false)
    {
        return StringHelper::randomString($length, $extendedChars);
    }

    /**
     * Returns the md5 hash of a string.
     *
     * @param string $value
     *
     * @return string
     */
    public function md5($value)
    {
        return md5($value);
    }

    /**
     * Stores a notice in the user’s flash data.
     *
     * @param string $message
     *
     * @return null
     */
    public function setNotice($message)
    {
        craft()->userSession->setFlash('notice', $message);
    }

    /**
     * Stores an error message in the user’s flash data.
     *
     * @param string $message
     *
     * @return null
     */
    public function setError($message)
    {
        craft()->userSession->setFlash('error', $message);
    }
}
