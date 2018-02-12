<?php

namespace AppBundle\DependencyInjection;
use AppBundle\ShowFinder\ShowFinder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ShowFinderCompailerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container){

        // récupère la définition du service ShowFinder afin de lui ajouter les services taggués avec la tag show.finder
        $showFinderDefinition = $container->findDefinition(ShowFinder::class);

        //récupère tous les noms de service (appelé id) qui ont le tag show.finder
        $showFinderTaggedServices = $container->findTaggedServiceIds('show.finder');

        foreach ($showFinderTaggedServices as $showFinderTaggedServiceId => $showFinderTags ){

            //Créé une référence (représentation d'un service)
            $service = new Reference($showFinderTaggedServiceId);

            //Demande à appeler la méthode 'addFinder' sur le service ShowFinder pour y injecter le service tagué
            //(OMDBShowFinder et DBShowFinder)
            $showFinderDefinition->addMethodCall('addFinder',[$service]);
        }
    }

}