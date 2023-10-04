<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Profile
 *
 * @property M_user              user
 */
class Profile extends Site_layout {
    protected $_data_condition_default = array(
        "limit"    => 10,
        "offset"   => 0,
        "order_by" => NULL,
    );

    function __construct() {
        parent::__construct();
        // set title
        $this->data["title"] = "Thông tin cá nhân";
        // Load model
        $this->load->model('M_user', 'user');
    }

    public function index() {
        $user_data = $this->session->userdata('user_data');
        $data = array();
        $data["current_controller"] = $this->router->class;
        if (file_exists(APPPATH . "views/site/profile/header.php")) {
            $more_head = $this->load->view("site/profile/header", NULL, TRUE);
            $this->append_part("assets_header", $more_head);
        }
        if (file_exists(APPPATH . "views/site/profile/footer.php")) {
            $more_foot = $this->load->view("site/profile/footer", NULL, TRUE);
            $this->append_part("assets_footer", $more_foot);
        }
        $user_id = $this->session->userdata("id");
        $user_info = $this->user->get_by(["m.id" => $user_id]);
        if (!$user_info) {
            $this->load->view("site_errors/html/error_404");
            return FALSE;
        }
        $data["user_role"] = $user_data->role_alias;
        $data["user_info"] = $user_info;
        $data["sex_list"] = [
            "male" => "Nam",
            "female" => "Nữ",
        ];
        // URL for link
        $data["update_general_info_url"] = site_url("profile/update_user_info");
        $data["update_password_url"] = site_url("profile/change_user_password");
        $data["update_profile_url"] = site_url("profile/update_user_profile_image");
        $content = $this->load->view("site/profile/content", $data, TRUE);
        $this->show_page($content);
    }

    public function update_user_info() {
        if ($this->input->is_ajax_request() && $this->input->post()) {
            $user_id = $this->session->userdata("id");
            $data = $this->input->post();
            $dataReturn = array();
            $data = $this->_filter_data_user($data);
            if (!$this->user->get($user_id)) {
                $dataReturn["msg"] = "Người dùng không tồn tại";
                $dataReturn["state"] = 0;
            } else {
                    $upd_status = $this->user->update($user_id, $data);
                    if ($upd_status) {
                        $dataReturn["msg"] = "Cập nhật thông tin thành công";
                        $dataReturn["state"] = 1;
                    } else {
                        $dataReturn["msg"] = "Đã có lỗi xảy ra, vui lòng thử lại";
                        $dataReturn["state"] = 0;
                    }
            }
            echo json_encode($dataReturn);

            if ($dataReturn["state"] != 0) {
                // update user info on session
                $updated_user_info = $this->user->get($user_id);
                $this->session->set_userdata("user_data", $updated_user_info);
            }
            return TRUE;
        }
        return FALSE;
    }

    protected function _filter_data_user($data) {
        $tmp = array();
        isset($data["phone"]) && $tmp["phone"] = $data["phone"];
        isset($data["sex"]) && $tmp["sex"] = $data["sex"];
        $tmp["display_name"] = isset($data["display_name"]) ? $data["display_name"] : "";
        return $tmp;
    }

    public function change_user_password() {
        $user_id = $this->session->userdata("id");
        $user = $this->user->get($user_id);
        $old_pass = $this->input->post("old-pass");
        $new_pass = $this->input->post("new-pass");
        if (md5($old_pass) == $user->password) {
            $data = [
                'password' => md5($new_pass),
            ];
            if ($this->user->update($user_id, $data)) {
                $dataReturn["msg"] = "Cập nhật thông tin thành công";
                $dataReturn["state"] = 1;
            } else {
                $dataReturn["msg"] = "Đã có lỗi xảy ra, vui lòng thử lại";
                $dataReturn["state"] = 0;
            }
            echo json_encode($dataReturn);
        } else {
            $dataReturn["msg"] = "Mật khẩu cũ không đúng";
            $dataReturn["state"] = 0;
            echo json_encode($dataReturn);
        }
        return TRUE;
    }

    public function update_user_profile_image() {
        $user_id = $this->session->userdata("id");
        $upload_dir = 'upload/avatars/' . $user_id . '/';

        $config['upload_path'] = $upload_dir;
        $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|PNG|GIF|JPG';
        $config['encrypt_name'] = TRUE;
        $config['overwrite'] = TRUE;
        $config['max_size'] = 8192; // max 8MB

        $this->load->library('upload', $config);
        $this->upload->initialize($config); // TODO: check if this can be remove
        $dataReturn = [];
        if ($this->upload->do_upload()) {
            $data = array('upload_data' => $this->upload->data());
            $dataUpdate = [
                'avatar' => 'upload/avatars/' . $user_id . '/' . $this->upload->data('file_name'),
            ];
            $this->user->update($user_id, $dataUpdate);
            $user = $this->user->get($user_id);
            $this->session->set_userdata("user_data", $user);

            $dataReturn["updateAvarUrl"] = $dataUpdate['avatar'];
            $dataReturn["avatar"] = base_url($dataUpdate['avatar']);
            $dataReturn["msg"] = "Cập nhật thông tin thành công";
            $dataReturn["state"] = 1;
            $dataReturn["data"] = $data;
        } else {
            $dataReturn["msg"] = "Đã có lỗi xảy ra, vui lòng thử lại";
            $dataReturn["state"] = 0;
            $dataReturn["error"] = ['error' => $this->upload->display_errors()];
        }
        echo json_encode($dataReturn);
    }

}