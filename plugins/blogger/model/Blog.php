<?php
defined('COT_CODE') or die('Wrong URL.');
require_once $cfg['plugins_dir']."/blogger/model/BlModelAbstract.php";
/**
 * Model class for the coupons
 *
 * @package Blogger
 * @subpackage blog
 *
 * @property int $ub_id;
 * @property int $user_id
 * @property string $ub_cat
 * @property string $ub_theme
 * @property string $ub_scheme
 * @property bool $ub_comnotify
 * @property array $ub_config
 * @method static Blog getById(int $pk)
 * @method static Blog[] getList(int $limit = 0, int $offset = 0, string $order = '', string $way = 'ASC')
 * @method static Blog[] find(mixed $conditions, int $limit = 0, int $offset = 0, string $order = '', string $way = 'ASC')
 *
 * @todo получать блог по категории с учетом того, что категория может быть вложенной
 */
class Blog extends BlModelAbstract{

    /**
     * @var string
     */
    public static $_table_name = '';

    /**
     * @var string
     */
    public static $_primary_key = '';

    /**
     * Column definitions
     * @var array
     */
    public static $_columns = array();

    public $owner = array();

    /**
     * Static constructor
     */
    public static function __init(){
        global $db_user_blogs;

        self::$_table_name = $db_user_blogs;
        self::$_primary_key = 'ub_id';
        parent::__init();

    }

    /**
     * @param mixed $data Array or Object - свойства
     *   в свойства заполнять только те поля, что есть в таблице + user_name
     */
    public function __construct($data = false) {

        parent::__construct($data);


    }

    /**
     * Get Blog by Category code
     * @static
     * @param string $c
     * @return Blog|null
     */
    public static function getByCategory($c){
        global $structure;

        static $_stCache = array();

        if(empty($c)) return null;
        if(empty($structure['page'][$c])) return null;

        if (isset($_stCache[$c])){
            return $_stCache[$c];
        }

        $cats = cot_structure_parents('page', $c);
        $res = self::fetch(array(array("ub_cat", $cats)), 1);

        if($res) $_stCache[$c] = $res[0];

        return ($res) ? $res[0] : null;
    }

    /**
     * Get Blog by UserId
     * @static
     * @param int $id
     * @return Blog|null
     */
    public static function getByUserId($id = 0){

        static $_stCache = array();

        $id = (int)$id;
        if(empty($id)) return null;

        if (isset($_stCache[$id])){
            return $_stCache[$id];
        }

        $res = self::fetch(array(array("user_id", $id)), 1);

        if($res) $_stCache[$id] = $res[0];

        return ($res) ? $res[0] : null;
    }



    /**
     * Save data
     * @param Coupon|array|null $data
     * @return int id of saved record
     */
    public function save($data = null){
        global $sys, $usr;

        if(!$data) $data = $this->_data;

        if ($data instanceof Coupon) {
            $data = $data->toArray();
        }

        $data['ub_updated_on'] = date('Y-m-d H:i:s', $sys['now']);
        $data['ub_updated_by'] = $usr['id'];

        if(!$data['ub_id']) {
            // Добавить новый
            $data['ub_created_on'] = date('Y-m-d H:i:s', $sys['now']);
            $data['ub_created_by'] = $usr['id'];
        }
        $id = parent::save($data);
        if($id){
            if(!$data['ub_id']) {
                cot_log("Added new blog #".$id,'adm');
            }else{
                cot_log("Edited blog #".$id,'adm');
            }
        }
        return $id;
    }

    /**
     * Delete coupon
     * @return bool|void
     */
    public function delete(){
        $ret = parent::delete();

        cot_log("Deleted blog #".$this->_data['ub_id'], 'adm');
        return $ret;
    }

    /**
     * @param array $conditions
     * @param int $limit
     * @param int $offset
     * @param string $order
     * @param string $way
     * @return Blog[]
     */
    protected static function fetch($conditions = array(), $limit = 0, $offset = 0, $order = '', $way = 'DESC'){
        global $db, $db_users;
        /** @var Blog[] $blogs  */
        $blogs = parent::fetch($conditions, $limit, $offset, $order, $way);

        if (is_array($blogs)){
            $userIds = array();
            foreach($blogs as $blog){
                if (!empty($blog->user_id)) $userIds[] = $blog->user_id;
            }
            $userIds = array_unique($userIds);
            $userList = array();
            $sql = $db->query("SELECT * FROM $db_users WHERE user_id IN (".implode(',', $userIds).")");
            $userList = $sql->fetchAll(PDO::FETCH_GROUP);
            $userList = array_map('reset', $userList);
            if(count($userList) > 0){
                foreach($userList as $uid => $user){
                    $user['user_id'] = $uid;
                    foreach($blogs as $blog){
                        if (!empty($blog->user_id) && $blog->user_id == $uid){
                            $blog->owner = $user;
                        }
                    }
                }
            }
        }

        return $blogs;
    }

