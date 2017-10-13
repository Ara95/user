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
class CreateUserForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di)
    {
        parent::__construct($di);
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
                ],
                "name" => [
                    "type"  => "text",
                    "label" => "Namn",

                    "class" => "mdl-textfield__input",
                    "placeholder" => "John doe",
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
                    "value" => "Skapa",
                    "callback" => [$this, "callbackSubmit"],
                    "class" => "mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent",
                ],
            ]
        );
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
            $this->form->addOutput("Lösenordet stämmer inte överens.");
            return false;
        }

        $user = new User();
        $user->setDb($this->di->get("db"));

        if (!$user->isEmailUnique($email)) {
            $this->form->rememberValues();
            $this->form->addOutput("E-postadressen är redan registrerad.");
            return false;
        }

        $user->email = $email;
        $user->name = $name;
        $user->setPassword($password);
        $user->created = date("Y-m-d H:i:s");
        $user->active = 1;
        $user->admin = 0;
        $user->save();

        $this->di->get("response")->redirect("user");

        return true;
    }
}
