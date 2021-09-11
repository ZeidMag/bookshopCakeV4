<?php
namespace App\Controller;

class AuthorsController extends AppController
{
    public function initialize():void
    {
        parent::initialize();
        $this->Authentication->addUnauthenticatedActions(['index']);
    }
    
    public function index():void
    {
        $authors = $this->Authors->find();
        $response = new authorizedAccess($authors);
        $this->RequestHandler->renderAs($this, 'json');
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', true);
    }
    
    public function view($id = null):void
    {
        $author = $this->Authors->get($id, [
            'contain' => ['Books'],
        ]);
        $response = new authorizedAccess($author);
        $this->RequestHandler->renderAs($this, 'json');
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', true);
    }
    
    public function add():void
    {
        $author = $this->Authors->newEmptyEntity();
        if ($this->request->is('post')) {
            $author = $this->Authors->patchEntity($author, $this->request->getData());
            if ($this->Authors->save($author)) {
                $this->index();
            }
        }
    }
    
    public function edit($id = null):void
    {
        $author = $this->Authors->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $author = $this->Authors->patchEntity($author, $this->request->getData());
            if ($this->Authors->save($author)) {
                $this->index();
            }
        }
    }
}