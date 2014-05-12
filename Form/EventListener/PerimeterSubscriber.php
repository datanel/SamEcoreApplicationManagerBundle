<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Form\EventListener;

use CanalTP\SamEcoreApplicationManagerBundle\Exception\OutOfBoundsException;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ObjectChoiceList;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Role\Role;
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

        try {
            $perimeters = $this->businessComponent->getBusinessComponent($app)->getPerimetersManager()->getPerimeters();
            $this->AddPerimeterForm($data, $form, $perimeters);
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
}
