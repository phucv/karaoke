<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Login
 *
 */
class Login extends Site_login_layout {

    /**
     * check user are logged in before jump into Master admin
     *
     * @throws Exception
     */
    public function index() {
        // Check login
        if ($this->session->userdata("id")) {
            $redirect_login = $this->session->userdata("redirect_login");
            if ($redirect_login) {
                redirect($redirect_login);
            } else {
                redirect('work');
            }
        }

        $data = array();
        if (file_exists(APPPATH . "views/site/login/header.php")) {
            $more_head = $this->load->view("site/login/header", NULL, TRUE);
            $this->append_part("assets_header", $more_head);
        }
        $data["login_url"] = site_url("login/check");
        //check cookie before check login
        if (!empty($_COOKIE['name']) && !empty($_COOKIE['pass'])) {
            $this->check();
        }
        $data["data_content"] = $this->load->view("site/login/content", $data, TRUE);
        $content = $this->load->view("site/login/content", $data, TRUE);
        $this->show_page($content);
    }

    public function check() {
        $redirect_login = $this->session->userdata("redirect_login");
        // if don't save cookie
        if ($this->input->is_ajax_request() && $this->input->post()) {
            $dataReturn = array();
            $dataReturn["callback"] = "login_response";
            $username = $this->input->post("username");
            $pass = $this->input->post("password");
            $remember = $this->input->post("remember");
            $password = md5($pass);
            $where = [
                'username' => $username,
                'password' => $password,
            ];
            $login = $this->user->get_by($where);
            if ($login) {
                if ($login->public == "0") {
                    $dataReturn["state"] = 0;
                    $dataReturn["msg"] = "Tài khoản bạn bị khoá.";
                } else {
                    $user_role = $login->role_data;
                    // save cookie data user
                    if ($remember) {
                        $this->load->helper('cookie');
                        set_cookie("name", $username, time() / 1000 + 365 * 24 * 60 * 60);
                        set_cookie("pass", $password, time() / 1000 + 365 * 24 * 60 * 60);
                    }
                    $this->set_more_session($login, $user_role);
                    $dataReturn["state"] = 1;
                    $dataReturn["msg"] = "Đăng nhập thành công";
                    // check student enroll
                    if ($redirect_login) {
                        $dataReturn["redirect"] = $redirect_login;
                    } else {
                        $dataReturn["redirect"] = site_url('work');
                    }
                }
            } else {
                $dataReturn["state"] = 0;
                $dataReturn["msg"] = "Tên đăng nhập hoặc mật khẩu không chính xác";
            }
            $dataReturn["userdata"] = $this->session->userdata("user_data");
            echo json_encode($dataReturn);
        } elseif (!empty($_COOKIE['name'])) {    // if isset user cookie, login by cookie data
            $name_ck = $_COOKIE['name'];
            $pass_ck = $_COOKIE['pass'];
            $where_ck = array(
                'username' => $name_ck,
                'password' => $pass_ck,
            );
            $login_ck = $this->user->get_by($where_ck);
            if ($login_ck) {
                $user_role = $user_role_view = $login_ck->role_data;
                $this->set_more_session($login_ck, $user_role);
                if ($redirect_login) {
                    redirect($redirect_login);
                } else {
                    redirect(site_url('work'));
                }
            }
        } else {
            redirect();
        }
    }

    private function set_more_session($user_data, $user_role) {
        if (isset($user_data->password)) unset($user_data->password);
        $this->session->set_userdata("user_data", $user_data);
        $this->session->set_userdata("role_data", $user_role);
        $this->session->set_userdata("id", $user_data->id);
        // init session id into database
        $current_session_id = session_id();
        $this->user->update($user_data->id, ['session_id' => $current_session_id]);
    }

    public function logout() {
        session_destroy();
        $this->session->set_flashdata('flash_message', "Đăng xuất thành công");
        // reset cookie when logout
        if (!empty($_COOKIE['name'])) {
            $this->load->helper('cookie');
            unset($_COOKIE['name']);
            unset($_COOKIE['pass']);
            set_cookie('name', '', time() / 1000 - 3600);
            set_cookie('pass', '', time() / 1000 - 3600);
        }
        redirect(site_url());
    }
}
