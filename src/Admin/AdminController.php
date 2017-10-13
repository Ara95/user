<?php

namespace Ara\Admin;

use \Anax\Configure\ConfigureInterface;
use \Anax\Configure\ConfigureTrait;
use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \Ara\Admin\HTMLForm\AdminLoginForm;
use \Ara\Admin\HTMLForm\CreateAdminForm;
use \Ara\Admin\HTMLForm\EditAdminForm;
use \Ara\Admin\HTMLForm\DeleteAdminForm;
use \Ara\User\User;

/**
 * A controller class for the Admin.
 */
class AdminController implements
    ConfigureInterface,
    InjectionAwareInterface
{
    use ConfigureTrait,
        InjectionAwareTrait;

    /**
     * Get the Admin indexpage.
     *
     * @throws Exception
     *
     * @return void
     */
    public function getIndex()
    {
        $this->di->get("auth")->isAdmin(true);

        $user = new User();
        $user->setDb($this->di->get("db"));

        $title      = "Min sida";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");


        $data = [
            "title" => $title,
            "users" => $user->findAll(),
        ];

        $view->add("admin/index", $data);

        $pageRender->renderPage(["title" => $title]);
    }

    /**
     * Create new users as admin
     *
     * @return void
     */
    public function getPostCreateAdmin()
    {
        $this->di->get("auth")->isAdmin(true);

        $title      = "Admin - Skapa en ny användare";
        $card       = "Användarinformation";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");
        $form       = new CreateAdminForm($this->di);

        $form->check();

        $data = [
            "form" => $form->getHTML(),
            "title" => $title,
            "card" => $card
        ];

        $view->add("admin/create", $data);

        $pageRender->renderPage(["title" => $title]);
    }

    /**
     * Edit a user as admin.
     *
     * @return void
     */
    public function getPostEditAdmin($id)
    {
        $this->di->get("auth")->isAdmin(true);

        $title      = "Admin - Redigera profil";
        $card       = "Redigera";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");
        $form       = new EditAdminForm($this->di, $id);

        $form->check();

        $data = [
            "form" => $form->getHTML(),
            "card" => $card,
            "title" => $title,
        ];

        $view->add("admin/edit", $data);

        $pageRender->renderPage(["title" => $title]);
    }

    /**
     * Delete a user
     *
     * @return void
     */
    public function getPostDeleteAdmin($id)
    {
        $this->di->get("auth")->isAdmin(true);

        $title      = "Admin - Radera användare";
        $card       = "Radera användare";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");
        $form       = new DeleteAdminForm($this->di, $id);

        $form->check();

        $data = [
            "form" => $form->getHTML(),
            "card" => $card,
            "title" => $title,
        ];

        $view->add("admin/delete", $data);

        $pageRender->renderPage(["title" => $title]);
    }
}
