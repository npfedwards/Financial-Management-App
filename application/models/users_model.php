<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function getUser($id) {
        $user=$this->db->query("SELECT * FROM users WHERE userid='$id' LIMIT 0,1");
        return ($user->num_rows() > 0) ? $user->row() : false;
    }

}
