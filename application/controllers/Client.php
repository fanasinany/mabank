<?php 

class Client extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("ClientModel");
    }

    public function index(){
        if(isset($_SESSION["id_cli"]) && isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]){
            $datas = $this->ClientModel->getClientByID($_SESSION["id_cli"]);
            $data = array_merge(array('content'=>'clients/home_page'),$datas[0]);
            $this->load->view('layout/main_client',$data);
        }
        else{
            redirect('connexion');
        }
    }

    public function logout(){
        session_destroy();
        redirect('connexion');
    }

    public function versement(){
        if($_POST["nib"] != "" && $_POST["montant"] != ""){
            $this->ClientModel->addVersement($_POST["montant"], (int)$_SESSION["id_cli"]);
        }
        else {
            show_error("Champ requis", 500);
        }
    }

    public function retrait(){
        if($_POST["nib"] != "" && $_POST["montant"] != ""){
            $ok = $this->ClientModel->verifySoldeAfterRetrait($_POST["montant"], (int)$_SESSION["id_cli"]);
            if($ok){
                return "Retrait reussi";
            }
            else{
                show_error("Retrait impossible", 404);
            }
        }
        else {
            show_error("Champ requis", 500);
        }   
    }

    public function getSolde(){
        $solde = $this->ClientModel->getSolde($_SESSION["id_cli"]);
        echo $solde;
    }

    public function getLastTransaction(){
        $table_trans = "";
        $data = $this->ClientModel->getLastTransaction($_SESSION["id_cli"]);
        foreach($data as $dt){
            $etat = $dt["etat"];
            $montant = $dt["montant"];
            $d = new DateTime($dt["date_transaction"]);
            $date_transaction = $d->format('d/m/Y H:i:s');
            
            $table_trans .= "<tr>
            <td>$date_transaction</td>
            <td>$etat</td>
            <td>$montant</td>
            </tr>";
        }
        if($table_trans != ""){
            echo $table_trans;
        }else{
            echo "<tr>
            <td></td>
            <td class=\"text-center\">Aucune transaction effectuée</td>
            <td></td></tr>";
        }
    }

}