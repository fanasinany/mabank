<?php 

class ClientModel extends CI_Model{

    private $table = 'clients';

    public function getClientByID($id){
        $sql = "SELECT id_cli, nom, prenom, nib, solde_cli FROM $this->table WHERE id_cli='$id' ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function addVersement(int $montant,int $id_cli){
        $data = array(
            'id_cli' => $id_cli,
            'etat' => 'Versement',
            'montant' => $montant,
            'date_transaction' => date("Y-m-d H:i:s")
        );
    
        $this->db->insert('transaction', $data);

        $query = $this->db->query("SELECT solde_cli FROM $this->table WHERE id_cli = '$id_cli' ");
        foreach($query->result() as $row){
            (int)$solde = $row->solde_cli;
        }
        $solde_now = $solde + $montant;
        $this->db->where('id_cli', $id_cli);
        $this->db->update($this->table, array("solde_cli" => $solde_now));
    }

    public function verifySoldeAfterRetrait(int $montant, int $id_cli){
        $solde = $this->getSolde($id_cli);
        if($solde >= $montant){
            $this->addRetrait($montant, $id_cli);
            return true;
        }
        else{
            return false;
        }
    }

    public function addRetrait(int $montant, int $id_cli){
        $data = array(
            'id_cli' => $id_cli,
            'etat' => 'Retrait',
            'montant' => $montant,
            'date_transaction' => date("Y-m-d H:i:s")
        );
    
        $this->db->insert('transaction', $data);

        $query = $this->db->query("SELECT solde_cli FROM $this->table WHERE id_cli = '$id_cli' ");
        foreach($query->result() as $row){
            (int)$solde = $row->solde_cli;
        }
        $solde_now = $solde - $montant;
        $this->db->where('id_cli', $id_cli);
        $this->db->update($this->table, array("solde_cli" => $solde_now));
    }

    public function getSolde(int $id){
        $query = $this->db->query("SELECT solde_cli FROM $this->table WHERE id_cli = '$id' ");
        foreach($query->result() as $row){
            (int)$solde = $row->solde_cli;
        }
        return $solde;
    }

    public function getLastTransaction($id){
        $sql = "SELECT * FROM transaction WHERE id_cli = '$id' ORDER BY date_transaction DESC LIMIT 5";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

}