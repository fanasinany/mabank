<?php 

class ConnexionModel extends CI_Model{

    private $table_user = 'user';
    private $table_client = 'clients';

    public function verifyLogin($pseudo, $password){
        $sql = "SELECT * FROM $this->table_user WHERE pseudo = '$pseudo' ";
        $query = $this->db->query($sql);
        if($query->num_rows() == 1){
			foreach($query->result() as $row){
				$pswhash = $row->password;
                $id_user = $row->id_user;
                $status = $row->status;
            }
            if(password_verify($password, $pswhash)){
                $data_session = array(
                    'id_user' => $id_user,
                    'pseudo' => $pseudo,
                    'status' => $status,
                    'logged_in' => TRUE
                );
                $this->session->set_userdata($data_session);
                return true;
            }else return false;
        }else return false;
    }

    public function verifyLoginClient($nib, $password){
        $sql = "SELECT * FROM $this->table_client WHERE nib = '$nib' ";
        $query = $this->db->query($sql);
        if($query->num_rows() == 1){
			foreach($query->result() as $row){
				$pswhash = $row->password;
                $id_cli = $row->id_cli;
            }
            if(password_verify($password, $pswhash)){
                $data_session = array(
                    'id_cli' => $id_cli,
                    'logged_in' => TRUE
                );
                $this->session->set_userdata($data_session);
                return true;
            }else return false;
        }else return false;
    }
}