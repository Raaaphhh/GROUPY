<?php 
// require_once 'Controlleur/BddConnController.php';
require_once 'Controlleur/UserController.php';
use PHPUnit\Framework\TestCase;

class authTest extends TestCase
{
    public function setup(): void
    {
        // putenv('DB_NAME=vente_groupe_test');
        $pdo = connect_bdd_test();
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        $pdo->exec("TRUNCATE TABLE utilisateur");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        $hash = password_hash("testpassword", PASSWORD_BCRYPT);
        $pdo->exec("INSERT INTO utilisateur (nom, prenom, adresse, phone, email, motdepasse) 
                    VALUES ('Test', 'User', '123 Test St', '1234567890', 'cc@gmail.com', '$hash')");
    }

    public function testUserExists()
    {
        $user = get_user("cc@gmail.com", "cccc");
        $user2 = get_user("cc@gmail.com", "testpassword");
        // $this->assertFalse($user);
        $this->assertTrue($user2);
    }

}

?>