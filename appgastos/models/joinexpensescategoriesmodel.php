<?php
require_once 'libs/model.php';
class JoinExpensesCategoriesModel extends Model{
    private $expenseId;
    private $title;
    private $amount;
    private $categoryId;
    private $date;
    private $userId;
    private $nameCategory;
    private $color;

public function __construct(){
    parent::__construct();
}

public function getAll($userid){
    try{
        $items=[];
        $query=$this->prepare('SELECT expenses.id as expense_id, title, category_id, amount, date, id_user, categories.id, name, color FROM expenses INNER JOIN categories WHERE expenses.category_id =categories.id AND expenses.id_user=:userid ORDER BY date');
        $query->execute([
         'userid'=>$userid
        ]);
        while($r=$query->fetch(PDO::FETCH_ASSOC)){
            $item= new JoinExpensesCategoriesModel();
            $item->from($r);
            array_push($items,$item);
        }
        return $items;
    }
    catch(PDOException $e){
        return NULL;
        error_log('JoinExpensesCategoriesModel::getAll($userid)--> No se pudo realizar la consulta.');
    }

}

public function from($array){
    $this->expenseId=$array['expense_id'];
    $this->title=$array['title'];
    $this->amount=$array['amount'];
    $this->date=$array['date'];
    $this->userId=$array['id_user'];
    $this->nameCategory=$array['category_id'];
    $this->color=$array['color'];
}

public function toArray(){
    $array=[];
    $array['expense_id']= $this->expenseId;
    $array['title']= $this->title;
    $array['amount']=$this->amount;
    $array['date']=$this->date;
    $array['id_user']=$this->userId;
    $array['category_id']=$this->nameCategory;
    $array['color']=$this->color;
    return $array;
}

//Funciones set.
public function setExpenseId($value){$this->expenseId=$value;}
public function setTitle($value){$this->title=$value;}
public function setAmount($value){$this->amount=$value;}
public function setDate($value){$this->date=$value;}
public function setUserId($value){$this->userId=$valeu;}
public function setNameCategory($value){$this->nameCategory=$value;}
public function setColor($value){$this->color=$color;}

//Funciones get.
public function getExpenseId(){$this->expense;}
public function getTitle(){$this->title;}
public function getAmount(){$this->amoumt;}
public function getDate(){$this->date;}
public function getUserId(){$this->userId;}
public function getNameCategory(){$this->nameCategory;}
public function getColor(){$this->color;
}





}

?>