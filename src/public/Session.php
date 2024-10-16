<?php

namespace Mostafa\Kitchen\public;

class Session
{

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Start the session if it hasn't started yet
        }
    }
    public function setSession($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function getSession($key)
    {
        return $_SESSION[$key];
    }

    public function removeSession($key)
    {
        unset($_SESSION[$key]);
    }

    public function sessionDestroy()
    {
        session_destroy();
    }
    public function flashSuccess($name = '', $message = '', $class = 'alert alert-success')
    {
        if (!empty($name)) {
            if (!empty($message) && empty($this->getSession($name))) {
                if (!empty($this->getSession($name))) {
                    $this->removeSession($name);
                }

                if (!empty($this->getSession($name . '_class'))) {
                    $this->removeSession($name);
                }
                $this->setSession($name, $message);
                $this->setSession($name . '_class', $class);
            } elseif (empty($message) && !empty($this->getSession($name))) {
                $class = !empty($this->getSession($name . '_class')) ? $this->getSession($name . '_class') : '';
                echo '<div class="' . $class . '" id="msg-flash">' . $this->getSession($name) . '</div>';
                $this->removeSession($name . '_class');
                $this->removeSession($name);
            }
        }
    }
    public function flashDanger($name = '', $message = '', $class = 'alert alert-danger')
    {
        if (!empty($name)) {
            if (!empty($message) && empty($this->getSession($name))) {
                if (!empty($this->getSession($name))) {
                    $this->removeSession($name);
                }

                if (!empty($this->getSession($name . '_class'))) {
                    $this->removeSession($name);
                }
                $this->setSession($name, $message);
                $this->setSession($name . '_class', $class);
            } elseif (empty($message) && !empty($this->getSession($name))) {
                $class = !empty($this->getSession($name . '_class')) ? $this->getSession($name . '_class') : '';
                echo '<div class="' . $class . '" id="msg-flash">' . $this->getSession($name) . '</div>';
                $this->removeSession($name . '_class');
                $this->removeSession($name);
            }
        }
    }
}
