<?php

namespace Ara\Auth;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \Ara\User\User;
use \Ara\Comment\Comment;

/**
 * Auth class
 */
class AuthMiddleware implements InjectionAwareInterface
{
    use InjectionAwareTrait;

    /**
     * Check if user is logged in.
     *
     * @return boolean
     */
    public function isLoggedIn()
    {
        $loggedIn = $this->di->get("session")->has("user");
        if (!$loggedIn) {
            $this->di->get("response")->redirect("user/login");
            return false;
        }
        return true;
    }

    /**
     * Check if user is admin
     *
     * @param string $email user email
     *
     * @return boolean if the user is an admin
     */
    public function isAdmin($redirect = false)
    {

        if (!$this->isLoggedIn()) {
            $this->di->get("response")->redirect("user/login");
            return false;
        }

        $email = $this->di->get("session")->get("user");
        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->find("email", $email);
        $isAdmin = $user->admin;

        if (!$isAdmin) {
            if ($redirect) {
                $this->di->get("response")->redirect("user");
            }
            return false;
        }

        return true;
    }

    /**
     * Check if the user have access to the comment
     *
     * @param integer $commentID Comment ID
     *
     * @return boolean True if the user is admin or is the owner of the comment
     */
    public function hasAccessToComment($commentID)
    {

        $this->isLoggedIn();

        $userid = $this->di->get("session")->get("userid");
        $comment = new Comment();
        $comment->setDb($this->di->get("db"));
        $user = new User();
        $user->setDb($this->di->get("db"));

        $user->find("id", $userid);
        $comment->find("id", $commentID);

        if (($comment->user_id == $userid) || $this->isAdmin()) {
            return true;
        }
        $this->di->get("response")->redirect("user");
        return false;
    }
}
