<?php 

namespace App;

class Auth
{

    public $pdo;
    public $loginPath;

    public function __construct(\PDO $pdo, string $loginPath) 
    {
        $this->pdo =  $pdo;
        $this->loginPath = $loginPath;
    }
    
    public function StartSession() 
    {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
    }

    public function role( ...$roles)
    {
        $user = $this->user();
      
            if ($user == null || $user->role !== $roles) {
            if (!in_array($user->role, $roles)) {
                header('Location: index.php');
            }
        } 

    }

   public function user(): ?User
    {
       $this->StartSession();
        $id = $_SESSION['userID'] ?? null;
        if ($id === null) {
            return null;
        }
        $query = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $query->execute([$id]);
        $user = $query->fetchObject(User::class);
        return $user ?: null;
    }
    


    public function login (string $user, string $password) : ?User 
    {
            $this->StartSession();

        $form = new FormValidator($user, $password);
        $result = $form->checkRegisterForms();

        dump($result);
        
        $request = $this->pdo->prepare('SELECT * FROM users where username =  :username');
        $request->bindParam(':username', $user, $this->pdo::PARAM_STR);
        
        // $hasg = password_hash('user',PASSWORD_ARGON2ID  );
        // var_dump($hasg);
        // $request = $this->pdo->prepare("INSERT INTO users (username, password,role) VALUES(?, ?, ?)");

        // $request->bindValue(1, 'apple', $this->pdo::PARAM_STR);
        // $request->bindValue(2, $hasg, $this->pdo::PARAM_STR);
        // $request->bindValue(3, 'apple', $this->pdo::PARAM_STR);

       
        $request->execute();
        $user = $request->fetchObject(User::class);
        
        if ($user === false) {
            return null;
        }
        if (password_verify($password, $user->password)) {
            dump('flse');
            $_SESSION['userID'] = $user->id;
             return $user;
        }
        return null;
    }

    

}
