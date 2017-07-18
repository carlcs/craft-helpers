# Helpers plugin for Craft CMS

A collection of Twig functions.

## Installation

The plugin is available on Packagist and can be installed using Composer. You can also download the [latest release][1] and copy the files into craft/plugins/helpers/.

```
$ composer require carlcs/craft-helpers
```

  [1]: https://github.com/carlcs/craft-helpers/releases/latest

## File Helpers

The plugin provides functions, which allow to read and convert JSON, YAML, CSV and PHP file contents. Using the `readText` or `inline` function you can read an entire file into a string.

All functions take a `path` argument, this can be either a relative or absolute path, or full URL to the file. A relative path is interpreted as relative to the web root by default, but this can be changed with the `basePath` config setting.

Here are some ideas for what you can do with reading files:

- read SVG files or “above-the-fold” CSS files and inline them into your templates
- read documents written in markdown that you version control in Git
- read mock data from JSON or YAML files for prototyping
- read Element API endpoints without Ajax requests
- read mapping tables for all sorts of things (keywords to element IDs, keywords to common sets of Atomic CSS classes, …)

#### readJson( path )

Reads a JSON file, parses and converts its contents.

- **`path`** (required) – The path to the file to read.

```twig
{% for article in readJson('https://example.com/api/news') %}
    {{ article.body }}
{% endfor %}
```

#### readYaml( path )

Reads a YAML file, parses and converts its contents.

- **`path`** (required) – The path to the file to read.

```twig
{% set c = readYaml('tachyons/base.yaml') %}
<img class="{{ c.img.avatar }}" src="http://tachyons.io/img/logo.jpg" alt="avatar">

{# outputs "br-100 pa1 ba b--black-10 h3 w3" #}
```

#### readCsv( path )

Reads a CSV file, parses and converts its contents.

- **`path`** (required) – The path to the file to read.
- **`associative`** (default `true`) – Whether an associative array should be returned for each row. The keys are provided from the first CSV row.

```twig
{% for customer in readCsv('data/customers.csv') %}
    {{ customer['First Name'] }}
{% endfor %}

{# outputs "Paul Clara Max Thomas Simone" #}
```

#### readPhp( path )

Executes a PHP file’s return statement and returns the value.

- **`path`** (required) – The path to the file to read.

```twig
{% for element in readPhp('data/elements.php') %}
    {{ element.getUrl() }}
{% endfor %}
```

#### readText( path )

Reads a file’s contents into a string. The plugin also provides an alias for this function with `inline( path )`.

- **`path`** (required) – The path to the file to read.

```twig
{{ readText('data/notes.md')|md }}
```

```twig
{{ inline('assets/svg/logo.svg') }}
```

```twig
{% set data = inline(url('api/elements.json')) %}
<vue-component :data="{{ data }}"></vue-component>
```

## String Helpers

#### truncate( length, suffix, preserve )

Truncates a string to a given length.

- **`length`** (default `30`) – The number of characters, beyond which the text should be truncated.
- **`suffix`** (default `'…'`) – The string to be appended if truncating occurs.
- **`preserve`** (default `true`) – Ensures that the filter doesn’t split words.

```twig
{{ 'This is some very long text and it is causing a lot of problems.'|truncate(30) }}

{# outputs "This is some very long text…" #}
```

#### truncateHtml( length, suffix, preserve )

A version of the `truncate` filter that is capable of handling HTML as an input string. `truncateHtml` closes HTML tags, if they’d be cut off by the truncation. Please note that its performance is worse than the normal `truncate` filter, so only use it when you need to.

- **`length`** (default `30`) – The number of characters, beyond which the text should be truncated.
- **`suffix`** (default `'…'`) – The string to be appended if truncating occurs.
- **`preserve`** (default `true`) – Ensures that the filter doesn’t split words.

```twig
{{ 'This is some <strong>very long text and it is causing a lot of problems</strong>.'|truncateHtml(30) }}

{# outputs "This is some <strong>very long text and</strong>…" #}
```

#### sentenceList( and, separator )

