<?php

namespace Komodo\TABundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $tutor = $menu->addChild('menu.tutor.head');
        $tutor->addChild('menu.tutor.record', array('route' => '_welcome'));
        $tutor->addChild('menu.tutor.report', array('route' => '_welcome'));
        $tutor->addChild('menu.tutor.list', array('route' => '_welcome'));
        $tutor->addChild('menu.tutor.photos', array('route' => '_welcome'));

        $incident = $menu->addChild('menu.incident.head');
        $incident->addChild('menu.incident.input', array('route' => '_welcome'));
        $incident->addChild('menu.incident.justify', array('route' => '_welcome'));
        $incident->addChild('menu.incident.student', array('route' => '_welcome'));
        $incident->addChild('menu.incident.class', array('route' => '_welcome'));
        $incident->addChild('menu.incident.input', array('route' => '_welcome'));
        $incident->addChild('menu.incident.report', array('route' => '_welcome'));
        $incident->addChild('menu.incident.warning', array('route' => '_welcome'));
        $incident->addChild('menu.incident.surveillance', array('route' => '_welcome'));

        $grading = $menu->addChild('menu.grading.head');
        $grading->addChild('menu.grading.input', array('route' => '_welcome'));
        $grading->addChild('menu.grading.report', array('route' => '_welcome'));
        $grading->addChild('menu.grading.summary', array('route' => '_welcome'));
        $grading->addChild('menu.grading.subjects', array('route' => '_welcome'));
        $grading->addChild('menu.grading.periods', array('route' => '_welcome'));

        $options = $menu->addChild('menu.options.head');
        $settings = $options->addChild('menu.options.settings.head', array('route' => '_welcome'));
        $settings->addChild('menu.options.settings.parameters', array('route' => '_welcome'));
        $settings->addChild('menu.options.settings.students', array('route' => '_welcome'));
        $settings->addChild('menu.options.settings.subjects', array('route' => '_welcome'));
        $settings->addChild('menu.options.settings.parents', array('route' => '_welcome'));
        $settings->addChild('menu.options.settings.photos', array('route' => '_welcome'));
        $settings->addChild('menu.options.settings.subgroups', array('route' => '_welcome'));
        $settings->addChild('menu.options.settings.roles', array('route' => '_welcome'));
        $settings->addChild('menu.options.settings.schedule', array('route' => '_welcome'));
        $settings->addChild('menu.options.settings.log', array('route' => '_welcome'));
        $settings->addChild('menu.options.settings.surveillance', array('route' => '_welcome'));
        $options->addChild('menu.options.profile', array('route' => '_welcome'));

        return $menu;
    }
}