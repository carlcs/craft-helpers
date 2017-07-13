<?php
namespace Craft;

use League\Csv\Reader;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class Helpers_FileService extends BaseApplicationComponent
{
    // Public Methods
    // =========================================================================

    /**
     * Reads a PHP file, parses its content and converts it to an array.
     *
     * @param string $path
     *
     * @return array|null
     */
    public function readPhp($path)
    {
        $filePath = $this->getFilePath($path);
        $data = require $filePath;

        return $data;
    }

    /**
     * Reads a PHP file, parses its content and converts it to an array.
     *
     * @param string $path
     *
     * @return array|null
     */
    public function readText($path)
    {
        $filePath = $this->getFilePath($path);
        $file = @file_get_contents($filePath);

        if ($file === false) {
            HelpersPlugin::log('Couldn’t read file: '.$path, LogLevel::Error);
            return null;
        }

        return $file;
    }

    /**
     * Reads a JSON file, parses its content and converts it to an array.
     *
     * @param string $path
     *
     * @return array|null
     */
    public function readJson($path)
    {
        $filePath = $this->getFilePath($path);
        $file = @file_get_contents($filePath);

        if ($file === false) {
            HelpersPlugin::log('Couldn’t read file: '.$path, LogLevel::Error);
            return null;
        }

        $data = @json_decode($file, true);

        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            HelpersPlugin::log('Couldn’t read file: '.$path.'. '.json_last_error_msg(), LogLevel::Error);
        }

        return $data;
    }

    /**
     * Reads a YAML file, parses its content and converts it to an array.
     *
     * @param string $path
     *
     * @return array|null
     */
    public function readYaml($path)
    {
        $filePath = $this->getFilePath($path);
        $file = @file_get_contents($filePath);

        if ($file === false) {
            HelpersPlugin::log('Couldn’t read file: '.$path, LogLevel::Error);
            return null;
        }

        try {
            $data = Yaml::parse($file);
        } catch (ParseException $e) {
            HelpersPlugin::log('Couldn’t read file: '.$path.'. '.$e->getMessage(), LogLevel::Error);
        }

        return $data;
    }

    /**
     * Reads a CSV file, parses its content and converts it to an array.
     *
     * @param string $path
     *
     * @return array|null
     */
    public function readCsv($path)
    {
        if (!ini_get('auto_detect_line_endings')) {
            @ini_set('auto_detect_line_endings', true);
        }

        $filePath = $this->getFilePath($path);

        try {
            $reader = Reader::createFromPath($filePath);

            $delimiters = $reader->fetchDelimitersOccurrence([',', ';', '|'], 10);
            $reader->setDelimiter(array_keys($delimiters)[0]);

            $results = $reader->fetchAssoc(0, function($row) {
                return array_map('trim', $row);
            });

            $data = iterator_to_array($results);
        } catch (\Exception $e) {
            HelpersPlugin::log('Couldn’t read file: '.$path.'. '.$e->getMessage(), LogLevel::Error);
        }

        return $data;
    }

    // Protected Methods
    // =========================================================================

    /**
     * Returns a provided relative path appended to the base path.
     *
     * @param string $path
     *
     * @return string
     */
    protected function getFilePath($path)
    {
        if ($this->isUrl($path) || $this->isAbsolutePath($path)) {
            return $path;
        }

        $basePath = craft()->config->get('readerBasePath', 'helpers');

        return rtrim($basePath, '/').'/'.$path;
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function isUrl($path)
    {
        $parts = parse_url($path);

        return isset($parts['host']);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function isAbsolutePath($path)
    {
        return substr($path, 0, 1) === DIRECTORY_SEPARATOR;
    }
}
