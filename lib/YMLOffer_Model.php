<?php

namespace Yandex\Market;

class YMLOffer_Model extends YMLOffer {

	/**
	 * Производитель
	 * @var string
	 */
	public $vendor = NULL;
	/**
	 * Код товара (указывается код производителя)
	 * @var string
	 */
	public $vendorCode = NULL;
	/**
	 * Модель
	 * @var string
	 */
	public $model = NULL;
	/**
	 * Элемент предназначен для отметки товаров, имеющих официальную гарантию производителя.
	 * @var string
	 */
	public $manufacturer_warranty = NULL;
	/**
	 * Элемент предназначен обозначения товара, который можно скачать.
	 * @var  boolean
	 */
	public $downloadable = NULL;

	public function getType()
	{
		return '';
	}

	public function getOrderArray()
	{
		$order_str = '
           url, buyurl?, price, wprice?,
           currencyId, xCategory?, categoryId+,
           picture?, delivery?, deliveryIncluded?, local_delivery_cost?,
           orderingTime?,
           name, vendor?,vendorCode?,
           aliases?,
           additional*, description?, sales_notes?, promo?,
           manufacturer_warranty?, country_of_origin?, downloadable?

           ';
		$order_str = preg_replace('/[\s\?\*\+]/', '', $order_str);

		$arr = explode(',', $order_str);

		return $arr;

	}

}
