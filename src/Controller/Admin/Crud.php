<?php


namespace App\Controller\Admin;


use Arkounay\Bundle\QuickAdminGeneratorBundle\Model\Fields;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class Crud extends \Arkounay\Bundle\QuickAdminGeneratorBundle\Controller\Crud
{

    protected function getFormFields(): Fields
    {
        return parent::getFormFields()->remove('position');
    }

    protected function getListingFields(): Fields
    {
        $fields = parent::getListingFields();

        $fields->remove('id');

        if (isset($fields['position'])) {
            $fields->moveToFirstPosition('position');
        }

        return $fields;
    }

    public function movePostAction(Request $request, $entity): Response
    {
        if (!method_exists($entity, 'setPosition')) {
            throw $this->createAccessDeniedException("Entity {$this->getEntity()} must have a Position field (use Moveable trait?)");
        }

        $position = $request->request->get('position');

        $entity->setPosition($position - 1);

        $this->em->flush();

        return $this->redirectToRoute('qag.' . $this->getRoute(), $request->get('referer', []));
    }

}