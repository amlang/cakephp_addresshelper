<?php
App::uses('Helper', 'View/Helper');
App::uses('HtmlHelper', 'View/Helper');

/**
 * Microdata Addresshelper class for easy working with addresses
 * @todo Kommentieren
 * @todo Beschreibung einfügen
 * @todo Readme verfassen
 * @author Alexander M. Lang<alexander.m.lang@gmail.com>
 * @copyright (c) 2013, Alexander M. Lang
 * @version 0.0.1
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 * @package View.Helper
 */
class AddressHelper extends HtmlHelper {

	const SCHEMA_ORG_URL = "http://www.schema.org/";

	public $helpers = array();

	public function __construct(\View $View, $settings = array()) {
		
		parent::__construct($View, $settings);
		//TAG additions
		$this->_tags += array(
			'adr' => '<address%s>%s</address>',
			'adrstart' => '<address%s>',
			'adrend' => '</address>',
			'content' => '<span%s>%s</span>'
		);
	}
	
	public function beginn($title, $options = array()) {
		$options['id'] = (strpos($title, 'hcard') === 0 ) ? Inflector::underscore(lcfirst($title)) : Inflector::underscore('hcard' . ucfirst($title));
		if (isset($options['class'])) {
			$options['class'] = 'hcard ' . $options['class'];
		}
		else {
			$options['class'] = 'hcard';
		}
		$tag = 'adrstart';
		return sprintf($this->_tags[$tag], $this->_parseAttributes($options));
	}

	private function _scope($content, $options = array(), $exclude = array()) {
		$_defaults = array('itemprop' => '');
		$_excludes = Hash::merge(array('format'),$exclude);
		if (isset($options['format']) && is_array($content)){
			$content = vsprintf($options['format'], $content);
		}
		$options = $this->_parseAttributes(Hash::merge($_defaults, $options), $_excludes);
		return sprintf($this->_tags['block'], $options, $content);
	}

	public function end() {
		return sprintf($this->_tags['adrend']);
	}

	/**
	 * Schema 
	 * i.e. Person, Organization, etc.
	 * @param string $name
	 * @return url i.e. http://schema.org/Person
	 */
	public static final function getSchema($name='') {
		return self::SCHEMA_ORG_URL . $name;
	}

	public function name($name, $options = array(), $itemOptions = array()) {
		$out = '';
		if (is_array($name)) {
			$honorific_suffix = '';
			foreach ($name as $itemprop => $itemcontent) {
				$_itemOptions = Hash::merge(array('itemprop' => $itemprop), $itemOptions);
				$item = sprintf($this->_tags['content'], $this->_parseAttributes($_itemOptions), $itemcontent);
				if ($itemprop == 'honorificPrefix') {
					$out = $item . ' ' . $out;
				}
				else if ($itemprop == 'honorificSuffix') {
					$honorific_suffix = $item;
				}
				else {
					//trailing space as seperator
					$out .= $item . ' ';
				}
			}
			$out .= $honorific_suffix;
		}
		//!is_array($name)
		else {
			$itemOptions = Hash::merge(array('itemprop' => 'name'), $itemOptions);
			$out .= sprintf($this->_tags['content'], $this->_parseAttributes($itemOptions, null, ' ', ''), $name);
		}
		if (isset($options['link'])) {
			if (is_array($options['link'])) {
				if (isset($options['link']['options'])) {
					Hash::merge(array('escape' => false), $options['link']['options']);
				}
				else {
					$options['link']['options'] = array();
				}
				$out = $this->link($out, $options['link']['url'], $options['link']['options']);
				unset($options['link']['options']);
			}
			//!is_array($options['link'])
			else {
				$out = $this->link($out, $options['link'], array('escape' => false));
			}
		}
		$_scopedefaults = array('itemprop' => 'name');
		$options = Hash::merge($_scopedefaults, $options);
		return $this->_scope($out, $options, array('url'));
	}

	public function founder($founder, $options = array(), $itemOptions = array()) {
		$_defaults = array('itemprop' => 'founder', 'itemscope', 'itemtype' => self::getSchema('Person'));
		$options = Hash::merge($_defaults, $options);
		return $this->name($founder, $options, $itemOptions);
	}

	public function address($address, $options = array(), $itemOptions = array()) {
		$_defaults = array(
			'itemprop' => 'address',
			'itemscope',
			'itemtype' => self::getSchema('PostalAddress')
		);
		$options = Hash::merge($_defaults, $options);
		return $this->_continuable($address, $options, $itemOptions);
	}

	public function contact($contact, $options = array(), $itemOptions = array()) {
		$_defaults = array(
			'itemprop' => 'contactPoint',
			'itemscope',
			'itemtype' => self::getSchema('ContactPoint')
		);
		$options = Hash::merge($_defaults, $options);
		return $this->_continuable($contact, $options, $itemOptions);
	}

	private function _continuable($values, $options = array(), $itemOptions = array()) {
		$out = '';
		$options = Hash::merge(array('link'=> true), $options);
		$_linkable = array('email','url');
		foreach ($values as $itemprop => $itemcontent) {
			
			$_itemOptions = Hash::merge(array('itemprop' => $itemprop), $itemOptions);
			$_out = sprintf($this->_tags['content'].' ', $this->_parseAttributes($_itemOptions), $itemcontent);
			if (in_array($itemprop, $_linkable) && $options['link']){
				$_out = $this->link($_out,($itemprop == 'email')?'mailto:'.$itemcontent:$itemcontent,array('escape' => false));
			}
			if (isset($options['format'])) {
				$out[] = $_out;
			}
			else {
				$out .= $_out;
			}
		}
		return $this->_scope($out, $options,array('link'));
	}

	/**
	 * 
	 * @param array $listitems
	 * @param array $options Html::options
	 */
	public function organization($listitems = array(), $options = array()) {
		$out = '';
		if (empty($listitems)) {
			$out = '';
		}
		else if (is_array($listitems)) {
			foreach ($listitems as $key => $values) {
				$_defaults = array('content' => array(), 'options' => array(), 'itemoptions' => array());
				$values = Hash::merge($_defaults, $values);
				if (method_exists($this, $key)) {
					$out .= $this->$key($values['content'], $values['options'], $values['itemoptions']);
				}
				else {
					$out .= $this->_continuable($values['content'], $values['options'], $values['itemoptions']);
				}
			}
		}
		$options['itemtype'] = self::getSchema(ucfirst(__FUNCTION__));
		return $this->_scope($out, $options);
	}

}
