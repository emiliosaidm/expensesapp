<?php
require_once 'libs/model.php';
class CategoriesModel extends Model{
    private $id;
    private $name;
    private $color;


    public function __construct(){
        parent:: __construct();
    }
//Funciones para interactuar con categorias.
    public function save(){
        try{
            $query=$this->prepare('INSERT INTO categories(name,color) VALUES (:name,:color)');
            $query->execute([
                'name'=> $this->name, //NOTA: como se van asignar valores a los objetos cuando se creen apartir de esta clase, podemos hacer referencia a los valores del objeto llamado.
                'color'=>$this->color
            ]);
        if($query->rowCount()) return true;
        return false;

        }
        catch(PDOException $e){
            return false;
            error_log('CATEGORIESMODEL::save()--> No se pudo guardar los datos en la base de datos.');
        }
    }
    public function getAll(){
       $items=[];
       try{
         $query= $this->query('SELECT * FROM categories');
         while($r= $query->fetch(PDO::FETCH_ASSOC)){
        $item= new CategoriesModel;
        $item->from($r);
        array_push($itmes,$item);
        }
     return $items;
       }
       catch(PDOExtension $e){
        return false;
        error_log('CATEGORIESMODEL::getAll()--> No se pudo realizar la consulta.');
       } 
    }
    public function get($id){
        try{
        $query=$this->prepare('SELECT * FROM categories WHERE id=:id');
        $query->execute(['id'=> $id]);
        $category=$query->fetch(PDO::FETCH_ASSOC);
        $this->from($category);
        return $this;
        }
        catch(PDOException $e){
            return NULL;
            error_log('CATEGORIESMODEL::get($id)--> No se pudo realizar la consulta.');
        }
    }
    public function delete($id){
    try{
        $query=$this->prepare('DELETE FROM categories WHERE id=:id');
        $query->execute(['id'=> $id]);
        return true;
        }
        catch(PDOException $e){
            return false;
            error_log('CATEGORIESMODEL::delete($id)--> No se pudo realizar la consulta.');
        }
    }
    public function update(){
     try{
        $query=$this->prepare('UPDATE categories SET name=:name, color=:color WHERE id=:id');
        $query->execute(['name'=> $this->name,
                          'color'=>$this->color]); //NOTA: como las propiedades the estos objetos ya estarian modificadas por el usuario, solo se necesita llamar al objeto con su atribuyo. 
        return true;
        }
        catch(PDOException $e){
            return false;
            error_log('CATEGORIESMODEL::update()--> No se pudo realizar la consulta.');
        }
    }

//Funciones de utilidad.
    public function exists($name){
        try{
            $query=$this->prepare('SELECT name FROM categories WHERE name=:name');
            $query->execute([
                'name'=> $this->name, //NOTA: como se van asignar valores a los objetos cuando se creen apartir de esta clase, podemos hacer referencia a los valores del objeto llamado.
            ]);
        if($query->rowCount()) return true;
        return false;

        }
        catch(PDOException $e){
            return false;
            error_log('CATEGORIESMODEL::exists($name)--> No se pudo guardar los datos en la base de datos.');
        }
    }


    public function from($array){
        $this->id=$array['id'];
        $this->name=$array['name'];
        $this->color=$array['color'];
    }

//Funciones para definir variables.
    public function setId($value)      {$this->      id=$value;}
    public function setName($value)  {$this->  name=$value;}
    public function setColor($value){$this->color=$value;}

//Funciones para obtener variables.
    public function getId()      {$this->   id;}
    public function getName()  {$this-> name;}
    public function getColor(){$this->color;}
}


?>