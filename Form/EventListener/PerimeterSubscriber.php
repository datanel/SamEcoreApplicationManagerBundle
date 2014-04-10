<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Form\EventListener;

use CanalTP\SamCoreBundle\Entity\ApplicationRole;
use CanalTP\SamEcoreUserManagerBundle\Entity\User;
use CanalTP\SamEcoreUserManagerBundle\Form\DataTransformer\RoleToRolesTransformer;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ObjectChoiceList;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Security\Core\SecurityContext;

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
            var_dump($this->businessComponent);die;
            $perimeters = $this->businessComponent->getBusinessComponent($app)->getPerimetersManager()->getPerimeters();
            $this->AddPerimeterForm($data, $form, $perimeters);
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
    protected function AddPerimeterForm(&$data, &$form, $perimeters)
    {
        $choiceList = new ObjectChoiceList($perimeters, 'name');
        $form->add(
            'perimeters',
            'choice',
            array(
                'choice_list' => $choiceList,
                'expanded' => true,
                'multiple' => true
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
