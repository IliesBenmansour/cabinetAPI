<?php
include 'jwt_utils.php';

function BDconnexion() {
    $servername = "mysql-47medecine.alwaysdata.net";
    $db = "47medecine_connexion";
    $login = "344048_menoh";
    $mdp = "cestmoiyoumni";

    try {
        $bdd = new PDO("mysql:host=$servername;dbname=$db;charset=utf8", $login, $mdp);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $bdd;
    } catch (PDOException $e) {
        die('La connexion a la base de donnée a échoué: ' . $e->getMessage());
    }
}
$linkpdo = BDconnexion();
$http_method = $_SERVER['REQUEST_METHOD'];
$data = (array) json_decode(file_get_contents('php://input'), TRUE);


function isValidUser($login, $mdp){
$linkpdo = BDconnexion();
$sql = $linkpdo->prepare('SELECT `login` FROM users WHERE `login`= :login');
$sql->bindParam(':login',$login,PDO::PARAM_STR); //Attention au type du paramètre !
if(!$sql->execute()){
return false;
}else{
$sql = $linkpdo->prepare('SELECT `mdp` FROM users WHERE `login`= :login');
$sql->bindParam(':login',$login,PDO::PARAM_STR); //Attention au type du paramètre !
$sql->execute();
$result = $sql->fetch(PDO::FETCH_ASSOC);
$password = $result['mdp'];
if(password_verify($mdp,$password)){
return true;

}
return false;

}
}
function isValidToken($token,$secret){
$secret = 'secret1';
if(is_jwt_valid(get_bearer_token(),$secret) == TRUE){
return TRUE;
}
return FALSE;
}


if($http_method == "POST"){
if(isValidUser($data['login'], $data['mdp'])){
$login = $data['login'];

$headers = array('alg' =>'HS256','typ'=>'JWT');
$payload = array('login' => $login, 'exp' =>(time() +600));

$jwt  = generate_jwt($headers, $payload,$secret = 'secret1');

$sql = $linkpdo->prepare('SELECT `role` FROM users WHERE `login`= :login');
$sql->bindParam(':login',$login,PDO::PARAM_STR); //Attention au type du paramètre !
$sql->execute();
$result = $sql->fetch(PDO::FETCH_ASSOC);
$res = $result['role'];
$data['jwt'] = $jwt;
$data['role'] = $res;
deliver_response(200,"tien le token",$data);
}else{
deliver_response(401,"Utilisateur inconnue");
}
}elseif (is_jwt_valid(get_bearer_token(), 'secret1')){
deliver_response(200,"il est valide");
}
else{
deliver_response(401,"Il faut utiliser POST");
}
?>