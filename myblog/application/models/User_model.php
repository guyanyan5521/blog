<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/1/11
 * Time: 11:06
 */

class User_model extends CI_Model{

    public function save_user($email,$name,$pwd,$gender,$province,$city){
        $this -> db -> insert("t_user", array(
            "username" => $name,
            "password"=> $pwd,
            'email' => $email,
            'sex' => $gender,
            'province' => $province,
            'city' => $city

        ));
        return $this-> db ->affected_rows();
    }
    public function get_all(){
        return $this -> db -> get('user') -> result();
//        return $this ->db ->get_where('user',array('username'=>'qq'))->result();
    }
    public function del_user($id){
       $this -> db -> delete('user',array('id'=>$id));
        return $this-> db ->affected_rows();


    }
    public function get_email($email){
        return $this -> db -> get_where('t_user',array('email'=>$email)) -> row();
    }
    public function update($id){
       $username= $this -> input -> post("res");
        $this->db->where('id', $id);
        $this->db->update('user', array(
            "username" => $username
        ));
        return $this->db->affected_rows();


    }

}

?>
