<?php

class IndexController extends Zend_Controller_Action
{
    protected $_user;

    public function preDispatch()
    {
    }

    public function init()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            // If the user is logged in, we don't want to show the login form;
          // however, the logout action should still be available
          $this->_helper->redirector('index', 'login');
        }

        $this->_helper->layout->setLayout('benchmark');

        date_default_timezone_set('UTC');

        $this->_user = Zend_Auth::getInstance()->getIdentity();
        $this->view->user = $this->_user;

        $charts = new Application_Model_BenchmarkChartsMapper();

        $result = $charts->fetchAll();
        $this->view->current = $result;

        $user = new Application_Model_UsersMapper();

        $userData = $user->fetchAll($this->_user);

        if (!$userData['cid'] | $userData['cid'] == null) {
            throw new Exception('No Favorite Chart ID found! Please go to your settings and enter one.');
        }

        $this->view->key = $userData['key'];
        $this->view->secret = $userData['secret'];
        $this->view->cid = $userData['cid'];
    }

    public function indexAction()
    {
        $t = new Catchpoint_Pull();

        $t->override = true;
        $t->key = $this->view->key;
        $t->secret = $this->view->secret;

        $testArray = $t->fetchData('tests?typeId=0&monitorId=18&status=0');

        $this->view->tests = $testArray;
    }

    public function createAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $t = new Catchpoint_Pull();

        $myArray = $t->fetchData('performance/favoriteCharts/'.$this->_getParam('cid').'/data');

        $mapper = new Application_Model_BenchmarkDataMapper();
        
        foreach ($myArray->summary->items as $a) {
            $name = preg_replace('/[^A-Za-z0-9 -]+/', '', $a->breakdown_1->id . ' - ' . $a->breakdown_1->name);
            $data = new Application_Model_BenchmarkData(
              array(
              'cid' => $this->_getParam('cid'),
              'name' => $name,
              'dns' => round($a->synthetic_metrics[0] / 1000, 3),
              'wait' => round($a->synthetic_metrics[1] / 1000, 3),
              'load' => round($a->synthetic_metrics[2] / 1000, 3),
              'bytes' => round($a->synthetic_metrics[3] / 1000000, 2),
              'docComplete' => round($a->synthetic_metrics[4] / 1000, 2),
              'webpageResponse' => round($a->synthetic_metrics[5] / 1000, 2),
              'items' => round($a->synthetic_metrics[6], 0),
            ));

            $mapper->save($data);
        }

        $chart = new Application_Model_BenchmarkCharts(
                array(
                        'cid' => $this->_getParam('cid'),
                        'name' => $this->_getParam('name'),
                        'modified' => date('Y-m-d H:i:s'),
                ));

        $mapper = new Application_Model_BenchmarkChartsMapper();

        $mapper->save($chart);

        $this->_helper->redirector('details', 'index', '', array('cid' => $this->_getParam('cid')));
    }

    public function testAction()
    {
        if (!$this->_getParam('testid')) {
            throw new Exception('No Test ID found!');
        }

        $site = new Application_Model_UsersMapper();

        $result = $site->fetchAll($this->_user);

        $t = new Catchpoint_Pull();
        $t->override = true;
        $t->key = $result['key'];
        $t->secret = $result['secret'];

        $myArray[] = $t->fetchData('performance/favoriteCharts/'.$result['cid'].'/data?tests='.$this->_getParam('testid'));

        $tests = array();

        foreach ($myArray as $m) {
            foreach ($m->summary->items as $a) {
                $tests[] = array(
                  'name' => $a->breakdown_1->name,
                  'dns' => round($a->synthetic_metrics[0] / 1000, 3),
                  'wait' => round($a->synthetic_metrics[1] / 1000, 3),
                  'load' => round($a->synthetic_metrics[2] / 1000, 3),
                  'bytes' => round($a->synthetic_metrics[3] / 1000000, 2),
                  'doc_complete' => round($a->synthetic_metrics[4] / 1000, 2),
                  'webpage_response' => round($a->synthetic_metrics[5] / 1000, 2),
                  'items' => round($a->synthetic_metrics[6], 0), );
            }
        }

        $this->session = new Zend_Session_Namespace('Catchpoint');
        $this->session->userChart = $tests[0];
        $this->session->userDetails = array(
          $this->_getParam('name'),
          $this->_getParam('job_title'),
          $this->_getParam('contact_number'),
        );

        $this->view->testDetails = $tests[0];
    }

    public function viewAction()
    {
        $this->_helper->layout->setLayout('print');

        $data = new Application_Model_BenchmarkDataMapper();

        $result = $data->fetchData($this->_getParam('cid'));

        $chart = new Application_Model_BenchmarkChartsMapper();

        $getChartName = $chart->fetchChartName($this->_getParam('cid'));

        $this->view->chartName = $getChartName->name;
        $data = (array) $result;

        $metrics = array('dns', 'wait', 'load', 'doc_complete', 'webpage_response');
        $metrics2 = array('bytes', 'items');

        $getMetrics = new Catchpoint_Metrics();

        foreach ($metrics as $key => $value) {
            $this->view->$value = $getMetrics->getJson($value, $result);
        }

        foreach ($metrics2 as $key => $value) {
            $this->view->$value = $getMetrics->getJson2($value, $result);
        }

        $this->view->webpageBytes = $getMetrics->getJson3($result);

        $this->session = new Zend_Session_Namespace('Catchpoint');

        $this->view->uname = $this->session->userChart['name'];
        $this->view->udns = $this->session->userChart['dns'];
        $this->view->uwait = $this->session->userChart['wait'];
        $this->view->uload = $this->session->userChart['load'];
        $this->view->udc = $this->session->userChart['doc_complete'];
        $this->view->uwr = $this->session->userChart['webpage_response'];
        $this->view->ubytes = $this->session->userChart['bytes'];
        $this->view->uitems = $this->session->userChart['items'];

        $this->view->creator = $this->session->userDetails[0];
        $this->view->jobTitle = $this->session->userDetails[1];
        $this->view->contactNumber = $this->session->userDetails[2];
    }

    public function addAction()
    {
    }

    public function updateAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $t = new Catchpoint_Pull();

        $myArray = $t->fetchData('performance/favoriteCharts/' . $this->_getParam('cid') . '/data');

        $chart = new Application_Model_BenchmarkCharts(
          array(
            'cid' => $this->_getParam('cid'),
            'name' => $this->_getParam('name'),
            'modified' => date('Y-m-d H:i:s'),
          )
        );

        $cMapper = new Application_Model_BenchmarkChartsMapper();

        $cMapper->save($chart);

        $dMapper = new Application_Model_BenchmarkDataMapper();

        foreach ($myArray->summary->items as $a) {
            $name = preg_replace('/[^A-Za-z0-9 -]+/', '', $a->breakdown_1->id . ' - ' . $a->breakdown_1->name);
            $data = new Application_Model_BenchmarkData(
              array(
              'cid' => $this->_getParam('cid'),
              'name' => $name,
              'dns' => round($a->synthetic_metrics[0] / 1000, 3),
              'wait' => round($a->synthetic_metrics[1] / 1000, 3),
              'load' => round($a->synthetic_metrics[2] / 1000, 3),
              'bytes' => round($a->synthetic_metrics[3] / 1000000, 2),
              'docComplete' => round($a->synthetic_metrics[4] / 1000, 2),
              'webpageResponse' => round($a->synthetic_metrics[5] / 1000, 2),
              'items' => round($a->synthetic_metrics[6], 0),
            ));

            $dMapper->update($data);

            $data = null;
        }

        $this->_helper->redirector('details', 'index', '', array('cid' => $this->_getParam('cid')));
    }

    public function detailsAction()
    {
        $data = new Application_Model_BenchmarkDataMapper();

        $result = $data->fetchData($this->_getParam('cid'));

        $chart = new Application_Model_BenchmarkChartsMapper();

        $getChartName = $chart->fetchChartName($this->_getParam('cid'));

        $this->view->chartName = $getChartName->name;
        $this->view->cid = $getChartName->cid;
        $this->view->modified = $getChartName->modified;
        $this->view->data = $result;
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $chart = new Application_Model_BenchmarkChartsMapper();
        $data = new Application_Model_BenchmarkDataMapper();

        $chart->delete($this->_getParam('cid'));
        $data->delete($this->_getParam('cid'));

        $this->_helper->redirector('index', 'index');
    }
}
