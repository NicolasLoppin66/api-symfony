<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Genre;
use App\Entity\Song;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    // On crée le constructieur qui prend commme parametre une
    // instance de AdminUrl
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    )
    {

    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // On donne l'entité que l'on veut afficher
        $url = $this->adminUrlGenerator
            ->setController(GenreCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<img src="/image/logo2.png" style="width: 30px; height: 30px;"><span> Api Symfony Spotify</span>')
            ->setFaviconPath('/image/logo.png');
    }

    public function configureMenuItems(): iterable
    {
        // Section principal
        yield MenuItem::section('Gestion Discographique');

        // Liste des sous-menu
        yield MenuItem::subMenu(
            'Gestion Catégorie',
            'fa fa-star'
        )
            ->setSubItems([
                MenuItem::linkToCrud(
                    'Ajouter une Catégorie',
                    'fa fa-plus',
                    Genre::class
                )
                    ->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud(
                    'Voir les Catégorie',
                    'fa fa-eye',
                    Genre::class
                )
            ]);
        yield MenuItem::subMenu(
            'Gestion Album',
            'fa fa-compact-disc'
        )
            ->setSubItems([
                MenuItem::linkToCrud(
                    'Ajouter un album',
                    'fa fa-plus',
                    Album::class
                )
                    ->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud(
                    'Voir les album',
                    'fa fa-eye',
                    Album::class
                )
            ]);
        yield MenuItem::subMenu(
            'Gestion Chansons',
            'fa fa-play',
            Song::class
        )
            ->setSubItems([
                MenuItem::linkToCrud(
                    'Ajouter une chanson',
                    'fa fa-plus',
                    Song::class
                )
                    ->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud(
                    'Voir les chansons',
                    'fa fa-eye',
                    Song::class
                )
            ]);
        yield MenuItem::subMenu(
            'Gestion Artist',
            'fa fa-user',
            Artist::class
        )
            ->setSubItems([
                MenuItem::linkToCrud(
                    'Ajouter un artist',
                    'fa fa-plus',
                    Artist::class
                )
                    ->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud(
                    'Voir les artiste',
                    'fa fa-eye',
                    Artist::class
                )
            ]);
    }
}
