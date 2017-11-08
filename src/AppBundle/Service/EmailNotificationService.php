<?php

namespace AppBundle\Service;

use AppBundle\Entity\Alunno;

class EmailNotificationService
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    private $fromAddress;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, $fromAddress)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->fromAddress = $fromAddress;
    }

    public function sendVotiCambiati(array $alunni) {

        foreach ($alunni as $alunno) {

            if(!$alunno instanceof Alunno) throw new \InvalidArgumentException("L'array passato deve contenere istanze di Alunno");

            $message = $this->renderEmailTemplate('modifica-voto', ['alunno' => $alunno])
                ->setFrom($this->fromAddress)
                ->setTo($alunno->getEmail())
            ;

            $this->mailer->send($message);
        }
    }

    private function renderEmailTemplate($name, $parameters) {
        $subject = $this->twig->render('AppBundle:Email:'.$name.'-subject.txt.twig', $parameters);
        $bodyHtml = $this->twig->render('AppBundle:Email:'.$name.'.html.twig', $parameters);
        $bodyText = $this->twig->render('AppBundle:Email:'.$name.'.txt.twig', $parameters);

        $message = (new \Swift_Message($subject));

        $message
            ->setBody($bodyText, 'text/plain')
            ->addPart($bodyHtml, 'text/html')
        ;

        return $message;
    }
}