<?php

require_once '../api/APIController.php';
//require_once '../model/...';

class restauranteController extends APIController{
    //private $model;

    public function __construct(){
        parent::__construct();
        //$this->model = new ...;
    }


    function sessionController(){
        if(!isset($_SESSION)){
            session_start();
            
            if(!isset($_SESSION['usuario'])){
                header("Location:".BASE_URL."login");
            }else{
                if($_SESSION['admin'] === true){
                    $_SESSION['timeout'] = time();
                }else{
                    return false;
                    die();
                }
                
            }
        }
    }


    function estadoController($params=null){
        //ENDPOINT: (GET) api/comanda/mesa/:nro_mesa;
        $body = $this->getData();

        $accion = $this->model->getComandaPorMesa($body->nro_mesa);

        if($accion){
            $this->view->response($accion, 200);
        }else{
            $this->view->response("La mesa ingresada no tiene comandas cargadas", 404);
        }
    }

    function itemController($params=null){
        // ENDPOINT: (POST) api/item/comanda/:ID;
        $body = $this->getData();

        $item = $body->item;
        $cantidad = $body->cantidad;
        $precio = $body->precio_unitario;
        $comanda = $body->id_comanda;

        $accion = $this->model->agregaItem($item, $cantidad, $precio, $comanda);

        if($accion){
            $this->view->response("Item agregado", 200);
        }else{
            $this->view->response("Hubo un error, revise los campos y reintente", 404);
        }
    }

    function actualizaComanda($params=null){
        // ENDPOINT: (PUT) api/comanda/:id

        $admin = $this->sessionController();
        $body = $this->getData();

        if($admin === true){
            $estado = $body->cerrada;

            $accion = $this->model->actualizaComanda($estado);
    
            if($accion){
                $this->view->response("El estado de la comanda fue actualizado", 200);
            }else{
                $this->view->response("Hubo un error, reintente", 404);
            }
        }else{
            $this->view->response("Usted no posee los permisos para realizar esta tarea", 404);
        }

        
    } 
}