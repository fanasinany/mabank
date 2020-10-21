<?php 

class Connexion extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("ConnexionModel");
    }

    public function index(){
        if(isset($_SESSION["id_cli"]) && isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]){
            redirect('client');
        }
        else{
            $this->load->view('clients/login_page');
        }
    }

    public function login(){
        if($_POST["nib"] != "" && $_POST["password"] != ""){
            if($this->ConnexionModel->verifyLoginClient($_POST["nib"], $_POST["password"])){
                return "connection ok";
            }
            else{
                show_error("Identifiants incorrects", 404);
            }
        }else{
            show_error("Champs requis",500);
        }
    }
}