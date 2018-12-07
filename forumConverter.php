<?php
$db_ip = '127.0.0.1';
$db_port = 3306;
$db_from = 'jmlasite';
$db_to = 'wordpress';
$db_user = 'root';
$db_pass = 'dfxy74UV';

class Post{
    public $ID;
    public $post_author;
    public $post_date;
    public $post_date_gmt;
    public $post_content;
    public $post_title;
    public $post_excerpt;
    public $post_status;
    public $comment_status;
    public $ping_status;
    public $post_password;
    public $post_name;
    public $to_ping;
    public $pinged;
    public $post_modified;
    public $post_modified_gmt;
    public $post_content_filtered;
    public $post_parent;
    public $guid;
    public $menu_order;
    public $post_type;
    public $post_mime_type;
    public $comment_count;
}

class Forum extends Post{
    public $_edit_last;
    public $slide_template;
    public $_wpb_vc_js_status;
    public $_cma_show_related_questions;
    public $_bbp_last_active_time;
    public $_bbp_forum_subforum_count;
    public $_bbp_reply_count;
    public $_bbp_total_reply_count;
    public $_bbp_topic_count;
    public $_bbp_total_topic_count;
    public $_bbp_topic_count_hidden;
    public $_edit_lock;
    public $_bbp_last_topic_id;
    public $_bbp_last_reply_id;
    public $_bbp_last_active_id;

    function _construct($row){
        $this->ID = $row['id']; // OLD ID, Do not get confused.
        $this->post_author = $row['created_by'];
        $this->post_date = $row['created'];
        $this->post_date_gmt = $row['created'];
        $this->post_content = $row['description'];
        $this->post_title = $row['title'];
        $this->post_excerpt = '';
        $this->post_status = $row['published']?'publish':'trash';
        $this->comment_status = 'closed';
        $this->ping_status = 'closed';
        $this->post_password = '';
        $this->post_name = $row['alias'];
        $this->to_ping = '';
        $this->pinged = '';
        $this->post_modified = (new \DateTime())->format('Y-m-d H:i:s');
        $this->post_modified_gmt = (new \DateTime())->format('Y-m-d H:i:s');
        $this->post_content_filtered = '';
        $this->post_parent = 0;
        $this->guid = ''; // re-visit after creating a new forum.  Pretty sure this is a URL to the forum.
        $this->menu_order = 0;
        $this->post_type = 'forum';
        $this->post_mime_type = 'text/plain';
        $this->comment_count = 0;
        // postmeta defaults
        $this->_edit_last = $row['created'];
        $this->slide_template = 'default';
        $this->_wpb_vc_js_status = 'false';
        $this->_cma_show_related_questions = '';
        $this->_bbp_last_active_time = $row['created'];
        $this->_bbp_forum_subforum_count = 0;
        $this->_bbp_reply_count = 0;
        $this->_bbp_total_reply_count = 0;
        $this->_bbp_topic_count = 0;
        $this->_bbp_total_topic_count = 0;
        $this->_bbp_topic_count_hidden = 0;
        $this->_edit_lock = time().":1";
        $this->_bbp_last_topic_id = 0;
        $this->_bbp_last_reply_id = 0;
        $this->_bbp_last_active_id = $row['created_by'];
    }

    public function addReplyCount(){
        ++$_bbp_reply_count;
    }
    public function addTopicCount(){
        ++$_bbp_topic_count;
        ++$_bbp_total_topic_count;
    }
    public function updateLastActive($date, $userid, $topicid, $replyid){
        if ($date > $this->_bbp_last_active_time){
            $this->_bbp_last_active_time = $date;
            $this->_bbp_last_active_id= $userid;
            $this->_bbp_last_topic_id= $topicid;
            $this->_bbp_last_reply_id= $replyid;
        }
    }

    public function writePostMeta(){
        // see if the metafield exists
        //  Yes: overwrite
        //  No: insert new.
    }
}

class Topic extends Post{
    public $forumRef;
    public $_bbp_forum_id;
    public $_bbp_topic_id;
    public $_bbp_author_ip;
    public $_bbp_last_reply_id;
    public $_bbp_last_active_id;
    public $_bbp_last_active_time;
    public $_bbp_reply_count;
    public $_bbp_reply_count_hidden;
    public $_bbp_voice_count; 