    // === Методы для работы с шаблонами ===
    /**
     * Returns all order tags for coTemplate
     *
     * @param Coupon|int $coupon Coupon object or ID
     * @param string $tagPrefix Prefix for tags
     * @param bool $cacheitem Cache tags
     * @return array|void
     *
     * @todo fixme
     */
    public static function generateTags($coupon, $tagPrefix = '', $cacheitem = true){
        global $cfg, $L;

        static $extp_first = null, $extp_main = null;
        static $coupon_cache = array();

        if (is_null($extp_first)){
//            $extp_first = cot_getextplugins('shop.coupon.tags.first');
//            $extp_main = cot_getextplugins('shop.coupon.tags.main');
        }

        /* === Hook === */
//        foreach ($extp_first as $pl){
//            include $pl;
//        }
        /* ===== */
        if ( is_object($coupon) && is_array($coupon_cache[$coupon->coupon_id]) ) {
            $temp_array = $coupon_cache[$coupon->coupon_id];
        }elseif (is_int($coupon) && is_array($coupon_cache[$coupon])){
            $temp_array = $coupon_cache[$coupon];
        }else{
            if (is_int($coupon) && $coupon > 0){
                $coupon = self::getById($coupon);
            }
            if ($coupon->coupon_id > 0){
                $coupon_link = cot_url('admin', array('m'=>'shop', 'n'=>'coupon', 'a'=>'edit', 'id'=>$coupon->coupon_id));
                $date_format = 'datetime_medium';
                $expiry = $L['shop']['never'];
                $eDate = strtotime($coupon->coupon_edate);
                if ($eDate > 100) $expiry = cot_date($date_format, $eDate);
                $temp_array = array(
                    'URL' => $coupon_link,
                    'ID' => $coupon->coupon_id,
                    'CODE' => $coupon->coupon_code,
                    'PER_O_TOTAL' => $L['shop']['coupon_'.$coupon->coupon_percent_or_total],
                    'PER_O_TOTAL_RAW' => $coupon->coupon_percent_or_total,
                    'TYPE' => $L['shop']['coupon_type_'.$coupon->coupon_type],
                    'TYPE_RAW' => $coupon->coupon_type,
                    'VALUE' => $coupon->coupon_value,
                    'MIN_ORDER_TOTAL' => $coupon->coupon_min_order_total,
                    'START_DATE' => cot_date($date_format, strtotime($coupon->coupon_vdate)),
                    'EXPIRY_DATE' => $expiry,
                    'PUBLISHED' => $coupon->coupon_published ? $L['Yes'] : $L['No'],
                    'CREATE_DATE' => cot_date($date_format, strtotime($coupon->coupon_created_on)),
                    'MODIFY_DATE' => cot_date($date_format, strtotime($coupon->coupon_updated_on)),
                    'DELETE_URL' => cot_confirm_url(cot_url('admin', 'm=shop&n=coupon&a=delete&id='.$coupon->coupon_id.'&'.cot_xg()), 'admin'),
                );

                // Extrafields
//                if (isset($cot_extrafields[$db_pages])){
//                    foreach ($cot_extrafields[$db_pages] as $row) {
//                        $tag = mb_strtoupper($row['field_name']);
//                        $temp_array[$tag.'_TITLE'] = isset($L['page_'.$row['field_name'].'_title']) ?  $L['page_'.$row['field_name'].'_title'] : $row['field_description'];
//                        $temp_array[$tag] = cot_build_extrafields_data('page', $row, $order["page_{$row['field_name']}"], $order['page_parser']);
//                    }
//                }

                /* === Hook === */
//                foreach ($extp_main as $pl)
//                {
//                    include $pl;
//                }
                /* ===== */
                $cacheitem && $coupon_cache[$coupon->coupon_id] = $temp_array;
            }else{
                // Заказ не существует
//                $temp_array = array(
//                    'TITLE' => (!empty($emptytitle)) ? $emptytitle : $L['Deleted'],
//                    'SHORTTITLE' => (!empty($emptytitle)) ? $emptytitle : $L['Deleted'],
//                );
            }
        }
        $return_array = array();
        foreach ($temp_array as $key => $val){
            $return_array[$tagPrefix . $key] = $val;
        }

        return $return_array;
    }
}

// Class initialization for some static variables
Blog::__init();