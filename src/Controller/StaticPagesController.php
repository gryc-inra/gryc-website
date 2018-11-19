<?php

/*
 * Copyright 2015-2018 Mathieu Piot.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StaticPagesController extends Controller
{
    /**
     * @Route("/publisher", methods={"GET"}, name="publisher")
     */
    public function publisherAction()
    {
        return $this->render('static_pages/publisher.html.twig');
    }

    /**
     * @Route("/privacy-policy", methods={"GET"}, name="privacy-policy")
     */
    public function privacyPolicyAction()
    {
        return $this->render('static_pages/privacyPolicy.html.twig');
    }

    /**
     * @Route("/faq", methods={"GET"}, name="faq")
     */
    public function faqAction()
    {
        return $this->render('static_pages/faq.html.twig');
    }

    /**
     * @Route("/tools", methods={"GET"}, name="tools")
     */
    public function toolsAction()
    {
        return $this->render('tools/index.html.twig');
    }
}
