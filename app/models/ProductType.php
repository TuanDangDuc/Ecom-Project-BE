<?php
class ProductType {
    private int $id;
    private String $name;
    private ?String $description;

    public function __construct(String $name = "", ?String $description = null)
    {
        $this->name = $name;
        $this->description = $description;
    }
    public function setName(String $name): void
    {
        $this->name = $name;
    }

    public function getName(): String
    {
        return $this->name;
    }

    public function setDescription(?String $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): ?String
    {
        return $this->description;
    }
}