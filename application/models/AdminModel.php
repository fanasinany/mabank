<?php 

class AdminModel extends CI_Model{

    private $table = 'clients';

    public function verifyNIB($nib){
        $sql = "SELECT * FROM $this->table WHERE nib = '$nib' ";
        $query = $this->db->query($sql);
        if($query->num_rows() == 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function addClienttoDB($nom,$prenom,$naissance,$sexe,$adresse,$nib,$phone,$password){
        $data  = array(
            'nom' => $nom,
            'prenom' => $prenom,
            'date_naissance' => $naissance,
            'sexe' => $sexe,
            'adresse' => $adresse,
            'nib' => $nib,
            'phone' => $phone,
            'password' => password_hash($password,PASSWORD_BCRYPT),
            'solde_cli' => 0,
            'created_at' => date("Y-m-d H:i:s")
        );

        $this->db->insert($this->table, $data);
    }

    public function getAllClient(){
        $sql = "SELECT * FROM $this->table ORDER BY created_at DESC";
        $query = $this->db->query($sql);
        return $query->result_array(); 
    }

    public function searchCliByNomNib($nom, $nib){
        $sql = "SELECT * FROM $this->table WHERE nib = '$nib' AND (nom LIKE '%$nom%' OR prenom LIKE '%$nom%')";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function searchCliByNom($nom){
        $sql = "SELECT * FROM $this->table WHERE  (nom LIKE '%$nom%' OR prenom LIKE '%$nom%')";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function searchCliByNib($nib){
        $sql = "SELECT * FROM $this->table WHERE nib = '$nib'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getLastTransaction($id){
        $sql = "SELECT * FROM transaction WHERE id_cli = '$id' ORDER BY date_transaction DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getClientByID($id){
        $sql = "SELECT * FROM $this->table WHERE id_cli = '$id' ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function editClientByID($data, $id){
        $this->db->where('id_cli', $id);
        $this->db->update($this->table, $data);
    }
}