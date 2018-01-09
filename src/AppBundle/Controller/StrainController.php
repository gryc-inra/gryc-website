<?php
/**
 *    Copyright 2015-2018 Mathieu Piot
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Strain;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Strain controller.
 */
class StrainController extends Controller
{
    /**
     * @Route("/db/{species_slug}/{strain_slug}", name="strain_view")
     * @Route("strain/{strain_slug}")
     * @Entity("strain", expr="repository.getStrainWithFlatFiles(strain_slug)")
     * @Security("is_granted('VIEW', strain)")
     */
    public function viewAction(Strain $strain)
    {
        return $this->render('strain/view.html.twig', [
            'strain' => $strain,
        ]);
    }

    /**
     * @Route("/strain", name="strain_index")
     */
    public function listAction()
    {
        $clades = $this->getDoctrine()->getManager()->getRepository('AppBundle:Clade')->getAvailableStrains($this->getUser());

        return $this->render('strain/index.html.twig', [
            'clades' => $clades,
        ]);
    }
}
