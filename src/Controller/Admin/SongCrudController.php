<?php

namespace App\Controller\Admin;

use App\Entity\Song;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SongCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Song::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste de chanson')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier une chanson')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter une chanson');
    }

    // Function pour agir sur les boutons d'actions
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // Permet de customiser les bouton de la page index
            ->update(
                Crud::PAGE_INDEX,
                Action::NEW,
                fn(Action $action) => $action
                    ->setIcon('fa fa-add')
                    ->setLabel('Ajouter')
                    ->setCssClass('btn btn-success'))
            ->update(
                Crud::PAGE_INDEX,
                Action::EDIT,
                fn(Action $action) => $action
                    ->setIcon('fa fa-pen')
                    ->setLabel('Modifier'))
            ->update(
                Crud::PAGE_INDEX,
                Action::DELETE,
                fn(Action $action) => $action
                    ->setIcon('fa fa-trash')
                    ->setLabel('Supprimer'))

            // Customiser les boutons de la page d'édition
            ->update(
                Crud::PAGE_EDIT,
                Action::SAVE_AND_RETURN,
                fn(Action $action) => $action
                    ->setLabel('Enregsitrer et quitter'))
            ->update(
                Crud::PAGE_EDIT,
                Action::SAVE_AND_CONTINUE,
                fn(Action $action) => $action
                    ->setLabel('Enregsitrer et continuer'))

            // Page de création
            ->update(
                Crud::PAGE_NEW,
                Action::SAVE_AND_RETURN,
                fn(Action $action) => $action
                    ->setLabel('Enregistrer'))
            ->update(
                Crud::PAGE_NEW,
                Action::SAVE_AND_ADD_ANOTHER,
                fn(Action $action) => $action
                    ->setLabel('Enregsitrer et ajouter un nouveaux'))
            ;
    }
}
