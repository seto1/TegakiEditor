<?php

class TegakiEditorModelEventListener extends BcModelEventListener {
	public $events = [
		'Blog.BlogPost.beforeFind',
		'Blog.BlogPost.beforeValidate'
	];

	public function blogBlogPostBeforeFind(CakeEvent $event) {
		$BlogPost = $event->subject();
		$BlogPost->bindModel([
			'hasOne' => [
				'TegakiEditor' => [
					'className' => 'TegakiEditor.TegakiEditorPost',
					'foreignKey' => 'blog_post_id'
				]
			]
		]);
	}

	public function blogBlogPostBeforeValidate(CakeEvent $event) {
		$BlogPost = $event->subject();
		foreach ($BlogPost->validate['detail'] as $key => $validate) {
			if (in_array('maxByte', $validate['rule'])) {
				unset($BlogPost->validate['detail'][$key]);
			}
		}
	}
}