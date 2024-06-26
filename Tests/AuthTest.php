<?php

use PDO;
use App\Auth;
use App\Exceptions\ForbiddenException;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    /**
     * 
     *
     * @var [Auth]
     */
    private $auth;
    private $session = [];

    /**
     *
     * @before
     */
    public function setAuth () {
        $pdo = new PDO("sqlite::memory:", null,null ,[
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);

        $pdo->query('CREATE TABLE users (id INTEGER, username TEXT, password TEXT, role TEXT)');
        for ($i=0; $i < 10; $i++) { 
            $password = password_hash("user$i", PASSWORD_BCRYPT,['cost' => 4]);
            $request = $pdo->prepare("INSERT INTO users (id,username, password, role) VALUES (:id,:username,:pass, :role)");

            $request->bindValue(":id",$i, PDO::PARAM_STR);
            $request->bindValue(":username","user$i", PDO::PARAM_STR);
            $request->bindValue(":pass",$password, PDO::PARAM_STR);
            $request->bindValue(":role","user$i", PDO::PARAM_STR);
            $request->execute();
        }
        $this->auth = new Auth($pdo, '/login', $this->session);
    }
    

    public function testLoginWithBadUsername () {
       
        $this->assertNull($this->auth->login('aze','aze'));
    }

     public function testLoginWithBadPassword() {
       
        $this->assertNull($this->auth->login('user1','aze'));
    }

    public function testLoginSuccess() {
       $this->assertObjectHasProperty('username', $this->auth->login('user1','user1'));
       $this->assertEquals(1, $this->session['auth']);
    }

    public function testUserNotConnected() {
       $this->assertNull($this->auth->user());
    }

    public function testConnectedUser() {
        $this->session['auth'] = 4;
        $user = $this->auth->user();
        $this->assertIsObject($user);
        $this->assertEquals('user4', $user->username);
    }

     public function testRole() {
       $this->session['auth'] = 2 ;
       $this->auth->role('user2');
        
    }

    public function testRoleThrowWithoutLoginThrowException() {
        $this->expectException(App\Exceptions\ForbiddenException::class);
        $this->auth->role('user3');
    }

    public function testRoleThrowExceptions() {
       $this->expectException(App\Exceptions\ForbiddenException::class);
       $this->auth->role('user2');
       $this->expectNotToPerformAssertions();
    }

     


}
