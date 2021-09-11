<?php
namespace App\Controller;

class UsersController extends AppController
{
    
    public function initialize():void
    {
        parent::initialize();
        //$this->Auth->allow(['login','unauthorized','logout','add']);
    }
    
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->addUnauthenticatedActions(['login','add']);
    }
    
    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            
            $id = $this->request
                        ->getAttribute('identity')
                        ->getIdentifier();
            return $this->view($id);
        } else {
            $response = new unauthorizedAccess('You are not logged in');
            $this->RequestHandler->renderAs($this, 'json');
            $this->set(compact('response'));
            $this->viewBuilder()->setOption('serialize', true);
        }
    }
    
    public function logout()
    {
        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $this->Authentication->logout();
            $response = new authorizedAccess('YOU ARE LOGGED OUT');
            $this->RequestHandler->renderAs($this, 'json');
            $this->set(compact('response'));
            $this->viewBuilder()->setOption('serialize', true);
            
        }
    }

    protected function view($id = null):void
    {
        $user = $this->Users->get($id, [
            'contain' => ['Rents' => ['Books' => 'Authors']]
            ]);
        $response = new authorizedAccess($user);
        $this->RequestHandler->renderAs($this, 'json');
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', true);
    }
    
    public function add():void
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $response = new successfulRegister();
            } else {
                $response = new unauthorizedAccess('User already exists');
            }
            $this->RequestHandler->renderAs($this, 'json');
            $this->set(compact('response'));
            $this->viewBuilder()->setOption('serialize', true);
        }
    }
    
    public function edit($id = null):void
    {
        $currentId = $this->request
                        ->getAttribute('identity')
                        ->getIdentifier();
        if(strval($currentId) !== $id){
            $response = new unauthorizedAccess('Cannot Edit other Users Accounts');
            $this->RequestHandler->renderAs($this, 'json');
            $this->set(compact('response'));
            $this->viewBuilder()->setOption('serialize', true);
            return;
        }
        
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $book = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->view($id);
            }
        }
    }
}