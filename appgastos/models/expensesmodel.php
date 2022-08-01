<?php
require_once 'libs/model.php';
class ExpensesModel extends Model{

    private $id;
    private $title;
    private $amount;
    private $categoryid;
    private $date;
    private $userid;

//Funciones set:
public function setId($id)                {$this->                id=$id;}
public function setTitle($title)          {$this->          title=$title;}
public function setAmount($amount)        {$this->        amount=$amount;}
public function setCategoryId($categoryid){$this->categoryid=$categoryid;}
public function setDate($date)            {$this->            date=$date;}
public function setUserId($userid)        {$this->        userid=$userid;}

//Funciones get:
public function getId()        {return $this->        id;}
public function getTitle()     {return $this->     title;}
public function getAmount()    {return $this->    amount;}
public function getCategoryId(){return $this->categoryid;}
public function getDate()      {return $this->      date;}
public function getUserId()    {return $this->    userid;}

//Funciones acotadas en masa:
public function from($array){ //From permite desglosar los atributos de una matriz en diferentes variables.
    $this->id=$array['id'];
    $this->title=$array['title'];
    $this->amount=$array['amount'];
    $this->categoryid=$array['category_id'];
    $this->date =$array['date'];
    $this->userid=$array['id_user'];
}

//Funciones para modificar la base de datos.
public function save(){
    try{
        $query=$this->prepare('INSERT INTO expenses(title, amount, category_id, date, id_user) VALUES (:title, :amount, :category_id, :date, :id_user) ');
        $query->execute([
            'title'=>$this->title,
            'ampunt'=>$this->amount,
            'category_id'=>$this->categoryid,
            'date'=>$this->date,
            'id_user'=>$this->userid
        ]);
        if($query->rowCount()) return true; // Si es que se contabiliza que se agrago una file, entonces devuelce true.
    }
    catch(PDOException $e){
        return false;
        error_log('EXPENSESMODEL::Save()--> No se puedo guardar la consulata.'.$e);
        }
}

public function getAll(){
    try{
        $items=[];
        $query=$this->prepare('SELECT * FROM expenses');
        while($r=$query->fetch(PDO::FETCH_ASSOC)){
            $item= new ExpensesModel();
            $item->setTitle($r['title']);
            $item->setAmount($r['amount']);
            $item->setCategoryId($r['category_id']);
            $item->setDate($r['date']);
            $item->setUserId($r['id_user']);
            array_push($items,$item);
        }
        return $items;
    }
    catch(PDOException $e){
        return false;
        error_log('EXPENSESMODEL::getAll()--> No se puedo generar la consulata.'.$e);
    }
}

public function get($id){
    try{
        $query= $this->prepare('SELECT * FROM expenses WHERE id=:id');
        $query->execute([':id'=>$id]);
        $expense=$query->fetch(PDO::FETCH_ASSOC);
        $this->setTitle($expense['title']);
        $this->setAmount($expense['amount']);
        $this->setCategoryId($expense['category_id']);
        $this->setDate($expense['date']);
        $this->setUserId($expense['id_user']);
        return $this;
    }
    catch(PDOException $e){
        return false;
        error_log('EXPENSESMODEL::get($id)--> No se puedo generar la consulata.'.$e);

    }
}

public function delete($id){
    try{
        $query= $this->prepare('DELETE  FROM expenses WHERE id=:id');
        $query->execute([':id'=>$id]);
        return true;
    }
    catch(PDOException $e){
        return false;
        error_log('EXPENSESMODEL::delete($id)--> No se puedo generar la consulata.'.$e);
    }
}

public function update($id){
    try{
        $query=$this->prepare('UPDATE expenses SET title=:title, amount=:amount, category_id=:category_id, date=:date. id_user=:userid WHERE id=:id');
        $query->execute([
            'title'=>      $this->title,
            'amount'=>     $this->amount,
            'category_id'=> $this->categoryid,
            'date'=>       $this->date,
            'id_user'=>    $this->id_user,
            'id'=>         $this->id 
        ]);
        if($query->rowCount()) return true;
    }
    catch(PDOException $e){
        return false;
        error_log('EXPENSESMODEL::update($id)--> No se puedo generar la consulata.'.$e);
        }
}

public function getAllByUserId($userid){
    try{ 
         $items=[];
         $query=$this->prepare('SELECT * FROM expenses WHERE id_user=:userid');
         $query->execute(['userid'=>$userid]);
         while($r=$query->fetch(PDO::FETCH_ASSOC)){
            $item=new ExpenseModel();
            $item->from($r);
            array_push($items,$item);
         }
        return $items;
    }
    catch(PDOException $e){
        return [];
        error_log('EXPENSESMODEL::getAllByUserId($userid)--> No se puedo generar la consulata.'.$e);

    }
}

public function getAllByUserIdAndLimit($userid, $n){
    try{ 
         $items=[];
         $query=$this->prepare('SELECT * FROM expenses WHERE id_user=:userid ORDER BY expenses.date DESC LIMIT 0, :n'); //Esta consulta arrojara resultados ordenados decendentemente desde el primer registro hasta el $n.
         $query->execute([
            'userid'=>$userid,
            'n'=>$n
        ]);
         while($r=$query->fetch(PDO::FETCH_ASSOC)){
            $item=new ExpenseMode();
            $item->from($r);
            array_push($items,$item);
         }
        return $items;
    }
    catch(PDOException $e){
        return [];
        error_log('EXPENSESMODEL::getAllByUserIdAndLImit($userid)--> No se puedo generar la consulata.'.$e);

    }
}

public function getTotalAmountThisMonth($userid){
    try{ 
        $year=date('Y');
        $month=date('M'); 
         $query=$this->prepare('SELECT SUM(amount) AS total FROM expenses WHERE YEAR(date)=:year AND MONTH(date)=:month AND id_user=:iduser'); //Esta consulta arrojara resultados ordenados decendentemente desde el primer registro hasta el $n.
         $query->execute([
            'iduser'=>$userid,
            'year'=>$year,
            'month'=>$month
        ]);
        $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
        if($total==NULL) return $total=0;
        return $total;
         }
    catch(PDOException $e){
        return NULL;
        error_log('EXPENSESMODEL::getTotalAmountThisMonth($userid)--> No se puedo generar la consulata.'.$e);
    }
}

