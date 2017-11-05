<?php

class TegakiEditorControllerEventListener extends BcControllerEventListener {
	public $events = array(
		'initialize',
		'beforeRender',
		'Blog.BlogPosts.afterAdd',
	);

	public function initialize(CakeEvent $event) {
		$Controller = $event->subject();
		$Controller->helpers[] = 'TegakiEditor.TegakiEditor';
	}

	public function BeforeRender(CakeEvent $event) {
		$Controller = $event->subject();

		if ($Controller->request->params['controller'] != 'blog_posts') {
			return;
		}

		if ($Controller->request->params['action'] == 'admin_add') {
			$useTegakiEditor = $Controller->Session->read('TegakiEditor.editor');
			if ($useTegakiEditor) {
				// for baser 4.0.6
				$Controller->siteConfigs['editor'] = 'TegakiEditor';
				// for baser 4.0.8
				$Controller->viewVars['siteConfig']['editor'] = 'TegakiEditor';
			}
		}

		if ($Controller->request->params['action'] == 'admin_edit') {
			$blogPostId = $Controller->request->data['BlogPost']['id'];

			$TegakiEditorPost = ClassRegistry::init('TegakiEditor.TegakiEditorPost');
			$post = $TegakiEditorPost->find('first',[
				'conditions' => ['blog_post_id' => $blogPostId]
			]);

			if ($post) {
				// for baser 4.0.6
				$Controller->siteConfigs['editor'] = 'TegakiEditor';
				// for baser 4.0.8
				$Controller->viewVars['siteConfig']['editor'] = 'TegakiEditor';
			}
		}
	}

	public function blogBlogPostsAfterAdd(CakeEvent $event) {
		$Controller = $event->subject();
		if (empty($Controller->request->data['BlogPost']['editorType'])
			|| $Controller->request->data['BlogPost']['editorType'] != 'TegakiEditor') {
			return;
		}

		$TegakiEditorPost = ClassRegistry::init('TegakiEditor.TegakiEditorPost');
		$blogPostId = $event->data['data']['BlogPost']['id'];
		$data = $TegakiEditorPost->create();
		$data['TegakiEditorPost']['blog_post_id'] = $blogPostId;
		$TegakiEditorPost->save($data);
	}
}
