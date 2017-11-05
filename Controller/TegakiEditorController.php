<?php

class TegakiEditorController extends AppController {
	public $uses = ['TegakiEditor.TegakiEditorPost'];

	public $components = ['BcAuth',  'BcAuthConfigure'];

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Security->csrfCheck = true;
	}

	public function admin_ajax_changeEditorType() {
		if (! $this->request->is('post')) {
			throw new Exception('無効なリクエスト');
		}

		$blogPostId = $this->request->data['blogPostId'];
		$status = $this->request->data['status'];

		if ($blogPostId) {
			$post = $this->TegakiEditorPost->find('first', [
				'conditions' => ['TegakiEditorPost.blog_post_id' => $blogPostId]
			]);

			if ($status) {
				if (! $post) {
					$data = $this->TegakiEditorPost->create();
					$data['TegakiEditorPost']['blog_post_id'] = $blogPostId;
					$this->TegakiEditorPost->save($data);
				}
			} else {
				if ($post) {
					$this->TegakiEditorPost->delete($post['TegakiEditorPost']['id']);
				}
			}
		} else {
			if ($status) {
				$this->Session->write('TegakiEditor.editor', true);
			} else {
				$this->Session->write('TegakiEditor.editor', false);
			}
		}

		echo json_encode(['result' => 'success']);
		exit();
	}
}