    function _construct($row){
        // Construct from thread, hopefully do not need posts
        $this->ID = $row['id']; // OLD ID, Do not get confused.
        $this->post_author = $row['user_id'];
        $this->post_date = $row['created'];
        $this->post_date_gmt = $row['created'];
        $this->post_content = $row['content'];
        $this->post_title = $row['title'];
        $this->post_excerpt = '';
        $this->post_status = $row['published']?'publish':'trash';
        $this->comment_status = 'closed';
        $this->ping_status = 'closed';
        $this->post_password = '';
        $this->post_name = $row['alias'];
        $this->to_ping = '';
        $this->pinged = '';
        $this->post_modified = (new \DateTime())->format('Y-m-d H:i:s');
        $this->post_modified_gmt = (new \DateTime())->format('Y-m-d H:i:s');
        $this->post_content_filtered = '';
        $this->post_parent = $row['category_id'];
        $this->guid = ''; // re-visit after creating a new forum.  Pretty sure this is a URL to the forum.
        $this->menu_order = 0;
        $this->post_type = 'forum';
        $this->post_mime_type = 'text/plain';
        $this->comment_count = 0;
        // postmeta defaults
        $this->_bbp_forum_id = $row['category_id'];
        $this->_bbp_topic_id = $row['post_id'];
        $this->_bbp_author_ip = '192.168.1.1'; // because fuxck your analytics, that's why.
        $this->_bbp_last_reply_id = $row['post_id'];
        $this->_bbp_last_active_id = $row['last_user_id'];
        $this->_bbp_last_active_time = $row['replied'];
        $this->_bbp_reply_count = $row['num_replied'];
        $this->_bbp_reply_count_hidden = 0;
        $this->_bbp_voice_count = 0; 
        // loop through the forums, set forumRef to the matching ID... maybe
    }
}

class Reply extends Post{
    public $_bbp_forum_id;
    public $_bbp_topic_id;
    public $_bbp_author_ip;

    function _construct($row){
        $this->ID = $row['id']; // OLD ID, Do not get confused.
        $this->post_author = $row['user_id'];
        $this->post_date = $row['created'];
        $this->post_date_gmt = $row['created'];
        $this->post_content = $row['content'];
        $this->post_title = $row['title'];
        $this->post_excerpt = '';
        $this->post_status = $row['published']?'publish':'trash';
        $this->comment_status = 'closed';
        $this->ping_status = 'closed';
        $this->post_password = '';
        $this->post_name = $row['alias'];
        $this->to_ping = '';
        $this->pinged = '';
        $this->post_modified = (new \DateTime())->format('Y-m-d H:i:s');
        $this->post_modified_gmt = (new \DateTime())->format('Y-m-d H:i:s');
        $this->post_content_filtered = '';
        $this->post_parent = $row['category_id'];
        $this->guid = ''; // re-visit after creating a new forum.  Pretty sure this is a URL to the forum.
        $this->menu_order = 0;
        $this->post_type = 'forum';
        $this->post_mime_type = 'text/plain';
        $this->comment_count = 0;
        // postmeta defaults
        $this->_bbp_forum_id = $row['category_id'];
        $this->_bbp_topic_id = $row['parent_id'];
        $this->_bbp_author_ip = $row['ip'];
    }
}

echo "EasyDiscuss to bbPress Converter<br/>\r\n";

echo "Loading old forums<br/>\r\n";
// Connect to the FROM database (Joomla)
$conn_from = new mysqli($db_ip, $db_user, $db_pass, $db_from);
if($conn_from->connect_error) {
    die("Connection Failed: ".$conn_from->connect_error);
}
// Connect to the TO database (Wordpress)
$conn_to = new mysqli($db_ip, $db_user, $db_pass, $db_to);
if($conn_to->connect_error) {
    die("Connection Failed: ".$conn_to->connect_error);
}

// Load forums, create forum objects
$from_forums = "SELECT * FROM fkumx_discuss_category";
$from_forums_result = $conn_from->query($from_forums);
$forums = [];
if ($from_forums_result->num_rows > 0){
    echo $from_forums_result->num_rows." forum rows returned from $from_db.fkumx_discuss_category";
    while($row = $from_forums_result->fetch_assoc()) {
        $forums[] = new Forum($row);
    }
    print_r($forums);
} else {
    echo "0 forum rows returned from $from_db.fkumx_discuss_category";
}