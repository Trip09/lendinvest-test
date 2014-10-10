<?php

namespace App\AppBundle\Controller;

use App\AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DisplayController extends Controller
{

    public function displayInfoAction(User $user) {

        return $this->render(
            'AppAppBundle:Info:display_info.html.twig',
            array(
                'user' => $user,
            )
        );
    }

}