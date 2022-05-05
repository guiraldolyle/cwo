<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BaseController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('app_model');
        $this->load->library('upload');
        $this->load->library('session');
        $this->load->library('form_validation');
        date_default_timezone_set('Asia/Manila');


        //Disable Cache
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        ('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }

    function sanitize($string)
    {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = trim($string);
        return $string;
    }

    public function testing()
    {
        $this->load->view('template/header');
        $this->load->view('page/testing');
        $this->load->view('template/footer');
    }

    public function checkCredentials()
    {
        $postContent = file_get_contents("php://input");
        $request = json_decode($postContent);

        $username = $request->username;
        $password = md5($request->password);

        $users = $this->app_model->getUsers($username, $password);

        if (!empty($users)) {
            $this->session->set_userdata([
                'user_id'       => $users->user_id,
                'username'      => $users->username,
                'position'      => $users->position,
                'name'          => $users->name,
                'userType'      => $users->userType,
                'cwo_logged_in' => TRUE
            ]);

            $userdata = $this->session->userdata('userType');

            // if ($userdata == 'Accounting') {

            //     $message = ['info' => 'Denied', 'message' => 'Access Denied.'];
            // } else {

            $message = ['info' => 'Granted', 'message' => 'Access Granted.'];
            // }
        } else {
            $message = ['info' => 'Error', 'message' => ' Wrong username or password.'];
        }

        JSONResponse($message);
    }

    public function endSession()
    {
        $this->session->sess_destroy();
        $this->login();
    }

    public function authorize()
    {
        $data = $this->input->post(NULL, FILTER_SANITIZE_STRING);
        $m    = array();
        $users = $this->app_model->getAuthenticate($data['username'], MD5($data['password']));

        if (isset($data)) {
            if (isset($users)) {
                if ($users->userType == 'Admin' || $users->userType == 'Supervisor' || $users->userType == 'Section Head') {

                    $this->session->set_userdata(['authorize_id' => $users->user_id]);
                    $m = ['message' => 'Access Granted', 'info' => 'Auth'];
                } else {
                    $m = ['message' => 'Access Denied.', 'info' => 'Denied'];
                }
            } else {
                $m = ['message' => 'Incorrect Username or Password.', 'info' => 'Error'];
            }
        } else {
            $m = ['message' => 'User does not exist.', 'info' => 'Not Found'];
        }

        JSONResponse($m);
    }

    public function login()
    {
        if (!file_exists(APPPATH . 'views/login/login.php')) {
            show_404();
        }
        $this->load->view('login/login');
    }

    public function home()
    {
        if ($this->session->userdata('cwo_logged_in')) {
            if (!file_exists(APPPATH . 'views/page/home.php')) {
                show_404();
            }

            $data['title'] = 'Home';

            $this->load->view('template/header', $data);
            $this->load->view('page/home');
            $this->load->view('template/footer');
        } else {
            die('Attention: You are not allowed to access this page. Please Log in <a href="' . base_url() . '"> here.</a>');
        }
    }

    public function masterfile($page)
    {
        if ($this->session->userdata('cwo_logged_in')) {
            if (!file_exists(APPPATH . 'views/page/masterfiles/' . $page . '.php')) {
                show_404();
            }

            $data['title'] = $page;

            $this->load->view('template/header', $data);
            $this->load->view('page/masterfiles/' . $page);
            $this->load->view('template/footer');
        } else {
            die('Attention: You are not allowed to access this page. Please Log in <a href="' . base_url() . '"> here.</a>');
        }
    }

    public function transactions($page)
    {
        if ($this->session->userdata('cwo_logged_in')) {
            if (!file_exists(APPPATH . 'views/page/transactions/' . $page . '.php')) {
                show_404();
            }

            $data['title'] = $page;

            $this->load->view('template/header', $data);
            $this->load->view('page/transactions/' . $page);
            $this->load->view('template/footer');
        } else {
            die('Attention: You are not allowed to access this page. Please Log in <a href="' . base_url() . '"> here.</a>');
        }
    }

    public function reports($page)
    {
        if ($this->session->userdata('cwo_logged_in')) {
            if (!file_exists(APPPATH . 'views/page/reports/' . $page . '.php')) {
                show_404();
            }

            $data['title'] = $page;

            $this->load->view('template/header', $data);
            $this->load->view('page/reports/' . $page);
            $this->load->view('template/footer');
        } else {
            die('Attention: You are not allowed to access this page. Please Log in <a href="' . base_url() . '"> here.</a>');
        }
    }
}
