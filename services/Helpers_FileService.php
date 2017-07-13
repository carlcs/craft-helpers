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
     * Reads a file contents into a string.
     *
     * @param string $path
     *
     * @return string|null
     */
    public function readText($path)
    {
        $filePath = $this->getFilePath($path);
        $file = @file_get_contents($filePath);

        if ($file === false) {
            HelpersPlugin::log('Couldn’t read file: '.$filePath, LogLevel::Error);
            return null;
        }

        return $file;
    }

    /**
     * Executes a PHP file’s return statement and returns the value.
     *
     * @param string $path
     *
     * @return mixed|null
     */
    public function readPhp($path)
    {
        $filePath = $this->getFilePath($path);
        $file = @include $filePath;

        if ($file === false) {
            HelpersPlugin::log('Couldn’t read file: '.$filePath, LogLevel::Error);
            return null;
        }

        if ($file === 1) {
            HelpersPlugin::log('Return statement missing in PHP file: '.$filePath, LogLevel::Error);
        }

        return $file;
    }

    /**
     * Reads a JSON file, parses and converts its contents.
     *
     * @param string $path
     *
     * @return mixed|null
     */
    public function readJson($path)
    {
        $filePath = $this->getFilePath($path);
        $file = @file_get_contents($filePath);

        if ($file === false) {
            HelpersPlugin::log('Couldn’t read file: '.$filePath, LogLevel::Error);
            return null;
        }

        $data = @json_decode($file, true);

        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            HelpersPlugin::log('Couldn’t read file: '.$filePath.'. '.json_last_error_msg(), LogLevel::Error);
            return null;
        }

        return $data;
    }

    /**
     * Reads a YAML file, parses and converts its contents.
     *
     * @param string $path
     *
     * @return mixed|null
     */
    public function readYaml($path)
    {
        $filePath = $this->getFilePath($path);
        $file = @file_get_contents($filePath);

        if ($file === false) {
            HelpersPlugin::log('Couldn’t read file: '.$filePath, LogLevel::Error);
            return null;
        }

        try {
            $data = Yaml::parse($file);
        } catch (ParseException $e) {
            HelpersPlugin::log('Couldn’t read file: '.$filePath.'. '.$e->getMessage(), LogLevel::Error);
            return null;
        }

        return $data;
    }

    /**
     * Reads a CSV file, parses and converts its contents.
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
            HelpersPlugin::log('Couldn’t read file: '.$filePath.'. '.$e->getMessage(), LogLevel::Error);
            return null;
        }

        return $data;
    }

    // Protected Methods
    // =========================================================================

    /**
     * Resolves relative paths corresponding to the configuration.
     *
     * @param string $path
     *
     * @return string
     */
    protected function getFilePath($path)
    {
        if ($this->isAbsolutePath($path)) {
            return $path;
        }

        $basePath = craft()->config->get('basePath', 'helpers');

        return rtrim($basePath, '/').'/'.$path;
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function isAbsolutePath($path)
    {
        return strspn($path, '/\\', 0, 1)
            || (strlen($path) > 3 && ctype_alpha($path[0])
                && substr($path, 1, 1) === ':'
                && strspn($path, '/\\', 2, 1))
            || null !== parse_url($path, PHP_URL_SCHEME);
    }
}
