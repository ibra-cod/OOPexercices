<?php 

namespace App;

use App\Exceptions\ForbiddenException;

class Auth
{

    public $pdo;
    public $loginPath;
    public $session;

    public function __construct(\PDO $pdo, string $loginPath, array &$session) 
    {
        $this->pdo =  $pdo;
        $this->loginPath = $loginPath;
        $this->session = &$session;
    }
    

    public function role( array | string ...$roles)
    {
        $user = $this->user();
            dump($this->session);
            if ($user == null ) {
                 throw new ForbiddenException('You must be connted.');
            } 
            if (!in_array($user->role, $roles)) {
                $roles = implode(',', $roles);
                $userRole = $user->role;
                throw new ForbiddenException("You're role is $userRole and you must be $roles to be able to see this page");
            }

    }

   public function user(): ?User
    {
        $id = $this->session['auth'] ?? null;

        $query = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $query->execute([$id]);
        $user = $query->fetchObject(User::class);
        return $user ?: null;
    }
    


    public function login (string $user, string $password) : ?User 
    {

        if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

        $form = new FormValidator($user, $password);
        $result = $form->checkRegisterForms();
        extract($result,EXTR_REFS);
        
        
        $request = $this->pdo->prepare('SELECT * FROM users where username =  :username');
        $request->bindParam(':username', $user, $this->pdo::PARAM_STR);
        
        // $hasg = password_hash('ssiap134',PASSWORD_ARGON2ID  );
        // var_dump($hasg);
        // $request = $this->pdo->prepare("INSERT INTO users (username, password,role) VALUES(?, ?, ?)");

        // $request->bindValue(1, 'ssiap', $this->pdo::PARAM_STR);
        // $request->bindValue(2, $hasg, $this->pdo::PARAM_STR);
        // $request->bindValue(3, 'vip', $this->pdo::PARAM_STR);

       
        $request->execute();
        $user = $request->fetchObject(User::class);

        
        if ($user === false) {
            return null;
        }


        if (password_verify($pass, $user->password)) {
            // if (session_status() === PHP_SESSION_NONE) {
            //     session_start();
            // } replaces by the code below 
            //  $_SESSION['userID'] = $user->id;
            $this->session['auth'] = $user->id;
               
             return $user;
        }
        return null;
    }

    

}
