<?php

class Admin extends SessionController{

    function __construct(){
        parent::__construct();
    }

function render(){
    $stats= $this->getStatistics();
    $this->view->render('admin/index',[
        'stats'=>$stats
    ]);
}

function createCategory(){
    $this->view->render('admin/create-category');
}

function newCategory(){
    if($this->existPOST(['name','color'])){
        $name= $this->getPOST('name');
        $color= $this->getPOST('color');

        $categoriesModel= new CategoriesModel();
        if(!$categoriesModel->exists($name)){
            $categoriesModel->setName($name);
            $categoriesModel->save();

            $this->redirect['admin',[]] //TODO::
        }else{
            $this->redirect['admin',[]] //TODO::
        }
    }
}

//La siguiente funcion utiliza max, el cual es un metodo ya definido de php. Lo que realiza esta funcion es hacer un bucle donde se compara el valor de $max y los expenses del array $expenses y la variable final toma el valor mas alto. 
private function getMaxAmount($expenses){
    $max=0;

    foreach($expenses as $expense){
        $max= max($max, $expense->getAmount());
    }

    return $max;
}

private function getMinAmount($expenses){
   $min= $this->getMaxAmount($expenses);

   foreach($expenses as $expense){
    $min=min($min,$expense->getAmount());
   }
   return $min;
}

private function getAverageAmount($expenses){
    $sum=0;

    foreach($expenses as $expense){
        $sum+= $expense->getAmount();
    }
    return ($sum/count($expenses));
}

private function getCategoryMostUsed($expenses){
   $repeat=[];
   
   foreach($expenses as $expense){
    if(!array_key_exists($expense->getCategoryId(),$repeat)){ //The function returns true if the specified key is found in the array otherwise returns false.
        $repeat[$expense->getCategoryId()]=0; //Si es que no existe un gasto en expenses, entonce esta funcion le asigna un valor de 0.
   }
   $categoryMostUsed = max($repeat); //Toma el valor mas alto de expenses con su id. 
   $categoryModel= new CategoriesModel();
   $categoryModel->get($categoryMostUsed);

   $category=$categoryModel->getName();

   return $category;
}

}

private function getCategoryLessUsed($expenses){
    $repeat=[];
    
    foreach($expenses as $expense){
     if(!array_key_exists($expense->getCategoryId(),$repeat)){ //The function returns true if the specified key is found in the array otherwise returns false.
         $repeat[$expense->getCategoryId()]=0; //Si es que no existe un gasto en expenses, entonce esta funcion le asigna un valor de 0.
    }
    $categoryLessUsed = min($repeat); //Toma el valor mas alto de expenses con su id. 
    $categoryModel= new CategoriesModel();
    $categoryModel->get($categoryLessUsed);
 
    $category=$categoryModel->getName();
 
    return $category;
 }
 
 }


function getStatistics(){ 
    $res=[];

    $userModel= new UserModel();
    $users=$usermodel->getAll();

    $expenseModel= new ExpensesModel();
    $expenses= $expenseModel->getAll();

    $categoriesModel= new CategoriesModel();
    $categories= $categoriesModel->getAll();

    $res=['count-users']=count($users);
    $res=['count-expenses']=count($expenses);
    $res=['max-expenses']= $this->getMaxAmount($expenses);
    $res=['min-expenses']= $this->getMinAmount($expenses);
    $res=['avg-expenses']= $this->getAverageAmount($expenses);
    $res=['count-categories']=count($categories);
    $res=['mostused-categories']=$this->getCategoryMostUsed($expenses);
    $res=['lessused-categories']=$this->getCategoryLessUsed($expenses);
    
    return $res;
}
}

?>