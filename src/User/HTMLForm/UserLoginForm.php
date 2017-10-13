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
class UserLoginForm extends FormModel
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
                    "class" => "mdl-textfield__input",
                    "placeholder" => "doe@dbwebb.com",
                    //"description" => "Here you can place a description.",
                    //"placeholder" => "Here is a placeholder",
                ],

                "password" => [
                    "type"        => "password",
                    "label"       => "Lösenord",
                    "class"       => "mdl-textfield__input",
                    //"description" => "Here you can place a description.",
                    //"placeholder" => "Here is a placeholder",
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Login",
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
        $password    = $this->form->value("password");

        $user = new User();
        $user->setDb($this->di->get("db"));
        $res = $user->verifyPassword($email, $password);

        if (!$res) {
            $this->form->rememberValues();
            $this->form->addOutput("Användarnamnet eller lösenordet stämmer inte överens.", "flash");

            return false;
        }

        if (!$user->isActive($email)) {
            $this->form->addOutput("Kontot är inte aktiverat.", "flash");
            return false;
        }

        $this->di->get("session")->set("user", $user->email);
        $this->di->get("session")->set("userid", $user->id);


        $this->di->get("response")->redirect("user");
        return true;
    }
}
