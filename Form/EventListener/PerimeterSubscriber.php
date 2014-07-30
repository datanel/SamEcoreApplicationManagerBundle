<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Form\EventListener;

use CanalTP\SamEcoreApplicationManagerBundle\Exception\OutOfBoundsException;
use FOS\UserBundle\Model\UserManagerInterface;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ObjectChoiceList;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PerimeterSubscriber implements EventSubscriberInterface
{
    protected $securityContext;
    protected $businessComponent;

    public function __construct($businessComponent, $securityContext, UserManagerInterface $userManager)
    {
        $this->businessComponent = $businessComponent;
        $this->securityContext = $securityContext;
        $this->userManager = $userManager;
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

        $app = $data->getCanonicalName();
        $disabledAllPerimeters = !$this->securityContext->isGranted('BUSINESS_MANAGE_USER_PERIMETER');

        try {
            $user = $this->securityContext->getToken()->getUser();
            $perimeterManager = $this->businessComponent->getBusinessComponent($app)->getPerimetersManager();

            $perimeters = $user->isSuperAdmin()
                ? $perimeterManager->getPerimeters()
                : $perimeterManager->getUserPerimeters($user);
            
            $this->AddPerimeterForm($data, $form, $perimeters, $disabledAllPerimeters);
        } catch (OutOfBoundsException $e) {
        } catch (\Exception $e) {
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
    protected function AddPerimeterForm(&$data, &$form, $perimeters, $disabledAllPerimeters)
    {
        $choiceList = new ObjectChoiceList($perimeters, 'name');
        $form->add(
            'perimeters',
            'choice',
            array(
                'choice_list' => $choiceList,
                'expanded' => true,
                'disabled' => $disabledAllPerimeters,
                'multiple' => true
            )
        );
    }
}
