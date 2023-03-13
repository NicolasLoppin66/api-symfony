<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AlbumCrudController extends AbstractCrudController
{
    // On crée des constantes
    public const ALBUM_BASE_PATH = 'upload/images/albums';
    public const ALBUM_BASE_DIR = 'public/upload/images/albums';

    public static function getEntityFqcn(): string
    {
        return Album::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste d\'album')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un album')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un album');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre de l\'album'),

            // On ajoute les champs d'association avec les autres tables
            AssociationField::new('genre', 'Catégorie de l\'album'),
            AssociationField::new('artist', 'Nom de l\'artiste'),

            // Champ d'upload d'une image
            ImageField::new('imagePath', 'Choisir une image de couverture')
                ->setBasePath(self::ALBUM_BASE_PATH)
                ->setUploadDir(self::ALBUM_BASE_DIR)
                ->setUploadedFileNamePattern(
                // On donne un nom de fichier unique pour eviter d'écraser des fichiers
                    fn(UploadedFile $file): string => sprintf(
                        'upload_%d_%s.%s',
                        random_int(1, 999),
                        $file->getFilename(),
                        $file->guessExtension()
                    )
                ),
            DateField::new('releaseDate', 'Date de sortie'),
            BooleanField::new('isActive', 'En ligne'),
            // Ici on cache createdAt et updateAt, on passera les données grace au persiteur
            DateField::new('createdAt')->hideOnForm(),
            DateField::new('updatedAt')->hideOnForm(),
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
                    ->setLabel('Enregsitrer et ajouter un nouveaux'));
    }
}
