<?php

namespace App\Controller\Admin;

use App\Entity\Artist;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArtistCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Artist::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste d\'artiste')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un artiste')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un artiste');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
          IdField::new('id')->hideOnForm(),
          TextField::new('name', 'Nom de l\'artiste'),
          TextEditorField::new('biography', 'Biographie de l\'artiste')
        ];
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
