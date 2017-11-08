<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Alunno;
use AppBundle\Entity\Voto;
use AppBundle\Service\EmailNotificationService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\UnitOfWork;

class EmailModificaVotoSubscriber implements EventSubscriber
{
    /**
     * @var EmailNotificationService
     */
    private $emailNotificationService;

    public function __construct(EmailNotificationService $emailNotificationService)
    {
        $this->emailNotificationService = $emailNotificationService;
    }

    public function getSubscribedEvents()
    {
        return array(
            'onFlush',
        );
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        // inseriamo gli spl hash come chiavi dell'array
        // in modo da deduplicarli automaticamente
        $alunniDaNotificare = new ArrayCollection();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            $this->processEntity($entity, $alunniDaNotificare, $uow);
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $this->processEntity($entity, $alunniDaNotificare, $uow);
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            $this->processEntity($entity, $alunniDaNotificare, $uow);
        }

        $elencoAlunni = $alunniDaNotificare->toArray();

        $this->emailNotificationService->sendVotiCambiati($elencoAlunni);
    }

    private function processEntity($entity, ArrayCollection $alunniDaNotificare, UnitOfWork $uow) {
        if($entity instanceof Voto) {
            $email = null;

            $alunnoDaNotificare = $entity->getAlunno();
            if(!$alunnoDaNotificare instanceof Alunno) {
                $changeSet = $uow->getEntityChangeSet($entity);
                if(isset($changeSet['alunno'][0])) {
                    $vecchioAlunno = $changeSet['alunno'][0];

                    if($vecchioAlunno instanceof Alunno) {
                        $alunnoDaNotificare = $vecchioAlunno;
                    }
                }
            }

            if($alunnoDaNotificare instanceof Alunno) {
                $alunniDaNotificare->set(spl_object_hash($alunnoDaNotificare), $alunnoDaNotificare);
            }
        }
    }
}