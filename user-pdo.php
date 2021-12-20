<?php

//constante d'envoronnement
define("DBHOST", "localhost");
define("DBUSER", "root");
define("DBPASS", "");
define("DBNAME", "classes");

//DSN de connexion (data source name)
$dsn = "mysql:dbname=" . DBNAME . ";host=" . DBHOST;

// on va se connecter a la base a travers un try catch pour gerer l'exeption levé par pdo
try {
    //on va instancie PDO
    $bdd = new PDO($dsn, DBUSER, DBPASS);

    //On s'assure d'envoyer les données en utf8
    $bdd->exec("SET NAMES utf8");

    //on definit le mode de fetch par defaut
    $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,  PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion a la base: " . $e->getMessage());
}




class Userpdo
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
        /* Connexion à une base MySQL avec l'invocation de pilote */
        $dsn = 'mysql:dbname=classes;host=localhost';
        $user = 'root';
        $pass = '';
        $bdd = new PDO($dsn, $user, $pass);
        //On s'assure d'envoyer les données en utf8
        $bdd->exec("SET NAMES utf8");

        //on definit le mode de fetch par defaut
        $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,  PDO::FETCH_ASSOC);

        $sqlVerif = "SELECT * FROM utilisateurs WHERE login =:login";

        //ON PREPARE LA REQUETE
        $requete = $bdd->prepare($sqlVerif);

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
            $requete1 = $bdd->prepare($sql1);

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
}
$membre = new Userpdo;
echo $membre->register('admin2', 'admin2', 'admin2@lapla.fr', 'admin2', 'admin2');
