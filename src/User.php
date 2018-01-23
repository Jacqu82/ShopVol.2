<?php


class User
{
    private $id;
    private $username;
    private $email;
    private $password1;
    private $password2;
    private $city;
    private $postalCode;
    private $street;
    private $houseNr;
    private $createdAt;


    public function __construct()
    {
        $this->id = -1;
        $this->username = '';
        $this->email = '';
        $this->password1 = '';
        $this->password2 = '';
        $this->city = '';
        $this->postalCode = '';
        $this->street = '';
        $this->houseNr = '';
        $this->createdAt = '';
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword1()
    {
        return $this->password1;
    }

    /**
     * @param string $password1
     * @return $this
     */
    public function setPassword1($password1)
    {
        $this->password1 = password_hash($password1, PASSWORD_BCRYPT);
        return $this;
    }

    /**
     * @param $hash
     * @return $this
     */
    public function setHash($hash)
    {
        $this->password1 = $hash;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     * @return $this
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return $this
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string
     */
    public function getHouseNr()
    {
        return $this->houseNr;
    }

    /**
     * @param string $houseNr
     * @return $this
     */
    public function setHouseNr($houseNr)
    {
        $this->houseNr = $houseNr;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
