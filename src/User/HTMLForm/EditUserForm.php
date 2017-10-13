<?php

namespace Ara\User\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Ara\User\User;

/**
 * Example of FormModel implementation.
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class EditUserForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     * @param string             $email to update
     */
    public function __construct(DIInterface $di, $email)
    {
        parent::__construct($di);

        $user = $this->getUserDetails($email);

        $this->form->create(
            [
                "id" => __CLASS__,
                "wrapper-element" => "div",
                "class" => "mdl-textfield mdl-js-textfield",
            ],
            [
                "email" => [
                    "type"        => "text",
                    "label" => "E-post",
                    "class" => "mdl-textfield__input",
                    "placeholder" => "doe@dbwebb.com",
                    "value" => $user->email,
                ],
                "name" => [
                    "type"  => "text",
                    "label" => "Namn",

                    "class" => "mdl-textfield__input",
                    "placeholder" => "John doe",
                    "value" => $user->name,
                ],
                "password" => [
                    "type"  => "password",
                    "label" => "Lösenord",
                    "class" => "mdl-textfield__input",
                ],
                "password-again" => [
                    "type"       => "password",
                    "label" => "Upprepa lösenord",
                    "validation" => [
                    "match" => "password"
                    ],
                    "class" => "mdl-textfield__input",
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Redigera",
                    "callback" => [$this, "callbackSubmit"],
                    "class" => "mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent",
                ],
            ]
        );
    }

    /**
     * Get user details to load form with.
     *
     * @param string $email get details on item with email.
     *
     * @return object User details.
     */
    public function getUserDetails($email)
    {
        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->find("email", $email);
        return $user;
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        $email       = $this->form->value("email");
        $name        = $this->form->value("name");
        $password      = $this->form->value("password");
        $passwordAgain = $this->form->value("password-again");
        // Check password matches

        if ($password !== $passwordAgain) {
            $this->form->rememberValues();
            $this->form->addOutput("Password did not match.");
            return false;
        }

        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->find("email", $this->form->value("email"));
        $user->email = $email;
        $user->name = $name;
        $user->updated = date("Y-m-d H:i:s");

        if (!empty($password)) {
            $user->setPassword($password);
        }

        $user->save();

        $this->di->get("response")->redirect("user");

        return true;
    }
}
