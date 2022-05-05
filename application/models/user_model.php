<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getUsers()
    {
        $result = $this->db->SELECT('*')
            ->FROM('users')
            ->WHERE('status = "Active"')
            ->GET()
            ->RESULT_ARRAY();
        return $result;
    }

    public function duplicate($username)
    {
        $result = $this->db->SELECT('*')
            ->FROM('users')
            ->WHERE('username', $username)
            ->GET()
            ->ROW();
        return $result;
    }
}
