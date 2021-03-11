<?php

require_once '../model/comandaModel.php';
// require_once '../view/restauranteView.php'

class restauranteController{
    private $model;
    //private $view;

    public function __construct(){
        $this->model = new comandaModel();
        //$this->view = new restauranteView();
    }

    // Control de logueo
    function sessionController(){
        if(!isset($_SESSION)){
            session_start();
            
            if(!isset($_SESSION['usuario'])){
                header("Location:".BASE_URL."login"); //Si el usuario no está logueado, lo dirige al login
            }else{
                $_SESSION['timeout'] = time(); //De lo contrario, comienza a correr el tiempo de sesión
            }
        }
    }

    function comandaController($params=null){
        // Controlo que el usuario esté logueado
        $this->sessionController();

        if(isset($_POST['nro_comanda'])&&($_POST['nro_mesa'])){
            $comanda = $_POST['nro_comanda'];
            $mesa = $_POST['nro_mesa'];
            $cerrada = false; // Es false por defecto. Cuando se cierre la mesa, el usuario realiza
                            // un update de la tabla en donde cambia "cerrada" a true;

            // Chequeo que no exista ninguna comanda asignada a la mesa ingresada
            $chequeaComanda = $this->model->getComandas($mesa);

            // Si la mesa ingresada tiene una mesa abierta, entonces envio mensaje de error
            if($chequeaComanda > 0){
                $this->view->mensaje("La mesa $mesa ya tiene una comanda abierta");
                die();
            }else{
                // De lo contrario, envío los datos al model y agrego la nueva comanda
                $accion = $this->model->agregaComanda($comanda, $mesa, $cerrada);

                if($accion > 0){
                    $this->view->mensaje("Comanda agregada exitosamente");
                    die();
                }else{
                    $this->view->mensaje("No se pudo agregar la comanda");
                }
            }
        }else{
            $this->view->mensaje("Debe completar todos los datos requeridos");
        }
    }
}


