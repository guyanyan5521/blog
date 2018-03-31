<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this ->load-> model("Article_model");
    }
	public function index()
	{
        $this->load->library('pagination');
        $total = $this ->Article_model->number_all();
        $config['base_url'] = base_url().'Welcome/index';
        $config['total_rows'] =$total ;
        $config['per_page'] = 2;

        $this->pagination->initialize($config);

        $links = $this->pagination->create_links();
        $results = $this->Article_model->get_article_list( $this->uri->segment(3),$config['per_page']);
        $article = $this -> Article_model -> get_article_type();
        $this->load->view('index',array('article'=>$results,'type'=>$article,'links'=>$links));
	}
    public function index_login()
    {
        $this->load->library('pagination');
        $user= $this -> session ->userdata('user');
        $user_id = $user -> user_id;
        $total = $this ->Article_model->login_number_all($user_id);
        $config['base_url'] = base_url().'Welcome/index_login';
        $config['total_rows'] =$total ;
        $config['per_page'] = 2;

        $this->pagination->initialize($config);

        $links = $this->pagination->create_links();
        $results = $this->Article_model->get_article_list( $this->uri->segment(3),$config['per_page'],$user_id);

        $article = $this -> Article_model -> get_article_type($user_id);
        $this->load->view('index_logined',array('article'=>$results,'type'=>$article,'links'=>$links));
    }
        public function viewPost_comment(){

        $this ->load ->view('viewPost_comment');
        }
        public function admin_index(){

        $this ->load ->view('adminIndex');
    }
    public function new_blog(){
        $user= $this -> session ->userdata('user');
        $user_id = $user -> user_id;
        $article = $this -> Article_model -> new_blog_article_type($user_id);
        $this ->load ->view('newBlog',array('type'=>$article));
    }
    public  function  blog_from(){
        date_default_timezone_set('Asia/Harbin');
        $user= $this -> session ->userdata('user');
        $user_id = $user -> user_id;
        $title=$this -> input ->post('title');
        $catalog =$this -> input ->post('catalog');
        $content=$this -> input ->post('content');
        $rows =$this -> Article_model -> article_insert(array(
            'user_id'=>$user_id,
            'title'=>$title,
            'content'=>$content,
            'type_id'=>$catalog,
            'post_date'=>date('Y-m-d h:m:s')
        ));
        if($rows >0){
           redirect('Welcome/index_login');
        }

    }
    public function blog_catalogs(){
        $user= $this -> session ->userdata('user');
        $user_id = $user -> user_id;
        $article = $this -> Article_model -> article_type($user_id);
        $this->load->view("blogCatalogs",array('type'=>$article));
    }
    public function edit_catalog(){
        $this ->load->view("Welcome/editCatalog");
    }
    public function add_catalog()
    {
        $user= $this -> session ->userdata('user');
        $user_id = $user -> user_id;
        $catalog_name = $this->input->get('catalog_name');
        if($catalog_name == ""){
            echo "null";
            die();
        }
        $result = $this->Article_model->new_blog_article_type_name($catalog_name,$user_id);
        if ($result) {
            echo "type_repeat";
            die();
        }
        $rows = $this -> Article_model -> article_type_insert(array(
            'type_name'=>$catalog_name,
            'user_id'=>$user_id
        ));
        if($rows){
            echo "success";
        }

    }
    public function revise_catalog(){
        $user= $this -> session ->userdata('user');
        $user_id = $user -> user_id;
        $catalog_name = $this->input->get('catalog_name');
        $type_id = $this->input->get('type_id');
        if($catalog_name == ""){
            echo "null";
            die();
        }
        $result = $this->Article_model->new_blog_article_type_name($catalog_name,$user_id);
        if ($result) {
            echo "type_repeat";
            die();
        }
        $rows = $this -> Article_model -> update_article_type_name($type_id,$user_id,array(
            'type_name'=>$catalog_name

        ));
        if($rows){
            echo "success";

        }

    }
         public  function  del_catalog(){
             $user= $this -> session ->userdata('user');
             $user_id = $user -> user_id;
             $type_id = $this->input->get('type_id');
             $result = $this->Article_model->ensure_article_type($type_id,$user_id);
             if (!$result) {
                 echo "error";
                 die();
             }
             $rows = $this -> Article_model -> del_article_type($type_id);
             if($rows){
                 echo "success";

             }

         }
         public function blogs(){
             $this->load->library('pagination');
             $user= $this -> session ->userdata('user');
             $user_id = $user -> user_id;
             $total = $this ->Article_model->login_number_all($user_id);
             $config['base_url'] = base_url().'Welcome/blogs';
             $config['total_rows'] =$total ;
             $config['per_page'] = 3;

             $this->pagination->initialize($config);

             $links = $this->pagination->create_links();
             $results = $this->Article_model->get_login_article_list( $this->uri->segment(3),$config['per_page'],$user_id);

             $article = $this -> Article_model -> get_login_article_type($user_id);
             $this->load->view('blogs',array('article'=>$results,'type'=>$article,'links'=>$links,'total'=>$total,"count"=>$config['per_page']));


         }
    public  function  del_article(){
//        $user= $this -> session ->userdata('user');
//        $user_id = $user -> user_id;
        $article_id = $this->input->get('ids');
//        $result = $this->Article_model->ensure_article_type($article_id,$user_id);
//        if (!$result) {
//            echo "error";
//            die();
//        }
        $rows = $this -> Article_model -> del_article($article_id);
        if($rows){
            echo "success";

        }

    }
    public  function article_content($id){
        $row = $this->Article_model->get_article_by_id($id);
        $date_str = $this->time_tran($row->post_date);

        $row->post_date = $date_str;

        $comments = $this->Article_model->get_comment_by_article_id($id);

        //查询上一篇和下一篇文章

        //全部的文章
        $result = $this->Article_model->get_article_list_all();
        $next_article = null;
        $prev_article = null;
        foreach($result as $index=>$article){
            if($article->article_id == $id){
                if($index >0){
                    $prev_article = $result[$index-1];
                }
                if($index < count($result) - 1){
                    $next_article = $result[$index+1];
                }
            }
        }
        $this->load->view('viewPost_comment',array(
            'article'=>$row,
            'comments'=>$comments,
            'next'=>$next_article,
            'prev'=>$prev_article
        ));
    }


    function time_tran($the_time)
    {
        $now_time = date("Y-m-d H:i:s", time() + 8 * 60 * 60);
        $now_time = strtotime($now_time);
        $show_time = strtotime($the_time);
        $dur = $now_time - $show_time;
        if ($dur < 0) {
            return $the_time;
        } else {
            if ($dur < 60) {
                return $dur . '秒前';
            } else {
                if ($dur < 3600) {
                    return floor($dur / 60) . '分钟前';
                } else {
                    if ($dur < 86400) {
                        return floor($dur / 3600) . '小时前';
                    } else {
                        if ($dur < 259200) {//3天内
                            return floor($dur / 86400) . '天前';
                        } else {
                            return $the_time;
                        }
                    }
                }
            }
        }
    }
    public function add_comment(){
        $content = $this->input->get('content');
        $article_id = $this->input->get('articleId');
        $user = $this->session->userdata('user');

        $rows = $this->Article_model->add_comment(array(
            'content'=>$content,
            'user_id'=>$user->user_id,
            'post_date'=>date("Y-m-d h:m:s"),
            'article_id'=>$article_id
        ));
        if($rows >0){
            echo 'success';
        }
    }


}
