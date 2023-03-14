<?php

namespace App\Controller\Admin;

use App\Entity\Song;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use getID3;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Form\Type\VichFileType;

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

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre de la chanson'),

            // Détournement du textField pour Vich
            // TextField::new('filePathSong', 'Choisir un fichier mp3')
            // ->setFormType(VichFileType::class),
            // TextField::new('filePath', 'mp3')
            //     ->hideOnForm(),
            ImageField::new('filePath', 'Choisir mp3')
                ->setBasePath('/upload/files/music')
                ->setUploadDir('public/upload/files/music')
                ->hideOnIndex(),
            TextField::new('filePath', 'Aperçu')
            ->hideOnForm()
            ->formatValue(function ($value, $entity) {
                return '
                    <audio controls>
                        <source src="/upload/files/music/".$value. type="audio/mpeg">
                    </audio>
                ';
            }),
            NumberField::new('duration', 'Durée du titre')->hideOnForm(),
            AssociationField::new('album', 'Album associé'),
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
            ->add(
                Crud::PAGE_INDEX,
                Action::DETAIL
            )
            ->update(
                Crud::PAGE_INDEX,
                Action::DETAIL,
                fn(
                    Action $action
                ) => $action
                    ->setIcon('fa fa-info')
                    ->setLabel('Information'),
            )

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

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Song) return;
        $file = $entityInstance->getFilePath();
        $entityInstance->setDuration($this->getDurationFile($file));

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Song) return;
        $file = $entityInstance->getFilePath();
        $entityInstance->setDuration($this->getDurationFile($file));

        parent::updateEntity($entityManager, $entityInstance);
    }

    public function getDurationFile( $file )
    {
        $getId3 = new getID3;
        // récupérer le chemin du fichier
        $basePath = $this->getParameter('kernel.project_dir') . '/public/upload/files/music/';
        // récupérer le fichier
        $file = new File( $basePath . $file );
        // récupérer les infos du fichier
        // $mp3Infos = $getId3->analyze($file );
        // récupérer la durée du fichier
        $duration = $getId3->analyze( $file )['playtime_seconds'];
        return $duration;
    }
}
