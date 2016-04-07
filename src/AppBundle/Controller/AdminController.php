<?php
// src/AppBundle/Controller/AdminController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Administration panel controller.
 *
 * @Route("admin")
 */
class AdminController extends Controller
{
    /**
     * Administration panel index page.
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @Route("/", name="admin_index")
     */
    public function indexAction()
    {
        return $this->render('admin/index.html.twig');
    }
}
