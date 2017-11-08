<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Alunno;
use AppBundle\Form\AlunnoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class AlunnoController
 * @package AppBundle\Controller
 *
 * @Route("/alunni")
 */
class AlunnoController extends Controller
{
    /**
     * @Route("/", name="alunni_lista")
     */
    public function indexAction(Request $request)
    {
        $alunniRepo = $this->getDoctrine()->getRepository(Alunno::class);

        $alunni = $alunniRepo->findAll();

        return $this->render('AppBundle:Alunni:index.html.twig', [
            'alunni' => $alunni
        ]);
    }

    /**
     * @Route("/{id}/modificaVoti", name="alunni_modifica_voti")
     */
    public function modificaVotiAction(Request $request, $id)
    {
        $alunniRepo = $this->getDoctrine()->getRepository(Alunno::class);

        $alunno = $alunniRepo->find($id);

        if(!$alunno instanceof Alunno) throw new NotFoundHttpException();

        $form = $this->createForm(AlunnoType::class, $alunno);
        $form->add('submit', SubmitType::class, ['label' => 'Salva']);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute('alunni_lista');
        }

        return $this->render('AppBundle:Alunni:new-edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
