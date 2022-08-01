<?php
include_once 'libs/imodel.php';
require_once 'libs/model.php';
class UserModel extends Model implements IModel{
//Estas variables van a ser redefinidas para que se puedan ejecutar con diferentes clases.   
private $id;
private $username;
private $password;
private $role;
private $budget;
private $photo;
private $name;

//El siguiente codigo define que tipo de datos son cada uno de los que se van a modificar y van a ser ejecutados al hacer una consulta.
    public function __construct(){
    parent::__construct();
    $this->username='';
    $this->password='';
    $this->role='';
    $this->budget=0.0;
    $this->photo='';
    $this->name='';
    }
public function save(){
    try{
      $query= $this->prepare('INSERT INTO users(username,password,role,budget,photo,name) VALUES(:username,:password,:role,:budget,:photo,:name)');
      //Execute es una funcion predefinida de php que cambia los valores de las variables nominadas con ':' en una query ( en este caso los valores insertados en la tabla users.)
      $query->execute([
        'username'=> $this->username,
        'password'=> $this->password,
        'role'    => $this->role,
        'budget'  => $this->budget,
        'photo'   => $this->photo,
        'name'    => $this->name
      ]);
      return true;
      //Catch lo que va hacer es detectar un error y ejecutar una accion. PDOException es un atributo de php el cual permite visualizar porque no se pudo conectar con la base de datos,
    } 
    catch(PDOException $e){
        error_log('USERMODE::save--> PDOexception'.$e);
        return false; 
    }
}
//Get all va a permitir que se muestren todos los datos de la consulta de users, la cual va a mostrar toda la tabla de users.
public function getAll(){
    $items=[];
    try{
      $query= $this->query('SELECT * FROM users');
      while($p=$query->fetch(PDO::FETCH_ASSOC)){//FETCH_ASSOC es una funcion de PDO que regresa los valores indexados por nombre de columnas. Lo que va a realizar este ciclo while es que por cada elemento $p (una columna de la tabla de datos) exista se va a ejecutar los atributos en llaves.
      $item= new UserModel();
      $item->setId($p['id']); // Si la consulta a la base de datos fue hecha exitosamente, entonces se pueden dar atributos a los mparametros de la clase UserModel. EN este caso, se le esta asignando un valor al id que viene de la base de datos al objeto $item con la propiedad de id.
      $item->setUsername($p['username']);
      $item->setPassword($p['password']);
      $item->setRole($p['role']);
      $item->setBudget($p['budget']);
      $item->setPhoto($p['photo']);
      $item->setName($p['name']);
      array_push($items,$item);
      }
      return $items;
    }
    catch(PDOException $e){
        error_log('USERMODE::getALL--> PDOexception'.$e);
    }
}
// La siguiente funcion get($id) va a tomar los datos con una variable ($id) que va a especificar cuales se van a tomar. Dado que no es una simple query y se va a utilizar WHERE es recomendable usar prepare() y luego execute para ejecutar la query. 
public function get($id){
    try{
        $query= $this->prepare('SELECT * FROM users WHERE id=:id');
        $query->execute([
            'id'=>$id
        ]);
       //NOTA 1: En este caso no se esta creando un nuevo objeto ya que se esta llamado por un identidficador y se esta almacenando en la variable $user. Por ende, podemos hacer uso del atributo $this para hacer referencia al objeto usado. 
       //NOTA 2: User esta ejecutando las funciones que estan definidas abajo de este codigo. El argumento de cada funcion hace referencia a los atributos del arreglo en la variable de users que se le otorgaron gracias al uso de la funcion PDO::FETCH_ASSOC. A groso modo, lo que va hacer las funciones es que van asingarle valores a las variables del modelo UserModel.
        $user = $query->fetch(PDO::FETCH_ASSOC);
        $this->setUsername($user['username']);
        $this->setId($user['id']); // Si la consulta a la base de datos fue hecha exitosamente, entonces se pueden dar atributos a los mparametros de la clase UserModel. EN este caso, se le esta asignando un valor al id que viene de la base de datos al objeto $item con la propiedad de id.
        $this->setPassword($user['password']);
        $this->setRole($user['role']);
        $this->setBudget($user['budget']);
        $this->setPhoto($user['photo']);
        $this->setName($user['name']);
        }
      catch(PDOException $e){
          error_log('USERMODE::getId--> PDOexception'.$e);
      }
}

//La siguiente funcion elimina un usuario mediante un id.
public function delete($id){
    try{
        $query= $this->prepare('DELETE FROM users WHERE id=:id');
        $query->execute([
            'id'=>$id
        ]);
        return true;
    } 
    catch(PDOException $e){
        error_log('USERMODE::Delete--> PDOexception'.$e);
        return false;
    }
}
//La siguiente funcion va a actualizar a un user por medio de su ID. La funcion, para poder ejecutarse, es necesario que llame a get($id). Posteriormente se puden editar el nombre de usuario.
public function update(){
    try{
        $query= $this->prepare('UPDATE users SET username=:username, password=:password, budget=:budget, role=:role, photo=:photo, name=:name WHERE id=:id');
        $query->execute([
            'id'=> $this->id,
            'username'=> $this->username,
            'password'=> $this->password,
            'role'=> $this->role,
            'budget'=> $this->budget,
            'photo'=> $this->photo,
            'name'=> $this->name
        ]);
        return true;
    }
        catch(PDOException $e){
            error_log('USERMODE::Update--> PDOexception'.$e);
            return false;
        } 
        
    }
//El siguiente metodo va a validar si es que existe o no un username.
public function exists($username){
    try{
        $query=$this->prepare('SELECT username FROM users WHERE username=:username');
        $query->execute([
            'username'=> $username
        ]);
        if($query->rowCount()>0){// Lo que hace este metodo if es que valida si hay al menos un usuario con el nombre de usuario insertado en la consulta y por lo tanto existe y puede validar el password.
            return true;
        } else{
            return false;
        }
    }
    catch(PDOException $e){
        return false;

    }
}
//La siguiente funcion va hacer una comparacion de passwords.

