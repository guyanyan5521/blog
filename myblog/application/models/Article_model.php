<?php

/**
 * Created by PhpStorm.
 * User: apple
 * Date: 18/1/13
 * Time: 下午3:20
 */
class Article_model extends CI_Model
{
    public function get_article_list($offset,$page_size){

//        $sql = "select * from t_article a ,t_article_type t where a.type_id = t.type_id";

        $this->db->select('*');
        $this->db->from('t_article a');
        $this->db->join('t_article_type t', 'a.type_id = t.type_id','left');
        $this->db->limit($page_size,$offset);
        $query = $this->db->get();

//        $query = $this->db->query($sql);
        return $query->result();
    }
    public function get_article_list_all(){

//        $sql = "select * from t_article a ,t_article_type t where a.type_id = t.type_id";

        $this->db->select('*');
        $this->db->from('t_article a');
        $this->db->join('t_article_type t', 'a.type_id = t.type_id','left');
        $query = $this->db->get();

//        $query = $this->db->query($sql);
        return $query->result();
    }
    public function get_login_article_list($offset,$page_size,$user_id){

//        $sql = "select * from t_article a ,t_article_type t where a.type_id = t.type_id";

        $this->db->select('*');
        $this->db->from('t_article a');
        $this->db->join('t_article_type t', 'a.type_id = t.type_id','left');
        $this->db->where('a.user_id',$user_id);
        $this->db->order_by('a.article_id','desc');
        $this->db->limit($page_size, $offset);
        $query = $this->db->get();

//        $query = $this->db->query($sql);
        return $query->result();
    }
    public function get_article_type(){
        $sql ="select  distinct(t.type_name),num from
               (select count(*) num, t.type_name
               from t_article a , t_article_type t
							where t.type_id = a.type_id
               GROUP BY t.type_name) nt,
              t_article_type t 
               where t.type_name = nt.type_name
";
        $query = $this->db->query($sql);
        return $query->result();

    }
    public function get_login_article_type($user_id){
    $sql ="select * from
                 (select count(*) num,a.type_id
                 from t_article a where a.user_id = $user_id
                GROUP BY a.type_id) nt,
                t_article_type t 
                where t.type_id = nt.type_id";


    $query = $this->db->query($sql);
    return $query->result();

}
    public function article_type($user_id){
        $sql ="select * ,(select count(*)
        from t_article a 
        where a.type_id = t.type_id ) num
        from t_article_type t
        where t.user_id =$user_id";


        $query = $this->db->query($sql);
        return $query->result();

    }


    public function number_all(){
        return $this->db->count_all('t_article');
    }
    public function login_number_all($user_id){
        $query = $this->db->get_where('t_article',array('user_id'=>$user_id));

        return count($query->result());
    }
    public function new_blog_article_type($user_id){
        $query = $this->db->get_where('t_article_type',array('user_id'=>$user_id));

        return $query->result();
    }
    public function new_blog_article_type_name($catalog_name,$user_id){
        $query = $this->db->get_where('t_article_type',array('user_id'=>$user_id,'type_name'=>$catalog_name));

        return $query->result();
    }
    public function update_article_type_name($type_id,$user_id,$type){
        $this->db->where('type_id', $type_id);
        $this->db->where('user_id', $user_id);
        $this->db->update('t_article_type',$type);
        return $this-> db ->affected_rows();
    }
    public function article_insert($article){
        $this -> db -> insert("t_article", $article);
        return $this-> db ->affected_rows();

    }
     public  function ensure_article_type($type_id,$user_id){
         $query = $this->db->get_where('t_article_type',array('user_id'=>$user_id,'type_id'=>$type_id));

         return $query->result();
     }
    public function get_article_by_id($id){
        $this->db->select('*');
        $this->db->from('t_article a');
        $this->db->join('t_user t', 'a.user_id = t.user_id','left');
        $this->db->where('a.article_id',$id);
        $query = $this->db->get();
        return $query->row();
    }
    public function add_comment($comment){
        $this->db->insert('t_comment',$comment);
        return $this->db->affected_rows();
    }

    public function article_type_insert($catalog){
        $this -> db -> insert("t_article_type", $catalog);
        return $this-> db ->affected_rows();

    }
    public function  del_article_type($type_id){
     $this->db->delete('t_article_type',array('type_id'=>$type_id));
        return $this-> db ->affected_rows();
    }
    public function  del_article($article_id){
      $this->db->where_in("article_id ", $article_id);
      $this->db->delete('t_article');
        return $this-> db ->affected_rows();
    }
    public function get_comment_by_article_id($id){
//        $query = $this->db->get_where('t_comment',array('article_id'=>$id));

        $this->db->select('*');
        $this->db->from('t_comment c');
        $this->db->join('t_user u','c.user_id=u.user_id');
        $this->db->where('c.article_id',$id);
        return $this->db->get()->result();
    }

}