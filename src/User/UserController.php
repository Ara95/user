<?php

namespace Ara\User;

use \Anax\Configure\ConfigureInterface;
use \Anax\Configure\ConfigureTrait;
use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \Ara\User\HTMLForm\UserLoginForm;
use \Ara\User\HTMLForm\CreateUserForm;
use \Ara\User\HTMLForm\EditUserForm;

/**
 * A controller class.
 */
class UserController implements
    ConfigureInterface,
    InjectionAwareInterface
{
    use ConfigureTrait,
        InjectionAwareTrait;

    /**
     *
     * Get the index page
     *
     * @return void
     */
    public function getIndex()
    {
        $this->di->get("auth")->isLoggedIn();

        $title      = "Min sida";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");

        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->find("email", $this->di->get("session")->get("user"));

        $isAdmin = $user->admin;

        $data = [
            "title" => $title,
            "user" => $user,
            "isAdmin" => $isAdmin,
        ];

        $view->add("user/index", $data);

        $pageRender->renderPage(["title" => $title]);
    }



    /**
     * Login page.
     *
     *
     * @return void
     */
    public function getPostLogin()
    {
        $title      = "Logga in";
        $card       = "Inloggning";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");
        $form       = new UserLoginForm($this->di);

        echo $form->check();


        $data = [
            "form" => $form->getHTML(),
            "title" => $title,
            "card" => $card
        ];

        $view->add("user/login", $data);

        $pageRender->renderPage(["title" => $title]);
    }


    /**
     * Logout
     *
     * @return void
     */
    public function getLogout()
    {
        $this->di->get("session")->delete("user");
        $this->di->get("session")->delete("userid");
        $this->di->get("response")->redirect("user/login");
    }



    /**
     * Create a new user.
     *
     *
     * @return void
     */
    public function getPostCreateUser()
    {
        $title      = "Skapa en ny användare";
        $card       = "Användarinformation";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");
        $form       = new CreateUserForm($this->di);

        $form->check();

        $data = [
            "form" => $form->getHTML(),
            "title" => $title,
            "card" => $card
        ];

        $view->add("user/create", $data);

        $pageRender->renderPage(["title" => $title]);
    }

    /**
     * Handler with form to update a user.
     *
     * @return void
     */
    public function getPostEditUser()
    {
        $this->di->get("auth")->isLoggedIn();

        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->find("email", $this->di->get("session")->get("user"));

        $title      = "Redigera profil";
        $card       = "Redigera";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");
        $form       = new EditUserForm($this->di, $user->email);

        $form->check();

        $data = [
            "form" => $form->getHTML(),
            "card" => $card,
            "title" => $title,
        ];

        $view->add("user/edit", $data);

        $pageRender->renderPage(["title" => $title]);
    }
}
