<?php
namespace App\Controller;

class BooksController extends AppController
{
    public function initialize():void
    {
        parent::initialize();
        $this->Authentication->addUnauthenticatedActions(['index']);
    }
    
    public function index():void
    {
        $this->paginate = [
            'contain' => ['Authors'],
            'limit' => 200
        ];
        $books = $this->paginate($this->Books);
        $response = new authorizedAccess($books);
        $this->RequestHandler->renderAs($this, 'json');
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', true);
    }
    
    public function view($id = null):void
    {
        $book = $this->Books->get($id, [
            'contain' => ['Authors', 'Rents'],
        ]);

        $response = new authorizedAccess($book);
        $this->RequestHandler->renderAs($this, 'json');
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', true);
    }
    
    public function add():void
    {
        $book = $this->Books->newEmptyEntity();
        if ($this->request->is('post')) {
            $book = $this->Books->patchEntity($book, $this->request->getData());
            if ($this->Books->save($book)) {
                $this->index();
            }
        }
    }
    
    public function edit($id = null):void
    {
        $book = $this->Books->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $book = $this->Books->patchEntity($book, $this->request->getData());
            if ($this->Books->save($book)) {
                $this->index();
            }
        }
    }
}