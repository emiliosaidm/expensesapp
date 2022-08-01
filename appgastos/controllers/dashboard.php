<?php
 require_once 'models/expensesmodel.php';
 require_once 'models/categoriesmodel.php';
 require_once 'models/usermodel.php';
 require_once 'classes/sessioncontroller.php';
class Dashboard extends SessionController{//El controlador de login se va a extender del padre 'Controller'

private $user;

function __construct(){
    parent::__construct();//Se llama el constructor de la clase padre. Es decir, el controlador Controller.
error_log('Dashboard::__contruct--> Se esta ejecutando la clase login');
$this->user=$this->getUserSessionData();
}

 function render(){
    $expensesModel= new ExpensesModel();
    $categories = $this->getCategories();
    $totalThisMonth= $expensesModel->getTotalAmountThisMonth($this->user->getId());
    $maxExpensesThisMonth=$expensesModel->getMaxExpensesThisMonth($this->user->getId());
    $expenses=$this->getExpenses(5);
    $this->view->render('dashboard/index',[
        'user'=>$this->user,
        'expenses'=>$expenses,
        'totalAmountThisMonth'=>$totalThisMonth,
        'maxExpensesThisMonth'=>$maxExpensesThisMonth,
        'categories'=>$categories

    ]);//Se va a renderizar la vista que deseamos tener. En este caso, se llama a la variable view y se ejecuta la funcion render haciendo atributo a la vista de login/index.
 
}

 private function getExpenses($n=0){
    if($n<0) {
        return NULL;}
    else{
     $expenses= new ExpensesModel();
    return $expenses->getAllByUserIdAndLimit($this->user->getId(),$n);
    }
 }

 private function getCategories(){
    $res = [];
    $categoriesModel=new CategoriesModel();
    $expensesModel = new ExpensesModel();
    $categories = $categoriesModel->getAll();

    foreach($categories as $category){
        $categoryArray=[];

        $total= $expensesModel->getTotalByCategoryThisMonth($category->getId(), $this->user->getId());
        $numberOfExpenses = $expensesModel->getNumberOfExpensesByCategoryThisMonth($category->getId());

        if($numberOfExpenses>0){
            $categoryArray['total']=$total;
            $categoryArray['count']=$NumberOfExpenses;
            $categoryArray['category']=$category;
            array_push($res,$categoryArray);
        }
    }
    return $res;
 }

}
?>