<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//базовые методы
///////////////////////////////////

class Back_basic_model extends CI_Model{
 function __construct(){
  parent::__construct();
 }

 function _prefix(){//получение префикса таблиц базы данных из конфигурационного файла
  return $this->config->item('db_tabl_prefix');
 }

 function add(/* значения полей */$post_arr,/* добавить в таблицу */$tabl){
  return $this->db->insert($tabl,$post_arr)?TRUE:FALSE;
 }

 function edit(/* изменения по id */$id,/* значения полей */$post_arr,/* изменения в таблице */$tabl){
  return $this->db->where('id',$id)->update($tabl,$post_arr)?TRUE:FALSE;
 }

 function del(/* в таблице */$tab,/* id страницы */$id){//удаление из таблицы $tab по $id
  return $this->db->where('id',$id)->delete($tab)?TRUE:FALSE;
 }

 function toggle_public(/* id страницы */$id,/* в таблице */$tab,/* on/off */$pub){
  if($pub==='off'){
   $this->db->where('id',$id)->update($tab,['public'=>'on']);
   return 'on';
  }elseif($pub==='on'){
   $this->db->where('id',$id)->update($tab,['public'=>'off']);
   return 'off';
  }
 }

 function links_url_replace(/*найти строку url*/$search,/*заменить на строку url*/$replace){//изменение связанных ссылок
  $tables=['index_page','pages','sections','gallerys'];
  foreach($tables as $table){//проход по таблицам с полями id,links
   $q=$this->db->select('id,links')->like('links','"'.$search.'"')->get($this->_prefix().$table)->result_array();//вернуть записи с искомой
   if(empty($q)){continue;}//в таблице нет записей
   foreach($q as $k=>$v){$q[$k]['links']=str_replace('"'.$search.'"','"'.$replace.'"',$v['links']);}//перезаписать искомые url в массиве
   $this->db->update_batch($this->_prefix().$table,$q,'id');//изменить в базе
  }
 }

 function links_url_del(/*найти опцию с строкой url*/$search){//удаление связанных ссылок
  $tables=['index_page','pages','sections','gallerys'];
  foreach($tables as $table){//проход по таблицам с полями id,links
   $q=$this->db->select('id,links')->like('links','"'.$search.'"')->get($this->_prefix().$table)->result_array();//вернуть записи с искомой
   if(empty($q)){continue;}//в таблице нет записей
   foreach($q as $k=>$v){//проход по записям
    $links=json_decode($v['links'],true);//json опций в массив
    foreach($links as $id=>$opt){if(is_array($opt)&&in_array($search,$opt)){unset($links[$id]);}}//проход по массиву опций, удалить искомое
    $q[$k]['links']=count($links)<=1?'':json_encode($links,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);//массив опций в json, для отправки
   }
   $this->db->update_batch($this->_prefix().$table,$q,'id');//изменить в базе записи с искомым
  }
 }

///////////////////////////////////
//получение, проверка, фильтр данных
///////////////////////////////////


 function get_tabl($tabl){
  $q=$this->db->get($tabl)->result_array();
  return empty($q)?FALSE:$q;
 }

 function get_where_id($tabl,$id){
  $q=$this->db->where('id',$id)->get($tabl)->result_array();
  if(empty($q)){return FALSE;}
  foreach($q as $data){foreach($data as $k=>$v){$data[$k]=$v;}}
  return $data;
 }

 function get_val(
         /* таблица */$tab,
         /* поле */$field,
         /* с значением (находим запись */$field_val,
         /* получить значение (из найденной записи) */$res_field
         ){
  $q=$this->db->get_where($tab,[$field=>$field_val])->result_array();
  if(empty($q)){return FALSE;}
  foreach ($q as $v){return $v[$res_field];}
 }

 function check_title($title,$id,$tab){//проверка на уникальность title в таблице БД
  $where=$id?['title'=>$title,'id !='=>$id]:['title'=>$title];
  $q=$this->db->where($where)->get($this->_prefix().$tab)->result_array();
  return empty($q)?FALSE:TRUE;
 }

 function get_result_list($table,$get_arr=[]){//получаю выборку, сортирую, фильтрую, поиск
 //$table=таблица для запроса "pages", "sections"...
 //$get_arr=get-массив формы фильтра (отсутствующие значения должны быть установлены по умолчанию)
 //$context_search=массив имен полей в которых будет поиск значения из $get_arr['search']
  if(empty($get_arr)){return [];}
  //комментарии только опубликованные
  $table==='comments'?$this->db->where('public','on'):TRUE;
  //если сортировка по id, сортирую результат - последняя запись сверху
  ($get_arr['order']=='id')?$this->db->order_by('id','DESC'):$this->db->order_by($get_arr['order'],'ASC');
  //поиск $get_arr['search'] в поле $get_arr['context_search']
  if($get_arr['search']!==''){
   $like=$get_arr['context_search']==='content'?['layout_t'=>$get_arr['search'],'layout_b'=>$get_arr['search'],'layout_l'=>$get_arr['search'],'layout_r'=>$get_arr['search']]:[$get_arr['context_search']=>$get_arr['search']];
   $table==='comments'?$this->db->like($like):$this->db->or_like($like);
  }
  $q['count_result']=$this->db->count_all_results($this->_prefix().$table,FALSE);
  $this->db->limit($get_arr['pag_per_page'],$get_arr['per_page']);
  $q['result']=$this->db->get()->result_array();
  return $q;
 }

///////////////////////////////////
//конфигурация и пользователи админки
///////////////////////////////////

 function get_back_users($email=FALSE){
  if($email){$this->db->where('email',$email);}
  $q=$this->db->get($this->_prefix().'back_users')->result_array();
  return empty($q)?FALSE:$q;
 }

 function edit_back_user($id,$post_arr=[]){
   return $this->db->where('id',$id)->update($this->_prefix().'back_users',$post_arr)?TRUE:FALSE;
 }

 function get_config(){//таблицу config в масив $data['name']='value'
  foreach($this->db->get($this->_prefix().'config')->result_array() as $v){
   $json=@json_decode($v['value'],TRUE);
   $data[$v['name']]=$json===NULL?$v['value']:$json;//если значение - json - преобразовать в массив
  }
  $data['prefix']=$this->_prefix();//префикс таблиц БД
  return $data;
 }

}
