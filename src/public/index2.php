<!-- ADRIAN MORENO-->
<?php
//Función capaz de soporta la inserción de registro con binding parametrizado opcional.
function InsertarRegistro(PDO $pdo,string $query,array $params=[]):void{
    try{
        $statement=$pdo->prepare($query);
        foreach($params as $key => $value){
            //AGL Añado echos para pruebas
            echo 'key: '.$key;
            echo 'value: '.$value;
            //No funciona
            $statement->bindParam(":".$key,$value);
        }
        $statement->execute();
        $statement=null;
    }catch(PDOException $e){
        echo '<p>Error '.$e->getMessage().'</p>';
    }
}
//Función que devuevle un statement de una selección de base de datos.
function SelectRegistro(PDO $pdo,string $query):PDOStatement{
    try{
        $statement=$pdo->prepare($query);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $statement->execute();
        return $statement;
    }catch(PDOException $e){
        echo '<p>'.$e->getMessage().'</p>';
    }
}
//Función que muestra todos los registros de una consulta del tipo SELECT.
function MostrarRegistro(PDOStatement $statement){
    while($row=$statement->fetch()){
        echo "<p>".$row['id'].", ".$row['nombre'].", ".$row['usuario'].", ".
        $row['password'].", ".$row['email']."</p>";
    }
    $statement=null;
}
//Función capaz de soportar las acciones de creación, actualización y borrado de la base de datos.
function CUDResgistro(PDO $pdo,string $query):void{
    try{
        $statement=$pdo->prepare($query);
        $statement->execute();
        $statement=null;
    }catch(PDOException $e){
        echo '<p>'.$e->getMessage().'</p>';
    }
}
//Declaración de la conexión y activación de errores
$host="database"; //AGL modificado
$dbname="demo";
$username="root";
$password="Pass1234";
$pdo=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8"
,$username,$password);
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
//Apartado 1
InsertarRegistro($pdo,"INSERT INTO usuarios 
VALUES(:ID,:NOMBRE,:USUARIO,:PASSWORD,:EMAIL);"
,ARRAY("ID"=>10,"NOMBRE"=>"John","USUARIO"=>"Juser",
"PASSWORD"=>"12345","EMAIL"=>"john@gmail.com"));
//Apartado 2
MostrarRegistro(SelectRegistro($pdo,
"SELECT id, nombre, usuario, password, email from usuarios where id=10;"));
//Apartado 3
CUDResgistro($pdo,"UPDATE usuarios SET nombre='James' Where id=10;");
//Apatado 4
MostrarRegistro(SelectRegistro($pdo,
"SELECT id, nombre, usuario, password, email from usuarios where id=10;"));
//Apartado 5
InsertarRegistro($pdo,"INSERT INTO usuarios 
VALUES(:ID,:NOMBRE,:USUARIO,:PASSWORD,:EMAIL);"
,ARRAY("ID"=>11,"NOMBRE"=>"James","USUARIO"=>"jjU",
"PASSWORD"=>"98765","EMAIL"=>"james9090@gmail.com"));
//Apartado 6
CUDResgistro($pdo,"DELETE FROM usuarios WHERE id=10;");
//Apartado 7
MostrarRegistro(SelectRegistro($pdo,
"SELECT id, nombre, usuario, password, email from usuarios;"));
//Desconexión de la base de datos
$pdo=null;
?>