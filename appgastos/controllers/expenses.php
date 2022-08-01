<?php
require_once 'models/expensesmodel.php';
require_once 'models/categoriesmodel.php';
require_once 'models/usermodel.php';
require_once 'models/joinexpensescategoriesmodel.php';
class Expenses extends SessionController{ //Session controller controla el acceso a los controladores.
   private $user;

function __construct(){
  parent::__construct();
  $this->user=$this->getUserSessionData();
}
function render(){
    $this->view->render('expenses/index',['user'=>$this->user]);
}

//Fnction que activa un nuevo expense.
function newExpense(){
    if(!$this->existPOST(['titleamount','category','date'])){
        $this->redirect('dashboard',[]); //TODO:
        return;
    }
    if($this->user=NULL){
        $this->redirect('dashboard',[]); //TODO:
        return;
    }
    $expense=new ExpensesModel();
    $expense->setTitle($this->getPost('title'));
    $expense->setAmount($this->getPost('amount'));
    $expense->setCategoryId($this->getPost('category'));
    $expense->setDate($this->getPost('date'));
    $expense->setUserId($this->user->getId());

    $expense->save();
    $this->redirect('dashboard',[]); 
}

//Esta funcion va a mostrar la vista para poder llenar los gastos.
public function create(){
    $categories = new CategoriesModel();
    $all=$categories->getAll();
    $this->view->render('expenses/create',[
        'categories'=>$all, //Lo que se va a enviar por medio de esta funcion es array con toda la informacion de las categories. Este fue definido en el modelo de categories.
         'user'=>$this->user 
    ]);
}

function getCategoriesId(){
    $joinModel = new JoinExpensesCategoriesModel();
    $categories= $joinModel->getAll($this->user->getId());
    $res=[];
    foreach($categories as $cat){
        array_push($res,$cat->getCategoryId());
    }
    $res=(array_values(array_unique($res))); // Array_value elemina los indices de un array ('indice=> valor) y solo devuelve el valor. Array_unique evita que se repitan valores de una matriz ya que si es que pasa los elimina y devuelve una matriz con valores unnicos.
    return $res;
}

private function getDateList(){
    $month = [];
    $res=[];
    $joinModel = new JoinExpensesCategoriesModel();
    $expenses= $joinModel->getAll($this->user->getId());
    foreach($expenses as $expense){
        array_push($months,substr($expense->getDate(), 0, 7));//LA funcion substr elimina una parte de un string. En este caso, va a tomar la fecha y va suprimir desde la posicion 0 hasta la 7 (que solo son los meses). Lo demas sera eliminado del string.
    }
    $months=array_values(array_unique($months));
    
    if(count($months>3)){
        array_push($res, array_pop($res)); //Array_pop elimina el ultimo elemento de un array.
        array_push($res, array_pop($res));
        array_push($res, array_pop($res));
    }

    return $res;
}

//Va a mostrar una lista de categories donde ha incluido expenses.
function getCategoryList(){
    $res=[];
    $joinModel = new JoinExpensesCategoriesModel();
    $expenses= $joinModel->getAll($this->user->getId());
    foreach($expenses as $expense){
        array_push($res, $expense->getNameCategory());
    }
    $res=array_values(array_unique($res));
    return $res;
}

//La siguiente funcion va a devlover los coloeres de las categorias .

function getCategoryColorList(){
    $res=[];
    $joinModel = new JoinExpensesCategoriesModel();
    $expenses= $joinModel->getAll($this->user->getId());
    foreach($expenses as $expense){
        array_push($res,$expense->getColor());
    }
    $res=array_unique($res);
    $res=array_values(array_unique($res));
    return $res;
}

//La siguiente funcion va a recibir la informacion utilizando AJAX. FUnciona como una api.

function getHistoryJSON(){
    header('Content-Type: application/json');//Header envia informacion al header del html. EN este caso, estamos indicando que se va a incluir un archivo JSON.
    $res=[];
    $joinModel = new JoinExpensesCategoriesModel();
    $expenses= $joinModel->getAll($this->user->getId());
    foreach($expenses as $expense){
        array_push($res,$expense->toArray());
    }
    echo json_encode($res); //Si se llama getHistoryJSON el array $res va a guardar toda la informacion del usuario en otro array. Es decir, va haber un array dentro de otro. Posteriormente, el array $res se va a codificar al idioma de JSON.
}

function getExpensesJSON(){
    header('Content-Type: application/json');

    $res=[];
    $categoryIds=$this->getCategoriesId();
    $categoryNames=$this->getCategoryList();
    $categoryColors=$this->getCategoryColorList();
    
    array_unshift($categoryNames,'mes'); //La funcion array_unshift agrega un nuevo elemento al inicio de un array definido. 
    array_unshift($categoryColors,'categories');

    $months= $this->getDateList();

    for($i=0; $i<count($months);$i++){
        $item= array($months[$i]);
        for($j=0;$j<count($categoryIds);$j++){
            $total=$this->getTotalByMonthAndCategory($months[$i],$categoryIds[$j]);
            array_push($item,$total);
        }
        array_push($res,$item);
    }
    array_unshift($res,$categoryNames);
    array_unshift($res,$categoryColors);
    echo json_encode($res);
}

private function getTotalByMonthAndCategory($date,$categoryid){
    $iduser=$this->user->getId();

    $total=$this->model->getTotalByMonthAndCategory($date, $categoryid, $iduser);
    if($total==NULL){
        $total=0;
    }
    return $total;
}

function delete($params){
    if($params==NULL){
        $this->redirect('expenses',[]); //TODO:  
    }
    $id=$params[0];
    $res=$this->model->delete($id);

    if($res){
        $this->redirect('expenses',[]); //TODO:
    }
    else{
        $this->redirect('expenses',[]); //TODO:
    }
}

}

?>