Generates a comma separated list from an array of strings, where the last two strings are joined with “and”.

- **`and`** (default `', and '`) – The separator between the last two strings (translatable using [translation files][2]).
- **`separator`** (default `', '`) – The separator between the other strings.

```twig
{% set names = ['Patrick', 'Clarisse', 'Caitlin', 'Danny', 'Loretta'] %}
{{ names|sentenceList }}

{# outputs "Patrick, Clarisse, Caitlin, Danny, and Loretta" #}
```

  [2]: https://craftcms.com/support/static-translations

#### titleize( ignore )

Returns a string with the first letter of each word capitalized.

- **`ignore`** (default `['the', 'to', ...]`) – A list of words which should not be capitalized. The default list can be overridden with the `titleizeIgnore` config setting.

```twig
{{ 'i like to watch television'|titleize }}

{# outputs "I Like to Watch Television" #}
```

#### collapseWhitespace

Trims the string and replaces consecutive whitespace characters with a single space. This includes tabs and newline characters, as well as multibyte whitespace such as the thin space and ideographic space.

```twig
{{ '     where that  extra whitespace  might come from? '|collapseWhitespace }}

{# outputs "where that extra whitespace might come from?" #}
```

#### stripWords( wordlist, ignoreCase )

Returns the input string stripped from all words of a given list of words.

- **`wordlist`** (required) – The array of words that should get stripped from the input string.
- **`ignoreCase`** (default `true`) – Controls whether case is ignored or respected.

```twig
{% set string = 'In theory it removes both the A and AN, but not the THE.' %}
{{ string|stripWords(['a', 'an']) }}

{# outputs "In theory it removes both the and , but not the THE." #}
```

#### stripPunctuation

Returns the input string stripped from all punctuation.

```twig
{% set string = 'In theory it removes .,:;*# but not ßøü.' %}
{{ string|stripPunctuation }}

{# outputs "In theory it removes but not ßøü" #}
```

#### htmlEntityDecode

Returns the input string with all HTML entities converted to their applicable characters.

```twig
{% set string = 'Ein Anf&uuml;hrungszeichen ist &lt;b&gt;fett&lt;/b&gt' %}
{{ string|htmlEntityDecode }}

{# outputs "Ein Anführungszeichen ist <b>fett</b>" #}
```

## Number Helpers

#### numbersToWords( locale )

Converts a number to its word representation. The filter uses the Numbers_Words library to generate the output. Have a look at its documentation for a [list of supported languages][5].

- **`locale`** (default `en_US`) – The language name abbreviation to set the ouput language.

```twig
{{ 42|numbersToWords('de') }}

{# outputs "zweiundvierzig" #}
```

#### currencyToWords( locale, currency, decPoint)

Converts a currency value to word representation. The filter uses the Numbers_Words library to generate the output. Have a look at its documentation for a [list of supported languages][5] and currency symbols.

- **`locale`** (default `en_US`) – The language name abbreviation to set the ouput language.
- **`currency`** (default `''`) – The international currency symbol to set the ouput currency.
- **`decPoint`** (default `null`) – The separator for the decimal point.

```twig
{{ 1700.99|currencyToWords('en_US', 'EUR') }}

{# outputs "one thousand seven hundred euros ninety-nine euro-cents" #}
```

  [5]: https://github.com/pear/Numbers_Words

#### numeralSystem( numeralSystem, zero )

Converts a number (arabic numeral system) to a representation of that number in another numeral system. If applied to a rational number (float), the filter rounds it to the closest integer first.

- **`numeralSystem`** (required) – The numeral system the number gets converted to. You can convert to the roman numberal system (`'roman'`, `'upperRoman'` or `'lowerRoman'`) or to the alphabetical equivalent to the input number (`'alpha'`, `'upperAlpha'` or `'lowerAlpha'`).
- **`zero`** (default `-1`) – Maps all negative numbers and zero. Setting this argument to `-1` gives you a decimal number to alphabetical character mapping like so: `-1` → `-B`, `0` → `-A`, `1` → `A`. Any other argument value other than `-1` or `1` does only map `0` to this value (i.e. `0` → `myZero`) and leaves negative number untouched.

