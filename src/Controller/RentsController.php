<?php
namespace App\Controller;

class RentsController extends AppController
{
    public function initialize():void
    {
        parent::initialize();
            $this->Authentication->addUnauthenticatedActions(['']);
    }


    public function index():void
    {
        $id = $this->request
                        ->getAttribute('identity')
                        ->getIdentifier();
        $rents = $this->Rents->find()
        ->where(['Rents.user_id =' => $id])
        ->contain(['Books']);
        $response = new authorizedAccess($rents);
        $this->RequestHandler->renderAs($this, 'json');
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', true);
    }
    
    public function add():void
    {
        $rent = $this->Rents->newEmptyEntity();
        if ($this->request->is('post')) {
            $rent = $this->Rents->patchEntity($rent, $this->request->getData());
            if ($this->Rents->save($rent)) {
                $this->index();
            }
        }
    }
}