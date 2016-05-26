<?php

/**
 * This file is part of urodoz/truncateHTML.
 *
 * (c) Albert Lacarta <urodoz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urodoz\Truncate;

use Urodoz\Truncate\TruncateService;

/**
 * TruncateService
 *
 * @package   org.urodoz.truncatehtml
 * @author    Albert Lacarta <urodoz@gmail.com>
 * @license   http://www.opensource.org/licenses/MIT The MIT License
 */
class TruncateServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var TruncateService
     */
    private $truncateService;

    public function setUp()
    {
        $this->truncateService = new TruncateService();
    }

    public function provider()
    {
        return array(
            array('<p>Hello world!</p>', 10, '<p>Hello...</p>'),
            array('<p>Hello world!</p>', 30, '<p>Hello world!</p>'),
            array('<p>Hello <b>World!</b> blah blah blah</p>', 20, '<p>Hello <b>World!</b>...</p>'),
            array('<b>One</b><b>Two</b> <b>Three</b>', 10, '<b>One</b><b>Two</b>...'),
            array('<p>Hello world! <img src="http://img.test.com/img.jpg" /></p>', 10, '<p>Hello...</p>'),
            array('<p>Hello world! <img src="http://img.test.com/img.jpg" /></p>', 20, '<p>Hello world! <img src="http://img.test.com/img.jpg" /></p>'),
        );
    }

    /**
     * @dataProvider provider
     */
    public function testTruncate($htmlString, $length, $expected)
    {
        $truncatedText = $this->truncateService->truncate($htmlString, $length);
        $this->assertEquals($expected, $truncatedText);
    }

}
