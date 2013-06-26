<?php

namespace Yandex\Market;

use DOMAttr;
use DOMDocument;
use DOMElement;

class YMLCategory {

	private $__id = NULL;
	private $__name = NULL;
	private $__parent_id = NULL;
	private $__tid = NULL;
	private $__yid = NULL;
	private $__xml = NULL;

	/**
	 * @param int $id
	 * @param string $name
	 * @param int|YMLCategory $parent_id
	 * @param array $options
	 */
	public function __construct($id, $name, $parent_id = NULL, array $options = array())
	{
		$this->__id   = $id;
		$this->__name = htmlspecialchars($name);

		if ($parent_id instanceof YMLCategory)
		{
			$this->__parent_id = $parent_id->getId();
		}
		else
		{
			$this->__parent_id = $parent_id;
		}
	}

	public function getId()
	{
		return $this->__id;
	}

	public function getName()
	{
		return $this->__name;
	}

	public function getParentId()
	{
		return $this->__parent_id;
	}

	/**
	 * @return DOMDocument
	 */
	function toXML()
	{
		$xml  = new DOMDocument();
		$xcat = $xml->appendChild(new DOMElement('category', $this->__name));
		$xcat->appendChild(new DOMAttr('id', $this->__id));
		if ($this->__parent_id)
		{
			$xcat->appendChild(new DOMAttr('parentId', $this->__parent_id));
		}

		return $xml;
	}

}