```twig
{{ 42|numeralSystem('roman') }}

{# outputs "XLII" #}
```

#### unitPrefix( system, decimals, trailingZeros, decPoint, thousandsSep, unitSep)

Formats a number with unit prefixes.

- **`system`** (default `decimal`) – Either a string (e.g. "decimal") to use a predefined configuration or an array of custom settings.
- **`decimals`** (default `1`) – The number of decimal points.
- **`trailingZeros`** (default `false`) – Whether to show trailing zeros.
- **`decPoint`** (default `'.'`) – The separator for the decimal point.
- **`thousandsSep`** (default `''`) – The thousands separator.
- **`unitSep`** (default `' '`) – The separator between number and unit.

```twig
{{ 72064|unitPrefix }}

{# outputs "72.1 k" #}
```

#### fractionToFloat( precision )

Converts a fraction to a decimal number.

- **`precision`** (default `4`) – The precision (number of digits after the decimal point) the returned value gets rounded to.

```twig
{{ '2/3'|fractionToFloat }}

{# outputs "0.6667" #}
```

#### floatToFraction( tolerance )

Converts a decimal number to a fraction.

- **`tolerance`** (default `0.001`) – The allowed tolerance for the fraction calculation. So, for example, `0.7143` gets converted to `5/7` instead of `7138/9993`.

```twig
{{ 0.7143|floatToFraction }}

{# outputs "5/7" #}
```

## Miscellaneous Helpers

#### randomString( length, extendedChars )

Generates a random string of a given length.

- **`length`** (default `36`) – The length of the generated string.
- **`extendedChars`** (default `false`) – Whether to use an extended set of characters.

```twig
{{ randomString(6) }}

{# outputs "mRU1wI" #}
```

#### md5( string )

Generates the md5 hash of a string.

- **`string`** (required) – The string to generate the hash from.

```twig
{% set string = 'Lorem ipsum dolor sit amet.' %}
{{ string|md5 }}

{# outputs "eb76f38646cca9420296bfc6731f94b5" #}
```

#### json_decode( assoc, depth, options )

Decodes a JSON string.

- **`assoc`** (default `false`) – Whether objects should be converted into associative arrays.
- **`depth`** (default `512`) – The recursion depth.
- **`options`** (default `null`) – Bitmask of JSON decode options.

```twig
{% set json = '{"beers":["Alpirsbacher Klosterbräu","Rothaus Tannenzäpfle","Neumarkter Lammsbräu"],"whiskeys":null}' %}
{% set drinks = json|json_decode() %}

{{ drinks.beers[1] }}

{# outputs "Rothaus Tannenzäpfle" #}
```

#### setNotice( message )

Stores a notice in the user’s flash data.

- **`message`** (required) – The message.

```twig
{% do setNotice('My short message.') %}
{{ craft.session.getFlash('notice') }}

{# outputs "My short message." #}
```

#### setError( message )

Stores an error message in the user’s flash data.

- **`message`** (required) – The message.

```twig
{% do setError('Do panic!') %}
{{ craft.session.getFlash('error') }}

{# outputs "Do panic!" #}
```

## Settings

You can override plugin defaults with a helpers.php config file, which you need to create in your craft/config/ folder.

```php
<?php

return [
    'basePath' => getenv('BASE_PATH') ?: $_SERVER['DOCUMENT_ROOT'],
    'titleizeIgnore' => ['at', 'by', 'for', 'in', 'of', 'on', 'out', 'to', 'the'],
];
```

#### basePath

The `basePath` is used by the `inline` and file reading functions. The default setting uses the value of your `BASE_PATH` environment variable if you have that set, otherwise falls back your the web root. You can override it with something like, for example `CRAFT_CONFIG_PATH.'data/'`.

#### titleizeIgnore

List of words which should not be capitalized by the `titleize` filter.

## Requirements

- PHP 5.4+
