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
class EditAdminForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     * @param integer             $id to update
     */
    public function __construct(DIInterface $di, $id)
    {
        parent::__construct($di);

        $user = $this->getUserDetails($id);

        $this->form->create(
            [
                "id" => __CLASS__,
                "wrapper-element" => "div",
                "class" => "mdl-textfield mdl-js-textfield",
            ],
            [
                "id" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "readonly" => true,
                    "class" => "mdl-textfield__input",
                    "value" => $user->id,
                ],
                "email" => [
                    "type"        => "text",
                    "label" => "E-post",
                    "class" => "mdl-textfield__input",
                    "value" => $user->email,
                ],
                "name" => [
                    "type"  => "text",
                    "label" => "Namn",
                    "class" => "mdl-textfield__input",
                    "value" => $user->name,
                ],
                "active" => [
                    "type"      => "checkbox",
                    "label"     => "Aktiv",
                    "checked"   => $user->active
                ],
                "admin" => [
                    "type"      => "checkbox",
                    "label"     => "Admin",
                    "checked"   => $user->admin
                ],
                "password" => [
                    "type"  => "password",
                    "label" => "Lösenord",
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
     * @param string $id get details on item with id.
     *
     * @return object User details.
     */
    public function getUserDetails($id)
    {
        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->find("id", $id);
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
        $admin = $this->form->value("admin");
        $active = $this->form->value("active");
        $email = $this->form->value("email");
        $password      = $this->form->value("password");

        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->find("id", $this->form->value("id"));


        if ($user->email != $email && !$user->isEmailUnique($email)) {
                $this->form->rememberValues();
                $this->form->addOutput("E-postadressen är redan registrerad.");
                return false;
        }

        $user->email = $this->form->value("email");
        $user->name = $email;
        $user->updated = date("Y-m-d H:i:s");
        $user->active = $active ? 1 : 0;
        $user->admin = $admin ? 1 : 0;

        if (!empty($password)) {
            $user->setPassword($password);
        }

        $user->save();

        $this->di->get("response")->redirect("admin");

        return true;
    }
}
