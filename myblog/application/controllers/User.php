<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
public function __construct(){
    parent::__construct();
    $this ->load-> model("User_model");
}


    public function login()
    {
        $this->load->view('login');
    }
    public function captcha(){
        $this->load->helper('captcha');
        $rand = rand(1000,9999);
//        $this -> session -> set_userdate(array("captcha" => $rand));
//        $this -> session -> set_userdate(array("captcha" => $rand));
        $this->session->set_userdata(array(
            "captcha" => $rand
        ));
//        $this->session->set_userdata(array(
//            "captcha" => $rand
//        ));

        $vals = array(
            'word'      => $rand,
            'img_path'  => './captcha/',
            'img_url'   => base_url().'captcha/',
            'font_path' => './path/to/fonts/texb.ttf',
            'img_width' => '150',
            'img_height'    => 30,
            'expiration'    => 7200,
            'word_length'   => 8,
            'font_size' => 16,
            'img_id'    => 'Imageid',
            'pool'      => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',

            // White background and border, black text and red grid
            'colors'    => array(
                'background' => array(255, 255, 255),
                'border' => array(255, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(255, 40, 40)
            )
        );

        $cap = create_captcha($vals);
        return $img = $cap['image'];
    }
    public function login_user()
    {
        $email = $this -> input -> get('email');
        $pwd = $this -> input -> get('pwd');
        $login = $this -> input -> get('save_login');
        $this->load->helper('cookie');

        $user = $this -> User_model -> get_email($email);
        if(count($user)>0){
            if($user -> password != $pwd){
                echo "password_error";
            } else{
//                if($login == '1'){
//                    set_cookie('email',$user['email']);
//                    set_cookie('password',$user["password"]);
//                }else{
//                    if(get_cookie('email')) {
//                        delete_cookie('email');
//                        delete_cookie('password');
//                    }
//
//                }
                $this -> session -> set_userdata(array("user" => $user));
                echo "success";
            }
        }else{
            echo "email_error";
        }




    }

    public function reg()
    {
       $img = $this -> captcha();
        $this->load->view('reg',array("img"=> $img));
    }
    public function change_code(){
        $img = $this -> captcha();
        echo $img;
    }
    public function check_email()
    {
        $email = $this -> input -> get('email');
        $user = $this -> User_model -> get_email($email);
        if($user){
            echo "email_exist";
            die();
        }else{
            echo "true";
        }
    }
    public function create()
    {
        $email = $this -> input -> get('email');
        $name = $this -> input -> get('name');
        $pwd = $this -> input -> get('pwd');
        $pwd2 = $this -> input -> get('pwd2');
        $gender =$this -> input -> get('gender');
        $province = $this -> input -> get('province');
        $city= $this -> input -> get('city');
        $code = $this -> input -> get('code');
        $user = $this -> User_model -> get_email($email);
        if($user){
            echo "email_exist";
            die();
        }
        $captcha = $this->session->userdata('captcha');
        if($code != $captcha){
            echo 'code_error';
            die();
        }
        if(strlen($pwd)<4){
            echo "pwd_number";

        }

        if($pwd!=$pwd2){
            echo "pwd-error";
            die();
        }

       $result = $this -> User_model -> save_user($email,$name,$pwd,$gender,$province,$city);

       if($result >0){
           $this -> session -> set_userdata(array("user" => $user));

           echo "success";

       }else{
           echo "fail";
       }

    }
    public  function logout(){
       $this -> session -> unset_userdata('user');
       redirect("Welcome/index");

    }



}
