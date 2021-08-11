<?php

class Houses extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $street;

    /**
     *
     * @var integer
     */
    protected $number;

    /**
     *
     * @var string
     */
    protected $addition;

    /**
     *
     * @var string
     */
    protected $zipCode;

    /**
     *
     * @var string
     */
    protected $city;

    /**
     *
     * @var integer
     */
    protected $userID;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field street
     *
     * @param string $street
     * @return $this
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Method to set the value of field number
     *
     * @param integer $number
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Method to set the value of field addition
     *
     * @param string $addition
     * @return $this
     */
    public function setAddition($addition)
    {
        $this->addition = $addition;

        return $this;
    }

    /**
     * Method to set the value of field zipCode
     *
     * @param string $zipCode
     * @return $this
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Method to set the value of field city
     *
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Method to set the value of field userID
     *
     * @param integer $userID
     * @return $this
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Returns the value of field number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Returns the value of field addition
     *
     * @return string
     */
    public function getAddition()
    {
        return $this->addition;
    }

    /**
     * Returns the value of field zipCode
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Returns the value of field city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Returns the value of field userID
     *
     * @return integer
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("EgFtyzVzTW");
        $this->setSource("houses");
        $this->hasMany('id', 'Rooms', 'houseID', ['alias' => 'Rooms']);
        $this->belongsTo('userID', '\Users', 'id', ['alias' => 'Users']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Houses[]|Houses|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Houses|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'street' => 'street',
            'number' => 'number',
            'addition' => 'addition',
            'zipCode' => 'zipCode',
            'city' => 'city',
            'userID' => 'userID'
        ];
    }

}
