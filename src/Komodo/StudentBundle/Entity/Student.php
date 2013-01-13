<?php

namespace Komodo\StudentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="student")
 */
class Student {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $lastname;
    
    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $gender;
    
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $year;
    
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $class;
    
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $level;
    
    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $address;
    
    /**
     * @var string $zipCode
     *
     * @ORM\Column(name="zip_code", type="string", length=10, nullable=true)
     */
    private $zipCode;

    /**
     * @var string $city
     *
     * @ORM\Column(name="city", type="string", length=50, nullable=true)
     */
    private $city;

    /**
     * @var string $state
     *
     * @ORM\Column(name="state", type="string", length=50, nullable=true)
     */
    private $state;

    /**
     * @var string $phone
     *
     * @ORM\Column(name="phone", type="string", length=16, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(name="mother_name", type="string", length=50, nullable=true)
     */
    private $motherName;
    
    /**
     * @ORM\Column(name="mother_lastname", type="string", length=50, nullable=true)
     */
    private $motherLastname;
    
    /**
     * @ORM\Column(name="father_name", type="string", length=50, nullable=false)
     */
    private $fatherName;
    
    /**
     * @ORM\Column(name="father_lastname", type="string", length=50, nullable=false)
     */
    private $fatherLastname;
    
    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Student
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Student
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return Student
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    
        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set year
     *
     * @param string $year
     * @return Student
     */
    public function setYear($year)
    {
        $this->year = $year;
    
        return $this;
    }

    /**
     * Get year
     *
     * @return string 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set class
     *
     * @param string $class
     * @return Student
     */
    public function setClass($class)
    {
        $this->class = $class;
    
        return $this;
    }

    /**
     * Get class
     *
     * @return string 
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set level
     *
     * @param string $level
     * @return Student
     */
    public function setLevel($level)
    {
        $this->level = $level;
    
        return $this;
    }

    /**
     * Get level
     *
     * @return string 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Student
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     * @return Student
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    
        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string 
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Student
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Student
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Student
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set motherName
     *
     * @param string $motherName
     * @return Student
     */
    public function setMotherName($motherName)
    {
        $this->motherName = $motherName;
    
        return $this;
    }

    /**
     * Get motherName
     *
     * @return string 
     */
    public function getMotherName()
    {
        return $this->motherName;
    }

    /**
     * Set motherLastname
     *
     * @param string $motherLastname
     * @return Student
     */
    public function setMotherLastname($motherLastname)
    {
        $this->motherLastname = $motherLastname;
    
        return $this;
    }

    /**
     * Get motherLastname
     *
     * @return string 
     */
    public function getMotherLastname()
    {
        return $this->motherLastname;
    }

    /**
     * Set fatherName
     *
     * @param string $fatherName
     * @return Student
     */
    public function setFatherName($fatherName)
    {
        $this->fatherName = $fatherName;
    
        return $this;
    }

    /**
     * Get fatherName
     *
     * @return string 
     */
    public function getFatherName()
    {
        return $this->fatherName;
    }

    /**
     * Set fatherLastname
     *
     * @param string $fatherLastname
     * @return Student
     */
    public function setFatherLastname($fatherLastname)
    {
        $this->fatherLastname = $fatherLastname;
    
        return $this;
    }

    /**
     * Get fatherLastname
     *
     * @return string 
     */
    public function getFatherLastname()
    {
        return $this->fatherLastname;
    }
}