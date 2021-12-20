<?php

//constante d'envoronnement
define("DBHOST", "localhost");
define("DBUSER", "root");
define("DBPASS", "");
define("DBNAME", "classes");



class Userpdo
{
    private $id;
    public $login;
    public $email;
    public $firstname;
    public $lastname;

    protected $bdd;

    //le constructeur
    public function __construct()
    {
        //DSN de connexion (data source name)
        $dsn = "mysql:dbname=" . DBNAME . ";host=" . DBHOST;

        // on va se connecter a la base a travers un try catch pour gerer l'exeption levé par pdo
        try {
            //on va instancie PDO
            $this->bdd = new PDO($dsn, DBUSER, DBPASS);

            //On s'assure d'envoyer les données en utf8
            $this->bdd->exec("SET NAMES utf8");

            //on definit le mode de fetch par defaut
            $this->bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,  PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur de connexion a la base: " . $e->getMessage());
        }
        return $this->bdd;
    }



    public function register($login, $password, $email, $firstname, $lastname)
    {

        $sqlVerif = "SELECT * FROM utilisateurs WHERE login =:login";

        //ON PREPARE LA REQUETE
        $requete = $this->bdd->prepare($sqlVerif);

        //ON INJECTE LES VALEURS AVEC LA FONCTION "bindValue"
        //PDO::PARAM_STR Pour dire que notre paramètre est une chaine de carractère
        $requete->bindValue(":login", $login, PDO::PARAM_STR);

        //ON EXECUTE LA REQUETE
        $requete->execute();

        $select = $requete->fetchAll();

        var_dump($select);

        if (count($select) > 0) {
            return "Ce login existe déjà , choisissez un autre";
        } else {

            $sql1 = "INSERT INTO `utilisateurs`(`login`, `password`, `email`, `firstname`, `lastname`) VALUES ( :login, :password, :email, :firstname, :lastname)";

            //ON PREPARE LA REQUETE
            $requete1 = $this->bdd->prepare($sql1);

            //ON INJECTE LES VALEURS AVEC LA FONCTION "bindValue"
            //PDO::PARAM_STR Pour dire que notre paramètre est une chaine de carractère
            $requete1->bindValue(":login", $login, PDO::PARAM_STR);
            $requete1->bindValue(":password", $password, PDO::PARAM_STR);
            $requete1->bindValue(":email", $email, PDO::PARAM_STR);
            $requete1->bindValue(":firstname", $firstname, PDO::PARAM_STR);
            $requete1->bindValue(":lastname", $lastname, PDO::PARAM_STR);

            //ON EXECUTE LA REQUETE
            $requete1->execute();
            return '
                       <table>
                           <thead>
                               <th>Login</th>
                               <th>Password</th>
                               <th>Email</th>
                               <th>Firstname</th>
                               <th>Lastname</th>
                           </thead>
                           <tbody>
                               <tr>
                                   <td>' . $login . '</td>
                                   <td>' . $password . '</td>
                                   <td>' . $email . '</td>
                                   <td>' . $firstname . '</td>
                                   <td>' . $lastname . '</td>
                               </tr>
                           </tbody>
                       </table>';
        }
    }





    public function connect($login, $password)
    {

        $sql = "SELECT * FROM `utilisateurs` WHERE login = :login1 AND password = :password1";

        //ON PREPARE LA REQUETE
        $requete = $this->bdd->prepare($sql);

        //ON INJECTE LES VALEURS AVEC LA FONCTION "bindValue"
        //PDO::PARAM_STR Pour dire que notre paramètre est une chaine de carractère
        $requete->bindValue(":login1", $login, PDO::PARAM_STR);
        $requete->bindValue(":password1", $login, PDO::PARAM_STR);

        //ON EXECUTE LA REQUETE
        $requete->execute();

        $utilisateur = $requete->fetch();

        var_dump($utilisateur);

        if (count($utilisateur) > 0) {

            $_SESSION['user_connect'] = [
                "id" => $utilisateur["id"],
                "login" => $utilisateur["login"],
                "password" => $utilisateur["password"],
                "email" => $utilisateur["email"],
                "firstname" => $utilisateur["firstname"],
                "lastname" => $utilisateur["lastname"]

            ];

            $this->id = $utilisateur["id"];
            $this->login = $login;
            $this->email = $utilisateur["email"];
            $this->firstname = $utilisateur["firstname"];
            $this->lastname = $utilisateur["lastname"];
        } else {
            echo 'Le login ou le mot de passe est incorrect';
        }
    }


