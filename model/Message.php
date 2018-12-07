<?php


class Message
{
    private $id;
    private $inhoud;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getInhoud()
    {
        return $this->inhoud;
    }

    public function setInhoud($inhoud): void
    {
        $this->inhoud = $inhoud;
    }

}