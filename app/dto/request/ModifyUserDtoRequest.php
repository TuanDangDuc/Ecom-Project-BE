<?php

class ModifyUserDtoRequest {
    public ?string $username;
    public ?string $email;
    public ?string $fullName;
    public ?string $sex;
    public ?DateTime $dateOfBirth;
    public ?string $avatarUrl;

    public function __construct($data) {
        $this->username = $data['username'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->fullName = $data['fullName'] ?? null;
        $this->sex = $data['sex'] ?? null;
        $dob = $data['dateOfBirth'] ?? null;
        $this->dateOfBirth = $dob ? new DateTime($dob) : null;
        $this->avatarUrl = $data['avatarUrl'] ?? null;

    }
}