<?php 

namespace App;

class FormValidator 
{

    const USERNAME_LENGHT = 3;
    const PASSWORD_LENGHT = 5;

    private  string $username;
    private  string $passsword;
    private array $errors = [];


    public function __construct( string $username, string $password) {
        $this->username =  $username;
         $this->passsword= $password;
    }


    private function getLength () : int 
    {
        return strlen($this->username) > self::USERNAME_LENGHT && strlen($this->passsword) >= self::PASSWORD_LENGHT;
    }

    private  function ispregMatchValid () 
    {

            if (preg_match("/^[a-zA-Z0-9]+/", $this->username) && preg_match('/^[a-zA-Z0-9_]+/', $this->passsword) ) {
                  return true;
            } 
    }
   
    public function checkRegisterForms () 
    {
       if ($this->getLength()) 
       {
            if ($this->ispregMatchValid()) 
            {
                $value['user' ] = $this->username;
                $value['pass'] = $this->passsword;
                 return $value;
            } else {
                $this->addErrors('userlength', 'Wrong charather used for username or password');
                return $this->errors;
            }

        } else {
            $this->addErrors('userlength', 'The Username must be more than 3');
            $this->addErrors('passwordLenght', 'The Username must be more than 5');
            return $this->errors;
       }
    }

    private  function addErrors (string $error, string $messages) : string 
    {
        return $this->errors[$error] = $messages;
    }

}
 