    public function disconnect()
    {
        unset($_SESSION['user_connect']);
    }



    public function delete()
    {

        $sql = "DELETE FROM `utilisateurs` WHERE id = :id";

        //ON PREPARE LA REQUETE
        $requete = $this->bdd->prepare($sql);

        //ON INJECTE LES VALEURS AVEC LA FONCTION "bindValue"
        //PDO::PARAM_STR Pour dire que notre paramètre est une chaine de carractère
        $requete->bindValue(":id", $this->id, PDO::PARAM_STR);

        //ON EXECUTE LA REQUETE
        $requete->execute();

        unset($_SESSION['user_connect']);
    }



    public function update($login, $password, $email, $firstname, $lastname)
    {
        $sql = "UPDATE `utilisateurs` SET `login`= :login,`password`= :password,`email`= :email,`firstname`= :firstname,`lastname`= :lastname";

        //ON PREPARE LA REQUETE
        $requete = $this->bdd->prepare($sql);

        //ON INJECTE LES VALEURS AVEC LA FONCTION "bindValue"
        //PDO::PARAM_STR Pour dire que notre paramètre est une chaine de carractère
        $requete->bindValue(":login", $login, PDO::PARAM_STR);
        $requete->bindValue(":password", $password, PDO::PARAM_STR);
        $requete->bindValue(":email", $email, PDO::PARAM_STR);
        $requete->bindValue(":firstname", $firstname, PDO::PARAM_STR);
        $requete->bindValue(":lastname", $lastname, PDO::PARAM_STR);

        //ON EXECUTE LA REQUETE
        $requete->execute();

        $this->login = $login;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;

        $_SESSION['user_connect']['login'] = $login;
        $_SESSION['user_connect']['password'] = $password;
        $_SESSION['user_connect']['email'] = $email;
        $_SESSION['user_connect']['firstname'] = $firstname;
        $_SESSION['user_connect']['lastname'] = $lastname;
    }


    public function isConnected()
    {
        if (!empty($_SESSION['user_connect'])) {
            return true;
        } else {
            return false;
        }
    }



    public function getAllinfos()
    {
        return ' <table>
       <thead>
           <th>id</th>
           <th>Login</th>
           <th>Password</th>
           <th>Email</th>
           <th>Firstname</th>
           <th>Lastname</th>
       </thead>
       <tbody>
           <tr>
               <td>' . $this->id . '</td>
               <td>' . $this->login . '</td>
               <td>' .  $_SESSION['user_connect']['password'] . '</td>
               <td>' . $this->email . '</td>
               <td>' . $this->firstname . '</td>
               <td>' . $this->lastname . '</td>
           </tr>
       </tbody>
   </table>';
    }



    public function getLogin()
    {
        return $this->login;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }
}



$membre = new Userpdo;
echo $membre->register('admin2', 'admin2', 'admin2@lapla.fr', 'admin2', 'admin2');
echo $membre->connect('admin2', 'admin2');
echo $membre->isConnected();
echo $membre->getAllinfos().'<br>';
echo $membre->getLogin().'<br>';
echo $membre->getEmail().'<br>';
echo $membre->getLogin().'<br>';
echo $membre->getFirstname().'<br>';
echo $membre->getLastname();
var_dump($_SESSION['user_connect']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
