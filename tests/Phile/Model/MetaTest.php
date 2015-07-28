<?php
/**
 * Created by PhpStorm.
 * User: franae
 * Date: 21.08.14
 * Time: 23:51
 */

namespace PhileTest\Model;


/**
 * the MetaTest class
 *
 * @author  Frank Nägler
 * @link    https://philecms.com
 * @license http://opensource.org/licenses/MIT
 * @package PhileTest
 */
class MetaTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var string meta data test string
	 */
	protected $metaTestData1 = "/*
Title: Welcome
Spaced Key: Should become underscored
Nested:
    nested a: 1
    nested B: 2
Description: This description will go in the meta description tag
Date: 2014/08/01
*/
";

	/**
	 * @var string meta data test string
	 */
	protected $metaTestData2 = "<!--
Title: Welcome
Description: This description will go in the meta description tag
Date: 2014-08-01
-->
";

	/**
	 *
	 */
	public function testCanGetMetaProperty() {
		$meta = new \Phile\Model\Meta($this->metaTestData1);
		$this->assertEquals('Welcome', $meta->get('title'));
		$this->assertEquals('This description will go in the meta description tag', $meta->get('description'));
		$meta2 = new \Phile\Model\Meta($this->metaTestData2);
		$this->assertEquals('Welcome', $meta2->get('title'));
		$this->assertEquals('This description will go in the meta description tag', $meta2->get('description'));
	}

	public function testCanGetFormatedDate() {
		$meta = new \Phile\Model\Meta($this->metaTestData1);
		$this->assertEquals('1st Aug 2014', $meta->getFormattedDate());
		$meta2 = new \Phile\Model\Meta($this->metaTestData2);
		$this->assertEquals('1st Aug 2014', $meta2->getFormattedDate());
	}

	public function testGetIfNotMetaDataOnPage() {
		$meta = new \Phile\Model\Meta('…');
		$this->assertEquals([], $meta->getAll());
		$this->assertNull($meta->get('title'));
	}

	public function testSpacedKey() {
		$meta = new \Phile\Model\Meta($this->metaTestData1);
		$this->assertEquals('Should become underscored', $meta->get('spaced_key'));
	}

	public function testNested() {
		$meta = new \Phile\Model\Meta($this->metaTestData1);
		$this->assertEquals(['nested_a' => 1, 'nested_b' => 2], $meta->get('nested'));
	}
}
