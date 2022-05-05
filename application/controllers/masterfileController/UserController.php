<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usercontroller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('upload');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('user_model');
        date_default_timezone_set('Asia/Manila');

        //Disable Cache
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        ('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }

    public function getUsers()
    {
        $users = $this->user_model->getUsers();
        return JSONResponse($users);
    }

    public function addUser()
    {
        $data      = $this->input->post(NULL, FILTER_SANITIZE_STRING);
        $name      = '';
        $duplicate = array();
        $userData  = array();
        $m         = array();

        if (!empty($data)) {

            $this->db->trans_start();
            $duplicate = $this->user_model->duplicate($data['username']);
            if (empty($duplicate)) {
                if (!empty($data['middlename'])) {
                    $name = $data['firstname'] . ' ' . $data['middlename'] . ' ' . $data['lastname'];
                } else {
                    $name = $data['firstname'] . ' ' . $data['lastname'];
                }
                $userData =
                    [
                        'name'       => $name,
                        'position'   => $data['position'],
                        'department' => $data['department'],
                        'subsidiary' => $data['subsidiary'],
                        'userType'   => $data['usertype'],
                        'username'   => $data['username'],
                        'password'   => MD5($data['password']),
                        'status'     => 'Active'
                    ];

                $this->db->insert('users', $userData);

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $error = array('action' => 'Saving User', 'error_msg' => $this->db->_error_message()); //Log error message to `error_log` table
                    $this->db->insert('error_log', $error);
                    $m = ['message' => 'Error: Failed saving data.', 'info' => 'Error Saving'];
                } else {
                    $m = ['message' => 'User Successfully Saved.', 'info' => 'Success'];
                }
            } else {
                $m = ['message' => 'Username already exists.', 'info' => 'Duplicate'];
            }
        } else {

            $m = ['message' => 'Failed Saving User: No Data.', 'info' => 'No Data'];
        }

        JSONResponse($m);
    }

    public function updateUser()
    {
        $data     = $this->input->post(NULL, FILTER_SANITIZE_STRING);
        $m        = array();
        $userData = array();

        if (!empty($data)) {

            $this->db->trans_start();

            $userData =
                [
                    'name'       => $data['name'],
                    'position'   => $data['position'],
                    'department' => $data['department'],
                    'subsidiary' => $data['subsidiary'],
                    'userType'   => $data['usertype']
                ];

            $this->db->where('user_id', $data['ID']);
            $this->db->update('users', $userData);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $error = array('action' => 'Updating User', 'error_msg' => $this->db->_error_message()); //Log error message to `error_log` table
                $this->db->insert('error_log', $error);
                $m = ['message' => 'Error: Failed Updating user.', 'info' => 'Error Saving'];
            } else {
                $m = ['message' => 'User Successfully Updated.', 'info' => 'Updated'];
            }
        } else {

            $m = ['message' => 'Failed Updating data: No Data', 'info' => 'No Data'];
        }

        JSONResponse($m);
    }

    public function deactivate()
    {
        $ID = $this->input->post('ID');
        $m = array();

        if (!empty($ID)) {
            $this->db->trans_start();

            $this->db->where('user_id', $ID);
            $this->db->update('users', ['status' => 'Deactivated']);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $error = array('action' => 'Deactivating User', 'error_msg' => $this->db->_error_message()); //Log error message to `error_log` table
                $this->db->insert('error_log', $error);
                $m = ['message' => 'Error: Failed Deactivating user.', 'info' => 'Error Deactivating'];
            } else {
                $m = ['message' => 'User Successfully Deactivated.', 'info' => 'Deactivated'];
            }
        } else {
            $m = ['message' => 'Failed deactivating user: No ID found.', 'info' => 'No ID'];
        }

        JSONResponse($m);
    }
}
