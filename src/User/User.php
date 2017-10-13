<?php

namespace Ara\User;

use \Anax\Database\ActiveRecordModel;

/**
 * A database driven omdel
 */
class User extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "user";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $email;
    public $password;
    public $name;
    public $created;
    public $updated;
    public $deleted;
    public $active;
    public $admin;

    /**
     * Set the password.
     *
     * @param string $password the password to use.
     *
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this->password;
    }

    /**
     * Verify the acronym and the password, if successful the object contains
     * all details from the database row.
     *
     * @param string $acronym  acronym to check.
     * @param string $password the password to use.
     *
     * @return boolean true if acronym and password matches, else false.
     */
    public function verifyPassword($email, $password)
    {
        $this->find("email", $email);
        return password_verify($password, $this->password);
    }
    /**
     * Check whether the user account is active or disabled
     *
     * @return boolean true if user is active
     */
    public function isActive($email)
    {
        $this->find("email", $email);
        return $this->active;
    }

    /**
     * Check whether the user is an Admin
     *
     * @return boolean true if user is an Admin
     */
    public function isAdmin($email)
    {
        $this->find("email", $email);
        return $this->admin;
    }

    /**
     * Check whether the user is an Admin
     *
     * @return boolean true if user is an Admin
     */
    public function isEmailUnique($email)
    {
        $this->find("email", $email);
        if (isset($this->id)) {
            return false;
        }
        return true;
    }
}
