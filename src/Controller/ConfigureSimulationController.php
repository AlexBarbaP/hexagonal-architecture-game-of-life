<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\SimulationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class ConfigureSimulationController extends AbstractController
{
    public function __invoke(Request $request, SessionInterface $session)
    {
        //$session->clear();

        $form = $this->createForm(SimulationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            return $this->redirectToRoute('iterate-simulation', [
                'height' => $formData['height'],
                'width' => $formData['width'],
                'iterations' => $formData['iterations'],
            ]);
        }

        return $this->render('configure.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
