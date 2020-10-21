<?php 

class Admin extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("ConnexionModel");
        $this->load->model("AdminModel");
    }

    public function index(){
        if(isset($_SESSION["id_user"]) && isset($_SESSION["pseudo"]) && $_SESSION["logged_in"]){
            $this->home();
        }
        else{
            $this->load->view('admin/login_page');
        }   
    }

    public function login(){
        if($_POST["pseudo"] != "" && $_POST["password"] != ""){
            if($this->ConnexionModel->verifyLogin($_POST["pseudo"], $_POST["password"])){
                return "connection ok";
            }
            else{
                show_error("Identifiants incorrects", 404);
            }
        }else{
            show_error("Champs requis",500);
        }
    }

    public function home(){
        $data = array('content'=>'admin/admin_page');
        $this->load->view('layout/main_admin',$data);
    }

    public function logout(){
        session_destroy();
        redirect('admin');
    }

    public function Search(){
        $data = array('content'=>'admin/search_page',);
        $this->load->view('layout/main_admin',$data); 
    }

    public function listAllClient(){
        $data = $this->AdminModel->getAllClient();
        $this->generateViewSearch($data);
    }

    public function ajaxSearch(){
        $nom = $_GET["nom"];
        $nib = $_GET["nib"];
        if( $nom != "" || $nib != ""){
            if($nom != "" && $nib != ""){
                $affichage= "";
                $data = $this->AdminModel->searchCliByNomNib($nom, $nib);
                $this->generateViewSearch($data);   
            }
            elseif($nib != ""){
                $affichage= "";
                $data = $this->AdminModel->searchCliByNib($nib);
                $this->generateViewSearch($data);
            }
            elseif($nom != ""){
                $data = $this->AdminModel->searchCliByNom($nom);
                $this->generateViewSearch($data);
            }
        }
    }

    public function generateViewSearch($data){
        $affichage= "";
        foreach($data as $dt){
            $id_cli = $dt["id_cli"];
            $nom = $dt["nom"];
            $prenom = $dt["prenom"];
            $nib = $dt["nib"];
            $solde = $dt["solde_cli"];
            
            $affichage .= "<tr>
            <td>$nom</td>
            <td>$prenom</td>
            <td>$nib</td>
            <td>$solde</td>
            <td>
            <button class=\"btn btn-link\" data-toggle=\"modal\" data-id=\"$id_cli\" data-target=\"#editModal\">
                <i class=\"fas fa-edit\"></i>
            </button>
            <button class=\"btn btn-link\" data-toggle=\"modal\" data-id=\"$id_cli\" data-target=\"#transactionModal\">
                <i class=\"fas fa-list\"></i>
            </button>
            </td>
            </tr>";
        }
        if($affichage != ""){
            echo $affichage;
        }else{
            echo "<tr>
            <td></td>
            <td></td>
            <td class=\"text-center\">Aucun Client trouvé</td>
            <td></td></tr>";
        }
    }

    //Creation Numero d'identité bancaire aleatoirement et test s'il n'est pas encore attribué à un client
    public function generateNIB(){
        $test = false;
        
        while ($test == false) {
            $nib = mt_rand(10000000000000,99999999999999);
            $test = $this->AdminModel->verifyNIB($nib);
        }
        echo $nib;
        
    }

    public function addClient(){
        $nom = $_POST["nom"];
        $prenom = $_POST["prenom"];
        $naissance = $_POST["naissance"];
        $sexe = $_POST["sexe"];
        $adresse = $_POST["adresse"];
        $nib = (int)$_POST["nib"];
        $phone = $_POST["phone"];
        $password = $_POST["password"];
        $password_deux = $_POST["password_deux"];

        if(!empty($nom) && !empty($prenom) && !empty($naissance) && !empty($sexe) 
        && !empty($adresse) && !empty($nib) && !empty($phone) && !empty($password) && !empty($password_deux)){
            if($password == $password_deux){
                $this->AdminModel->addClienttoDB($nom,$prenom,$naissance,$sexe,$adresse,$nib,$phone,$password);
            }
            else{
                show_error("Mot de passe different", 500);
            }
        }
        else{
            show_error("Champs requis", 404);
        }
    }

    public function getLastTransaction(){
        $table_trans = "";
        $data = $this->AdminModel->getLastTransaction($_GET["id"]);
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

    public function getClient(){
        $data = $this->AdminModel->getClientByID($_GET["id"]);
        foreach($data as $dt){
            $id = $dt["id_cli"];
            $nom = $dt["nom"];
            $prenom = $dt["prenom"];
            $adresse = $dt["adresse"];
            $phone = $dt["phone"];
            $d = new DateTime($dt["date_naissance"]);
            $date_naissance = $d->format('Y-m-d');
            
        }
        $affichage = "
               <div class=\"row\">
               <input id=\"id\" value=\"$id\" type=\"text\" hidden disabled>
                <div class=\"col-md-6\">
                    <div class=\"form-group\">
                        <label class=\"bmd-label-floating\">Nom</label>
                        <input id=\"nomP\" value=\"$nom\" type=\"text\" class=\"form-control\">
                    </div>
                </div>
                <div class=\"col-md-6\">
                    <div class=\"form-group\">
                        <label class=\"bmd-label-floating\">Prenom</label>
                        <input id=\"prenom\" value=\"$prenom\" type=\"text\" class=\"form-control\">
                    </div>
                </div>
                </div>

                <div class=\"row\">
                    <div class=\"col-md-3\">
                        <div class=\"form-group\">
                          <label class=\"bmd-label-floating\">Date de naissance</label>
                          <input id=\"naissance\" value=\"$date_naissance\" type=\"date\" class=\"form-control\">
                        </div>
                    </div>
                    <div class=\"col-md-6\">
                        <div class=\"form-group\">
                          <label class=\"bmd-label-floating\">Adresse</label>
                          <input id=\"adresse\" value=\"$adresse\" type=\"text\" class=\"form-control\">
                        </div>
                    </div>
                    <div class=\"col-md-3\">
                        <div class=\"form-group\">
                            <label class=\"bmd-label-floating\">Téléphone</label>
                            <input id=\"phone\" value=\"$phone\" type=\"number\" class=\"form-control\">
                        </div>
                    </div>
                </div>
                
                <button id=\"btnenregistrer\" class=\"btn btn-primary\" style=\"margin-left: 230px\">Enregistrer</button>
                <button class=\"btn btn-secondary\" data-dismiss=\"modal\">Annuler</button>
        ";
        echo $affichage;

    }

    public function editClient(){
        
        $id = $_POST["id"];
        $nom = $_POST["nom"];
        $prenom = $_POST["prenom"];
        $naissance = $_POST["naissance"];
        $adresse = $_POST["adresse"];
        $phone = $_POST["phone"];
        if($id != "" && $nom != "" && $prenom != "" && $naissance != "" && $adresse != "" && $phone != ""){
            $data = array(        
                'nom' => $_POST["nom"],
                'prenom' => $_POST["prenom"],
                'date_naissance' => $_POST["naissance"],
                'adresse' => $_POST["adresse"],
                'phone' => $_POST["phone"]
            );
            $this->AdminModel->editClientByID($data, $id);
        }
        else{
            show_error("Champs incomplet", 500);
        }
        
    }

    public function listClient(){
        if(isset($_SESSION["id_user"]) && isset($_SESSION["pseudo"]) && $_SESSION["logged_in"]){
            $data = array('content'=>'admin/list_page',);
            $this->load->view('layout/main_admin',$data); 
        }
        else{
            $this->load->view('admin/login_page');
        }  
    }
}