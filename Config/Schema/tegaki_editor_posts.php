<?php 
class TegakiEditorPostsSchema extends CakeSchema {

	public $file = 'tegaki_editor_posts.php';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $tegaki_editor_posts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary', 'comment' => 'ID'),
		'blog_post_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'ブログ記事ID'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日時'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
	);

}
