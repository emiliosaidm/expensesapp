<?php
 interface IModel{// Una interfaz es un almacenador de clases. Permite que otras clases llamen distintos metodos.
    public function save();
    public function getAll();
    public function get($id);
    public function delete($id);
    public function update();
    public function from($array);
 }//Los metodos previamente definidos no tienen especifiaciones ya que esos se establecerian al llamar el metodo en una clase.
?>