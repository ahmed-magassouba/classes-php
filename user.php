<?php
session_start();
class User
{
    private $id;
    public $login;
    public $email;
    public $firstname;
    public $lastname;

    //LE CONSTRUCTEUR SANS PARAMETRE

    public function __construct()
    {
    }



    public function register($login, $password, $email, $firstname, $lastname)
    {

        $bdd = mysqli_connect('localhost', 'root', '', 'classes');
        mysqli_set_charset($bdd, 'UTF8');

        $sqlVerif = "SELECT * FROM utilisateurs WHERE login = '$login'";
        $select = mysqli_query($bdd, $sqlVerif);

        if (mysqli_num_rows($select)) {
            return "Ce login existe déjà , choisissez un autre";
        } else {

            $sql = "INSERT INTO `utilisateurs`(`login`, `password`, `email`, `firstname`, `lastname`) VALUES ('$login','$password','$email','$firstname','$lastname')";
            $requete = mysqli_query($bdd, $sql);
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

        $bdd = mysqli_connect('localhost', 'root', '', 'classes');
        mysqli_set_charset($bdd, 'UTF8');

        $sql = "SELECT * FROM `utilisateurs` WHERE login = '$login' AND password = '$password' ";
        $requete = mysqli_query($bdd, $sql);
        $utilisateur = mysqli_fetch_all($requete, MYSQLI_ASSOC);
        var_dump($utilisateur);
        if (count($utilisateur) > 0) {

            $_SESSION['user_connect'] = [
                "id" => $utilisateur[0]["id"],
                "login" => $utilisateur[0]["login"],
                "password" => $utilisateur[0]["password"],
                "email" => $utilisateur[0]["email"],
                "firstname" => $utilisateur[0]["firstname"],
                "lastname" => $utilisateur[0]["lastname"]

            ];

            $this->id = $utilisateur[0]["id"];
            $this->login = $login;
            $this->email = $utilisateur[0]["email"];
            $this->firstname = $utilisateur[0]["firstname"];
            $this->lastname = $utilisateur[0]["lastname"];

            //$this->$password = $password;
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

        $bdd = mysqli_connect('localhost', 'root', '', 'classes');
        mysqli_set_charset($bdd, 'UTF8');
        $sql = "DELETE FROM `utilisateurs` WHERE login = '$this->login'";
        $requete = mysqli_query($bdd, $sql);

        unset($_SESSION['user_connect']);
    }



    public function update($login, $password, $email, $firstname, $lastname)
    {
        $bdd = mysqli_connect('localhost', 'root', '', 'classes');
        mysqli_set_charset($bdd, 'UTF8');
        $sql = "UPDATE `utilisateurs` SET `login`='$login',`password`='$password',`email`='$email',`firstname`='$firstname',`lastname`='$lastname'";
        $requete = mysqli_query($bdd, $sql);

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



    public function getLogin(){
       return $this->login;
    }

    public function getEmail(){
        return $this->email;
     }

     public function getFirstname(){
        return $this->firstname;
     }

     public function getLastname(){
        return $this->lastname;
     }

}



$membre = new User;
echo $membre->register('ahmed', 'ahmed', 'ahmed@lapla.fr', 'ahmed', 'ahmed');
echo $membre->connect('ahmed', 'ahmed');
echo $membre->getAllinfos();
echo $membre->isConnected();
var_dump($_SESSION['user_connect'])


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