<?php

/**
 * This is a simple wrapper class to handle sessions using SUPERGLOBAL Variable $_SESSION
 * @method : set, get, has, delete, set_flash_message, get_flash_message, destroy
 * @author : Uttam Kumar
 */

class SessionManager
{
    private $flash_identifier = 'flash_';

    public function set($key, $value = null): self
    {
        if (!empty($key)) {

            if (is_array($key)) {
                foreach ($key as $k => $v) {
                    $_SESSION[$k] = $v;
                }
            } else {
                $_SESSION[$key] = $value;
            }
        }

        return $this;
    }

    public function get($key)
    {

        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        trigger_error('Undefined key `' . $key . '` in session ');
    }

    public function delete($key): self
    {

        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }

        return $this;
    }

    public function set_flash_message($key, $value=null): void
    {

        if (empty($key)) return;

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $_SESSION[$this->flash_identifier . $k] = $v;
            }
        } else {
            $_SESSION[$this->flash_identifier . $key] = $value;
        }
    }


    public function get_flash_message($key)
    {

        if (isset($_SESSION[$this->flash_identifier . $key])) {
            $msg = $_SESSION[$this->flash_identifier . $key];
            unset($_SESSION[$this->flash_identifier . $key]);

            return $msg;
        }
        return null;
    }

    public function destroy(array $exclude = array()): void
    {
        if (!empty($exclude)) {
            foreach ($_SESSION as $key => $value) {
                if (!in_array($key, $exclude)) {
                    unset($_SESSION[$key]);
                }
            }
        } else {
            $_SESSION = [];
            session_destroy();
        }
    }


    public function has($key, $check_flash = false): bool
    {
        
        if($check_flash){
            $key = $this->flash_identifier.$key;
        }
        return isset($_SESSION[$key]);
    }
}
