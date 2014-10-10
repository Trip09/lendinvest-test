<?php

namespace App\AppBundle\Controller;

use App\AppBundle\Entity\User;
use App\AppBundle\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EditUserController extends Controller
{
    public function createUserAction(Request $request, User $user = null) {
        return $this->processAction($request, $user, true);
    }

    public function editUserAction(Request $request, User $user) {
        return $this->processAction($request, $user, false);
    }

    public function processAction(Request $request, User $user = null, $newEntry) {

        $form = $this->createForm(new UserType(), $user);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $userData = $form->getData();
                $em = $this->getDoctrine()->getManagerForClass(get_class($userData));

                // If user did not come trough request, persist form data
                if ($newEntry) {
                    $em->persist($userData);
                }

                // Save user
                $em->flush();

                return $this->redirect(
                    $this->generateUrl(
                        'app_app_display',
                        array(
                            'user' => $userData->getId(),
                        )
                    )
                );
            }
        }

        return $this->render(
            'AppAppBundle:EditUser:edit_user.html.twig',
            array(
                'form' => $form->createView(),
            )
        );

    }
}