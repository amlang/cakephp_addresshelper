<?php

App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('AddressHelper', 'View/Helper');

/**
 * AddressHelper Test Case
 *
 */
class AddressHelperTest extends CakeTestCase {

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->AddressHelper = new AddressHelper($View);
		$this->Html = new HtmlHelper($View);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->AddressHelper);

		parent::tearDown();
	}

	/**
	 * testBeginn method
	 *
	 * @return void
	 */
	public function testBeginn() {
		$expected = $this->AddressHelper->beginn('oneword');
		$this->assertContains('id="hcard_oneword"', $expected);
		$this->assertContains('class="hcard"', $expected);

		$expected = $this->AddressHelper->beginn('two words');
		$this->assertContains('id="hcard_two words"', $expected);
		$this->assertContains('class="hcard"', $expected);

		$expected = $this->AddressHelper->beginn('withclass', array('class' => 'test'));
		$this->assertContains('id="hcard_withclass"', $expected);
		$this->assertContains('class="hcard test"', $expected);
		$this->assertEqual('<address class="hcard test" id="hcard_withclass">', $expected, $expected);

		$expected = $this->AddressHelper->beginn('withclass', array('class' => 'test', 'data-target' => '#main'));
		$this->assertContains('data-target="#main"', $expected);
	}

	/**
	 * testEnd method
	 *
	 * @return void
	 */
	public function testEnd() {
		$this->assertEqual('</address>', $this->AddressHelper->end());
	}

	/**
	 * testGetSchema method
	 *
	 * @return void
	 */
	public function testGetSchema() {
		$this->assertEqual(AddressHelper::SCHEMA_ORG_URL, AddressHelper::getSchema());
		$this->assertEqual(AddressHelper::SCHEMA_ORG_URL . 'somesample', AddressHelper::getSchema('somesample'));
	}

	/**
	 * testName method
	 *
	 * @return void
	 */
	public function testNameSingle() {
		$expected = $this->AddressHelper->name('SampleName');
		$this->assertContains('<div itemprop="name">', $expected);
		$this->assertContains('<span itemprop="name">', $expected);
		$this->assertContains('SampleName', $expected);
	}

	public function testNameArray() {
		$expected = $this->AddressHelper->name(array('SampleName'));
		$this->assertContains('<div itemprop="name">', $expected);
		$this->assertContains('<span itemprop="0">', $expected);
		$this->assertContains('SampleName', $expected);

		$expected = $this->AddressHelper->name(array('legalName' => 'SampleName'));
		$this->assertContains('<div itemprop="name">', $expected);
		$this->assertContains('<span itemprop="legalName">', $expected);
		$this->assertContains('SampleName', $expected);

		$expected = $this->AddressHelper->name(array('legalName' => 'SampleName'), array('url' => 'abc.de'));
		$this->assertContains('<div itemprop="name">', $expected);
		$this->assertContains('<span itemprop="legalName">', $expected);
		$this->assertNotContains('link="abc.de"', $expected);
		$this->assertNotContains('<a href="/abc.de">', $expected);
		$this->assertContains('SampleName', $expected);

		$expected = $this->AddressHelper->name(array('legalName' => 'SampleName'), array('link' => array('url' => 'abc.de')));
		$this->assertContains('div itemprop="name"', $expected);
		$this->assertContains(htmlspecialchars('span itemprop="legalName"'), $expected);
		$this->assertContains('link="abc.de"', $expected);
		$this->assertContains('<a href="/abc.de">', $expected);
		$this->assertContains('SampleName', $expected);

		$expected = $this->AddressHelper->name(array('legalName' => 'SampleName'), array('link' => array('url' => 'abc.de', 'options' => array('class' => 'asc'))));
		$this->assertContains('div itemprop="name"', $expected);
		$this->assertContains(htmlspecialchars('span itemprop="legalName"'), $expected);
		$this->assertContains('link="abc.de"', $expected);
		$this->assertContains('<a href="/abc.de" class="asc">', $expected);
		$this->assertContains('SampleName', $expected);

		$expected = $this->AddressHelper->name(array('legalName' => 'SampleName'), array('link' => 'abc.de'));
		$this->assertContains('<div itemprop="name" link="abc.de">', $expected);
		$this->assertContains('<span itemprop="legalName">', $expected);
		$this->assertContains('<a href="/abc.de">', $expected);
		$this->assertContains('SampleName', $expected);
	}

	/**
	 * testFounder method
	 *
	 * @return void
	 */
	public function testFounder() {
		$testarr = array(
			'givenName' => 'John',
			'additionalName' => 'M.',
			'familyName' => 'Doe'
		);
		$test = function($expected) use($testarr) {
			foreach ($testarr as $k => $v) {
				$this->assertContains('<span itemprop="' . $k . '">' . $v, $expected);
			}
		};
		$expected = $this->AddressHelper->founder($testarr);

		$this->assertContains('itemprop="founder"', $expected);
		$this->assertContains('itemscope', $expected);
		$this->assertContains('itemtype="http://www.schema.org/Person">', $expected);
		$test($expected);

		$testarr += array('honorificPrefix' => 'Dr.', 'honorificSuffix' => 'M.D.');
		$expected = $this->AddressHelper->founder($testarr);
		$test($expected);
	}

	/**
	 * testAddress method
	 *
	 * @return void
	 */
	public function testAddress() {
		$testarr = array(
			'streetAddress' => 'Corinthweg 1',
			'addressCountry' => 'DE',
			'postalCode' => '66802',
			'addressLocality' => 'Überherrn',
			'addressRegion' => 'Saarland'
		);
		$expected = $this->AddressHelper->address($testarr);
	}

	/**
	 * testContact method
	 *
	 * @return void
	 */
	public function testContact() {
		$expected = $this->AddressHelper->contact(array(
			'telephone' => '0126/123456',
			'faxNumber' => '0126/123457',
			'email' => 'ma.muster@example.com',
			'url' => 'http://www.some-example.de'
			), array(
			'link' => true,
			'format' => '<span class="label">Telefon: </span>%s <br /><span class="label">Fax: </span>%s <br /><span class="label">Email: </span>%s<br /><span class="label">WWW: </span>%s'
			), array('class' => 'prop'));
		$this->assertContains('itemprop="contactPoint"', $expected);
		$this->assertContains('itemscope', $expected);
		$this->assertContains('itemtype="http://www.schema.org/ContactPoint"', $expected);
		//CODECOVERAGE

		$expected = $this->AddressHelper->contact(array(
			'telephone' => '0126/123456',
			'faxNumber' => '0126/123457',
			'email' => 'ma.muster@example.com',
			'url' => 'http://www.some-example.de'
			), array(
			'link' => true,
			'format' => '<span class="label">Telefon: </span>%s <br /><span class="label">Fax: </span>%s <br /><span class="label">Email: </span>%s<br /><span class="label">WWW: </span>%s'
			), array('class' => 'prop'));

		$expected = $this->AddressHelper->contact(array(
			'telephone' => '0126/123456',
			'faxNumber' => '0126/123457',
			'email' => 'ma.muster@example.com',
			'url' => 'http://www.some-example.de'
			), array(
			'link' => false,
//			'format' => '<span class="label">Telefon: </span>%s <br /><span class="label">Fax: </span>%s <br /><span class="label">Email: </span>%s<br /><span class="label">WWW: </span>%s'
			), array('class' => 'prop'));
	}

	/**
	 * testOrganization method
	 *
	 * @return void
	 */
	public function testOrganization() {
		//CODEVERAGE - EMPTY
		$expected = $this->AddressHelper->organization(array());
		//--CODEVERAGE - EMPTY

		$expected = $this->AddressHelper->organization(array(
			'name' => array(
				'content' => array(
					'legalName' => 'Firma<br />Büroservice für den Handwerker Cornelia Lang'
				),
				'options' => array(
					'url' => $this->Html->url('asd.cd', true)
				)
			),
			'founder' => array(
				'content' => array(
					'givenName' => 'Cornelia',
					'familyName' => 'Lang'
				)
			),
			'address' => array(
				'content' => array(
					'streetAddress' => 'Corinthweg 1',
					'addressCountry' => 'DE',
					'postalCode' => '66802',
					'addressLocality' => 'Überherrn',
					'addressRegion' => 'Saarland'
				),
				'options' => array(
					'format' => '%s<br />%s-%s,%s %s'
				)
			),
			'contact' => array(
				'content' => array(
					'telephone' => '0126/123456',
					'faxNumber' => '0126/123457',
					'email' => 'ma.muster@example.com'
				),
				'options' => array(
					'format' => 'Telefon: %s <br /> Fax: %s <br /> Email: %s'
				),
				'itemoptions' => array('class' => 'prop')
			)
			), array(
			'class' => 'organization'
		));
	}

}
