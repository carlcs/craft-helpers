# Helpers plugin for Craft CMS

A collection of Twig functions.

## Installation

The plugin is available on Packagist and can be installed using Composer. You can also download the [latest release][1] and copy the files into craft/plugins/helpers/.

```
$ composer require carlcs/craft-helpers
```

  [1]: https://github.com/carlcs/craft-helpers/releases/latest

## String Helpers

#### truncate( length, preserve, separator )

Generates a truncated version of a string by cutting it off after the limit is reached.

The filter uses a fork of the [truncateHTML][2] library by Albert Lacarta. It counts single characters, this makes the length of the returned string very predictable, even for short length settings or text in languages with very long words like German.

It preserves whole words by default and is capable of closing HTML tags, in case the closing tag got cut off. It also makes sure to remove punctuation preceding the separator, for example you won't get a “!...” at the end.

- **`length`** (default `30`) – The max. number of characters after a string gets cut off.
- **`preserve`** (default `true`) – Makes the filter preserve whole words.
- **`separator`** (default `'...'`) – The characters to be appended to the truncated string.

```twig
{{ 'This is some very long text and it is causing a lot of problems.'|truncate(25) }}

{# outputs "This is some very long text..." #}
```

  [2]: https://github.com/carlcs/truncateHTML

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

- **`removeMultiSpaces`** (default `false`) – Controls whether multiple consecutive spaces should get stripped.

```twig
{% set string = 'In theory it removes .,:;*# but not ßøü.' %}
{{ string|stripPunctuation }}

{# outputs "In theory it removes but not ßøü" #}
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

## Requirements

- PHP 5.4+
