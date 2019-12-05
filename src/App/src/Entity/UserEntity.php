<?php 
namespace App\Entity;

use Zend\Form\Annotation;

/**
 * @Annotation\Name("user")
 * @Annotation\Hydrator("Zend\Hydrator\ClassMethodsHydrator")
 */
class UserEntity 
{
	/**
	 * @Annotation\Exclude()
	 */
	private $id;
	
	/**
	 * Full name of user
	 * 
	 * @var string
	 * 
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":25}})
     * @Annotation\Attributes({"type":"text"})
     */
	private $name;
	
	
	/**
	 * Current Age
	 * 
	 * @var int
	 * 
	 * @Annotation\Filter({"name": "ToInt"})
	 * @Annotation\Validator({"name":"Between", "options":{"min":0, "max":150}}) // Range 0 - 150
	 * @Annotation\Validator({"name": "Digits"})
	 * @Annotation\Attributes({"type":"integer"})
	 */
	private $age;
	
	
	/**
	 * This is the password
	 * 
	 * @var string
	 * 
	 * @Annotation\Validator({"name":"StringLength", "options":{"min":8, "max":100}})
	 * @Annotation\Attributes({"type":"text"})
	 * 
	 */
	private $password;
	
	
	// ...
	
	
	/**
	 * @var AddressEntity
	 * @Annotation\OneToOne
	 */
	private $address;
	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return the $age
	 */
	public function getAge() {
		return $this->age;
	}

	/**
	 * @return the $password
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @return the $address
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param number $age
	 */
	public function setAge($age) {
		$this->age = $age;
	}

	/**
	 * @param field_type $password
	 */
	public function setPassword($password) 
	{
		$this->password = crypt($password, time());
	}

	/**
	 * @param \App\Entity\AddressEntity $address
	 */
	public function setAddress($address) {
		$this->address = $address;
	}

	
	
	
}