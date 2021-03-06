<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 08/01/15
 * Time: 17:35
 */

namespace Cacic\MultiBundle\EventListener;

use Cacic\MultiBundle\Connection\ConnectionWrapper;
use Cacic\MultiBundle\Site\SiteManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CurrentSiteListener
 * Copied from http://knpuniversity.com/screencast/question-answer-day/symfony2-dynamic-subdomains
 *
 * @package Cacic\MultiBundle\EventListener
 */
class CurrentSiteListener {
    private $siteManager;

    private $container;

    //private $em;

    //private $baseHost;

    public function __construct(SiteManager $siteManager, ContainerInterface $container)
    {
        $this->siteManager = $siteManager;
        $this->container = $container;
        //$this->em = $em;
        //$this->baseHost = $baseHost;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        /*
        $currentHost = $request->getHttpHost();
        $subdomain = str_replace('.'.$this->baseHost, '', $currentHost);


        $site = $this->em
            ->getRepository('QADayBundle:Site')
            ->findOneBy(array('subdomain' => $subdomain))
        ;
        if (!$site) {
            throw new NotFoundHttpException(sprintf(
                'No site for host "%s", subdomain "%s"',
                $this->baseHost,
                $subdomain
            ));
        }


        $this->siteManager->setCurrentSite($subdomain);
        */

        $domain = $request->getBasePath();
        $this->siteManager->setCurrentSite($domain);

        // Load the database for this website
        $dbname = trim($domain, "/");
        $container = $this->container;

        $logger = $container->get('logger');

        $logger->debug("MULTI-SITE DEBUG: detected domain $dbname");

        // Se for nulo, pega o valor que está no parâmetro
        if (empty($dbname)) {
            $dbname = $container->getParameter('database_name');
        }

        // TODO: allow $dbuser and $dbpass for each website
        $dbuser = $container->getParameter('database_user');
        $dbpass = $container->getParameter('database_password');

        $container->get('doctrine.dbal.dynamic_connection')->forceSwitch($dbname, $dbuser, $dbpass);
    }

} 