    public function getMaxExpensesThisMonth($userid){ //Va a regresar el gasto mas alto del mes.
    
    try{ 
        $year=date('Y');
        $month=date('M'); 
         $query=$this->prepare('SELECT MAX(amount) AS total FROM expenses WHERE YEAR(date)=:year AND MONTH(date)=:month AND id_user=:userid'); //Esta consulta arrojara resultados ordenados decendentemente desde el primer registro hasta el $n.
         $query->execute([
            'userid'=>$userid,
            'year'=>$year,
            'month'=>$month,
        ]);
        $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
        if($total==NULL) return $total=0;
        return $total;
         }

    catch(PDOException $e){
        return NULL;
        error_log('EXPENSESMODEL::getMaxExpensesThisMonth($userid)--> No se puedo generar la consulata.'.$e);
    }
}

    public function getTotalByCategoryThisMonth($categoryid, $userid){ //Va a regresar el gasto mas alto del mes.
        try{ 
             $total=0;
             $year=date('Y');
             $month=date('M'); 
             $query=$this->prepare('SELECT SUM(amount) AS total FROM expenses WHERE category_id=:category_id AND YEAR(date)=:year AND MONTH(date)=:month AND id_user=:userid'); //Esta consulta arrojara resultados ordenados decendentemente desde el primer registro hasta el $n.
             $query->execute([
                'userid'=>$userid,
                'year'=>$year,
                'month'=>$month,
                'category_id'=>$categoryid
            ]);
            $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
            if($total==NULL) return $total=0;
            return $total;
             }
        
        catch(PDOException $e){
            return NULL;
            error_log('EXPENSESMODEL::getTotalByCategoryThisMonth($userid)--> No se puedo generar la consulata.'.$e);
        } 
}

function getTotalByMonthAndCategory($date,$category, $userid){
    try{
        $total=0;
        $year=substr($date, 0, 4);
        $month=substr($date, 5,7);

        $query=$this->prepare('SELECT SUM(amount) AS total FROM expenses WHERE category_id=:category_id AND id_user=:user AND YEAR(date)=:year AND MONTH(date)=:date');
        $query->execute([
         'userid'=>$userid,
         'category_id'=>$categoryid,
         'year'=>$year,
         'month'=>$month

        ]);
        if($query->rowCount()>0){
            $total=$query->fetch(PDO::FETCH_ASSOC)['total'];
            return $total;
        }
        else{
            return 0;
        }
        }
    catch(PDOException $e){
        return NULL;
        error_log('JoinExpensesCategoriesModel::getAll($userid)--> No se pudo realizar la consulta.');
    }
}

public function getNumberOfExpensesByCategoryThisMonth($categoryid, $userid){ //Va a regresar el gasto mas alto del mes.
    try{ 
         $total=0;
         $year=date('Y');
         $month=date('M'); 
         $query=$this->prepare('SELECT COUNT(amount) AS total FROM expenses WHERE category_id=:category_id AND YEAR(date)=:year AND MONTH(date)=:month AND id_user=:userid'); //Esta consulta arrojara resultados ordenados decendentemente desde el primer registro hasta el $n.
         $query->execute([
            'userid'=>$userid,
            'year'=>$year,
            'month'=>$month,
            'category_id'=>$categoryid
        ]);
        $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
        if($total==NULL) return $total=0;
        return $total;
         }
    
    catch(PDOException $e){
        return NULL;
        error_log('EXPENSESMODEL::getNumberOfExpensesByCategoryThisMonth($userid)--> No se puedo generar la consulata.'.$e);
    } 
}
}
?>