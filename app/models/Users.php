<?php

class Users
{
    private int $id;
    private string $username;
    private string $password;
    private string $email;
    private string $fullName;
    private ?Sex $sex = null;
    private ?Role $role = null;
    private ?DateTime $dateOfBirth = null;
    private string $avatarUrl;
    private ?AccountStatus $accountStatus = null;
    private ?DateTime $createdAt = null;
    private ?DateTime $updatedAt = null;

    public function __construct(String $username, String $password, Role $role)
    {
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
    }
    
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getSex(): ?Sex
    {
        return $this->sex;
    }

    public function setSex(Sex $sex): void
    {
        $this->sex = $sex;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(Role $role): void
    {
        $this->role = $role;
    }

    public function getDateOfBirth(): ?DateTime
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(DateTime $dob): void
    {
        $this->dateOfBirth = $dob;
    }

    public function getAvatarUrl(): string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(string $url): void
    {
        $this->avatarUrl = $url;
    }

    public function getAccountStatus(): ?AccountStatus
    {
        return $this->accountStatus;
    }

    public function setAccountStatus(AccountStatus $status): void
    {
        $this->accountStatus = $status;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $dt): void
    {
        $this->createdAt = $dt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $dt): void
    {
        $this->updatedAt = $dt;
    }
}
