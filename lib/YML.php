<?php

namespace Yandex\Market;

use DOMAttr;
use DOMDocument;
use DOMElement;
use DOMImplementation;
use Yandex\Market\YMLOffer;

class YML {

	/**
	 * @var DOMDocument
	 */
	private $__xml = NULL;
	/**
	 * @var DOMElement
	 */
	private $__xshop = NULL;
	/**
	 * @var DOMElement
	 */
	private $__xyml_catalog = NULL;
	private $__shop_company = '';
	private $__shop_name = '';
	private $__shop_url = '';
	private $__local_delivery_cost = NULL;
	private $__delivery_included = NULL;
	private $__allowed_currencies = array('RUR', 'RUB', 'USD', 'EUR', 'UAH', 'KZT');
	private $__offers = array();
	private $__currencies = array(
		'RUR' => 1,
		'USD' => 'CBRF',
		'EUR' => 'CBRF',
		'UAH' => 'CBRF',
		'KZT' => 'CBRF'
	);
	private $__categories = array();

	/**
	 * @param string $shop_name
	 * @param string $shop_company
	 * @param string $shop_url
	 * @param array $options eq array('currencies'=>array('USD'=>'29.2',...),...);
	 */
	public function __construct($shop_name, $shop_company, $shop_url, array $options = array())
	{

		$this->__shop_company = $shop_company;
		$this->__shop_name    = $shop_name;
		$this->__shop_url     = $shop_url;


		if (isset($options[ 'currencies' ]))
		{
			$this->setCurrencies($options[ 'currencies' ]);
		}

		if (isset($options[ 'categories' ]))
		{
			$this->setCategories($options[ 'categories' ]);
		}

		if (isset ($options[ 'local_delivery_cost' ]))
		{
			$this->setLocalDeliveryCost($options[ 'local_delivery_cost' ]);
		}
		if (isset ($options[ 'deliveryIncluded' ]))
		{
			$this->setDeliveryIncluded($options[ 'deliveryIncluded' ]);
		}

		$this->initXML();
	}

	public function setCurrencies(array $currencies)
	{
		$this->__currencies = $currencies;

		return $this;
	}

	public function setLocalDeliveryCost($value)
	{
		$this->__local_delivery_cost = $value;

		return $this;
	}

	public function setDeliveryIncluded($value)
	{
		$this->__delivery_included = (boolean) $value;

		return $this;
	}

	private function initXML()
	{
		$imp = new DOMImplementation();
		$dtd = $imp->createDocumentType('yml_catalog', '', "shops.dtd");

		$xml               = $imp->createDocument('', '', $dtd);
		$xml->encoding     = 'utf-8';
		$xml->formatOutput = TRUE;

		$yml_catalog = $xml->appendChild(new DOMElement('yml_catalog'));
		$yml_catalog->appendChild(new DOMAttr('date', date('Y-m-d H:i:s')));

		$shop = $yml_catalog->appendChild(new DOMElement('shop'));

		$this->__xyml_catalog = $yml_catalog;
		$this->__xshop        = $shop;
		$this->__xml          = $xml;

		$this->__xshop->appendChild(new DOMElement('name', htmlspecialchars($this->__shop_name)));
		$this->__xshop->appendChild(new DOMElement('company', htmlspecialchars($this->__shop_company)));
		$this->__xshop->appendChild(new DOMElement('url', htmlspecialchars($this->__shop_url)));

	}

	public function setOffers(array $offers)
	{
		$this->__offfers = $offers;
	}

	public function addOffer(YMLOffer $offer)
	{
		$this->__offers[ ] = $offer;
	}

	public function generateYML()
	{

		$xshop = $this->__xshop;

		$xcurrencies = $xshop->appendChild(new DOMElement('currencies'));
		foreach ($this->__currencies as $id => $rate)
		{
			$xcurr = $xcurrencies->appendChild(new DOMElement('currency'));
			$xcurr->appendChild(new DOMAttr('id', $id));
			$xcurr->appendChild(new DOMAttr('rate', $rate));
		}

		$xcategories = $xshop->appendChild(new DOMElement('categories'));
		foreach ($this->__categories as $cat)
		{
			$xcategories->appendChild($this->__xml->importNode($cat->toXML()->documentElement, TRUE));
		}

		$xoffers = $xshop->appendChild(new DOMElement('offers'));
		foreach ($this->__offers as $offer)
		{
			$xoffers->appendChild($this->__xml->importNode($offer->toXML()->documentElement, TRUE));
		}
	}

	/**
	 * @return string
	 */
	public function saveYML()
	{

		return $this->__xml->saveXML();
	}

	public function addCategory(YMLCategory $category)
	{
		$this->__categories[ ] = $category;

		return $this;
	}

	public function setCurrency($currency_code, $rate)
	{
		$this->__currencies[ $currency_code ] = $rate;

		return $this;
	}

	/**
	 * @return DOMDocument
	 */
	public function getXML()
	{
		return $this->__xml;
	}

}