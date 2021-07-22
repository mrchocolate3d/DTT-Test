<?php

class Rooms extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $width;

    /**
     *
     * @var integer
     */
    protected $length;

    /**
     *
     * @var integer
     */
    protected $height;

    /**
     *
     * @var integer
     */
    protected $typeID;

    /**
     *
     * @var integer
     */
    protected $houseID;

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
     * Method to set the value of field width
     *
     * @param integer $width
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Method to set the value of field length
     *
     * @param integer $length
     * @return $this
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Method to set the value of field height
     *
     * @param integer $height
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Method to set the value of field typeID
     *
     * @param integer $typeID
     * @return $this
     */
    public function setTypeID($typeID)
    {
        $this->typeID = $typeID;

        return $this;
    }

    /**
     * Method to set the value of field houseID
     *
     * @param integer $houseID
     * @return $this
     */
    public function setHouseID($houseID)
    {
        $this->houseID = $houseID;

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
     * Returns the value of field width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Returns the value of field length
     *
     * @return integer
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Returns the value of field height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Returns the value of field typeID
     *
     * @return integer
     */
    public function getTypeID()
    {
        return $this->typeID;
    }

    /**
     * Returns the value of field houseID
     *
     * @return integer
     */
    public function getHouseID()
    {
        return $this->houseID;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("sql11426251");
        $this->setSource("rooms");
        $this->belongsTo('typeID', '\RoomType', 'id', ['alias' => 'Roomtype']);
        $this->belongsTo('houseID', '\Houses', 'id', ['alias' => 'Houses']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Rooms[]|Rooms|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Rooms|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
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
            'width' => 'width',
            'length' => 'length',
            'height' => 'height',
            'typeID' => 'typeID',
            'houseID' => 'houseID'
        ];
    }

}
