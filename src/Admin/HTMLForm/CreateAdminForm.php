<?php

namespace Ara\Admin\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Ara\User\User;

/**
 * Example of FormModel implementation.
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class CreateAdminForm extends FormModel
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
                "active" => [
                    "type"      => "checkbox",
                    "label"     => "Aktiv",
                    "checked"   => true
                ],
                "admin" => [
                    "type"      => "checkbox",
                    "label"     => "Admin",
                    "checked"   => false
                ],
                "password" => [
                    "type"  => "password",
                    "label" => "Lösenord",
                    "class" => "mdl-textfield__input",
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Skapa användare",
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
        $email = $this->form->value("email");
        $user = new User();
        $user->setDb($this->di->get("db"));

        if (!$user->isEmailUnique($email)) {
            $this->form->rememberValues();
            $this->form->addOutput("E-postadressen är redan registrerad.");
            return false;
        }

        $user->email = $email;
        $user->name = $this->form->value("name");
        $user->setPassword($this->form->value("password"));
        $user->created = date("Y-m-d H:i:s");
        $user->active = $this->form->value("active") ? 1 : 0;
        $user->admin = $this->form->value("admin") ? 1 : 0;
        $user->save();

        $this->di->get("response")->redirect("user");

        return true;
    }
}
