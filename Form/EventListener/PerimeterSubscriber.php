<?php

namespace CanalTP\Sam\Ecore\ApplicationManagerBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Form\FormFactoryInterface;
use CanalTP\SamEcoreUserManagerBundle\Form\DataTransformer\RoleToRolesTransformer;
use CanalTP\SamEcoreUserManagerBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use CanalTP\SamCoreBundle\Entity\ApplicationRole;

class PerimeterSubscriber implements EventSubscriberInterface
{
    protected $securityContext;

    public function __construct($businessComponent)
    {
        $this->businessComponent = $businessComponent;
    }

    /**
     * Defini les methodes associés aux evenements
     *
     * @return Array
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA  => 'preSetData',
            // FormEvents::POST_SET_DATA => 'postSetData',
            // FormEvents::SUBMIT => 'submit',
        );
    }

    /**
     * Fonction appelée lors de l'evenement FormEvents::PRE_SET_DATA
     *
     * @param \Symfony\Component\Form\Event\FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {


        $form = $event->getForm();
        $data = $event->getData();

        if ($data instanceof ApplicationRole) {
            $app = strtolower($data->getApplication()->getName());
            $perimeters = $this->businessComponent->getBusinessComponent($app)->getPerimetersManager()->getPerimeters();
            //$this->AddPerimeterForm($data, $form);
        }
        $event->setData($data);
    }


    /**
     * Ajoute le formulaire de sélection des applications
     *
     * @param  type $data
     * @param  type $form
     * @return type
     */
    protected function AddPerimeterForm(&$data, &$form)
    {
        $user = $this->securityContext->getToken()->getUser();
        if ($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

        }

        $form->add(
            'parents',
            'entity',
            array(
                'label'         => $this->translator->trans('ctp_user.user.add.roles') . ' ' . $data->getApplication()->getName(),
                'multiple'      => true,
                'expanded'      => true,
                'class'         => 'CanalTPSamCoreBundle:ApplicationRole',
                'query_builder' => function(EntityRepository $er) use ($data) {
                    $qb = $er->createQueryBuilder('r')
                        ->where('r.application = :application')
                        ->setParameter('application', $data->getApplication());

                    return $qb->orderBy('r.name', 'ASC');
                },
                'translation_domain' => 'messages'
            )
        );
    }

    /**
     * Méthode appelé avant soumission des données du formulaire
     * @param \Symfony\Component\Form\FormEvent $event
     */
    public function submit(FormEvent $event)
    {
        $data = $event->getData();

        $selectedApplications = $data->getGroups();

        $aUserRoles = array();
        $roleGroupByApplications = $data->getRoleGroupByApplications();

        foreach ($roleGroupByApplications as $roleGroupByApplication) {
            $aUserRoles[$roleGroupByApplication->getApplication()->getId()] = $roleGroupByApplication->getParents();
        }

        foreach ($selectedApplications as $selectedApplication) {
            foreach($aUserRoles[$selectedApplication->getId()] as $applicationRole) {
                $data->addApplicationRole($applicationRole);
            }
        }

        $event->setData($data);
    }

    /**
     * Fonction appelée lors de l'evenement FormEvents::POST_SET_DATA
     *
     * @param \Symfony\Component\Form\Event\FormEvent $event
     */
    public function postSetData(FormEvent $event)
    {
        $this->addPasswordField($event);
    }

    /**
     * Ajoute une valeur dans le champs password pour permettre
     * l'enregistrement en attendant qu'il soit redefini lors
     * de l'activation du compte
     *
     * @param \Symfony\Component\Form\Event\DataEvent $event
     */
    private function addPasswordField(FormEvent $event)
    {
        $data = $event->getData();

        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. You're only concerned with when
        // setData is called with an actual Entity object in it (whether new
        // or fetched with Doctrine). This if statement lets you skip right
        // over the null condition.
        if (null === $data) {
            return;
        }

        $data->setPlainPassword(md5(time()));
        $event->setData($data);
    }

}
