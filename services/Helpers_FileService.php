<?php
namespace Craft;

use League\Csv\Reader;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class Helpers_FileService extends BaseApplicationComponent
{
    // Static
    // =========================================================================

    /**
     * @var array
     */
    private static $readerMapping = [
        'readPhp' => ['php'],
        'readJson' => ['json'],
        'readYaml' => ['yaml', 'yml'],
        'readCsv' => ['csv'],
    ];

    // Public Methods
    // =========================================================================

    /**
     * Reads a file, parses its content and converts it to an array.
     *
     * @param string $path
     * @param string $property
     *
     * @return array|null
     */
    public function read($path, $property = null)
    {
        $method = $this->getReaderMethod($path);

        if (!$method) {
            return null;
        }

        return $this->{$method}($path, $property);
    }

    /**
     * Reads a PHP file, parses its content and converts it to an array.
     *
     * @param string $path
     * @param string $property
     *
     * @return array|null
     */
    public function readPhp($path, $property = null)
    {
        $filePath = $this->getFilePath($path);
        $data = require $filePath;

        return $this->getValueByKey($property, $data);
    }

    /**
     * Reads a JSON file, parses its content and converts it to an array.
     *
     * @param string $path
     * @param string $property
     *
     * @return array|null
     */
    public function readJson($path, $property = null)
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

        return $this->getValueByKey($property, $data);
    }

    /**
     * Reads a YAML file, parses its content and converts it to an array.
     *
     * @param string $path
     * @param string $property
     *
     * @return array|null
     */
    public function readYaml($path, $property = null)
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

        return $this->getValueByKey($property, $data);
    }

    /**
     * Reads a CSV file, parses its content and converts it to an array.
     *
     * @param string $path
     * @param string $property
     *
     * @return array|null
     */
    public function readCsv($path, $property = null)
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

        return $this->getValueByKey($property, $data);
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

    /**
     * Returns the callable associated with a filename.
     *
     * @param string $path
     *
     * @return string|null
     */
    protected function getReaderMethod($path)
    {
        $filename = IOHelper::getFileName($path);

        $extension = IOHelper::getExtension($filename);
        $extension = StringHelper::toLowerCase($extension);

        if (!$extension) {
            return null;
        }

        foreach (static::$readerMapping as $method => $extensions) {
            if (in_array($extension, $extensions)) {
                return $method;
            }
        }

        return null;
    }

    /**
     * Traverse an array using dot notation.
     * https://selv.in/blog/traversing-arrays-using-dot-notation
     *
     * @param string $property
     * @param array $data
     *
     * @return array|string|null
     */
    protected function getValueByKey($property, $data)
    {
        if (!is_array($data) || !is_string($property) || empty($property)) {
            return $data;
        }

        if (strpos($property, '.') !== false) {
            foreach (explode('.', $property) as $key) {
                if (!array_key_exists($key, $data)) {
                    return null;
                }

                // Continue traversing the array.
                $data = $data[$key];
            }

            return $data;
        }

        return array_key_exists($property, $data) ? $data[$property] : null;
    }
}
