<?php 
namespace App\Entity;

use Zend\Form\Annotation;

class AddressEntity 
{
	/**
	 * @Annotation\Exclude()
	 */
	private $id;
	
	/**
	 * A city name
	 * 
	 * @var string
	 * 
	 * @Annotation\Filter({"name":"StringTrim"})
	 */
	private $city;	
}