 function comparepasswords($password, $id){
    try{
    $query=$this->prepare('SELECT id, password FROM users WHERE id=:id');
    $query->execute(['id'=>$id]);
    if($row=$query->fetch(PDO::FETCH_ASSOC)) return password_verify($password,$row['password']);
}
catch(PDOException $e){
    return NULL;
}

}


//La siguiente funcion le va asignar valores a las variables de las clases. EL array se va a descodificar y se va a tomar cada uno de sus datos.
    public function from($array){
    $this->id=$array['id'];
    $this->username=$array['username'];
    $this->password=$array['password'];
    $this->role=$array['role'];
    $this->budget=$array['budget'];
    $this->photo=$array['photo'];
    $this->name=$array['name'];
    }



//Funciones para definir un objeto de identidad,
public function setId($id){ $this->id=$id;}
public function setUsername($username){ $this->username=$username;}
public function setRole($role){$this->role=$role;}
public function setBudget($budget){$this->budget=$budget;}
public function setPhoto($photo){$this->photo=$photo;}
public function setName($name){$this->name=$name;}
//La siguiente funcion lo que va hacer es que va incriptar un password en un hash para que no se guarde en texto plano. 
private function getHashedPassword($password){
    return password_hash($password,PASSWORD_DEFAULT,['cost'=>10]);// Lo que hace esta funcion es que crea un hash password. La PASSWORD_DEFAULT crea un hash predeterminado de php. EL costo de 10 significa el numero de rpeocesos para encriptar el hash. ENtre mas costo mas lento es el proceso.
  }
public function setPassword($password){
    $this->password=$this->getHashedPassword($password);
}


//Funciones para llamar a un objeto de identidad.
public function getId(){return $this->id;}
public function getUsername(){return $this->username;}
public function getPassword(){return $this->password;}
public function getRole(){return $this->role;}
public function getBudget(){return $this->budget;}
public function getPhoto(){return $this->photo;}
public function getName(){return $this->name;}


}