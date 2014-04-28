<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ComputadorColetaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ComputadorColetaRepository extends EntityRepository
{
	
	/**
	 * Recupera os dados de coleta referentes ao Computador parametrizado
	 * @param \Cacic\CommonBundle\Entity\Computador $computador
	 */
	public function getDadosColetaComputador( \Cacic\CommonBundle\Entity\Computador $computador )
	{
		$qb = $this->createQueryBuilder('coleta')->select('coleta',
            'propriedade',
            'classe',
            'software.displayName',
            'software.displayVersion',
            'software.URLInfoAbout',
            'software.publisher'
        )
            ->innerJoin('coleta.classProperty', 'propriedade')
            ->innerJoin('propriedade.idClass', 'classe')
            ->leftJoin('CacicCommonBundle:PropriedadeSoftware', 'software', 'WITH', 'propriedade.idClassProperty = software.classProperty')
            ->where('coleta.computador = (:computador)')
            ->setParameter('computador', $computador)
            ->orderBy('classe.nmClassName')
            ->addOrderBy('propriedade.nmPropertyName');
	
		return $qb->getQuery()->execute();
	}

    /**
     *
     * Gera relatório de configurações de hardware coletadas dos computadores
     * @param array $filtros
     */
    public function gerarRelatorioConfiguracoes( $filtros )
    {
        $qb = $this->createQueryBuilder('coleta')
            ->select('IDENTITY(coleta.computador), coleta.teClassPropertyValue, comp.nmComputador, comp.teNodeAddress, comp.teIpComputador, so.idSo, so.inMswindows, so.sgSo, rede.idRede, local.nmLocal, local.idLocal')
            ->innerJoin('coleta.classProperty', 'property')
            ->innerJoin('property.idClass', 'classe')
            ->innerJoin('coleta.computador', 'comp')
            ->innerJoin('comp.idSo', 'so')
            ->innerJoin('comp.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'local');

        /**
         * Verifica os filtros
         */
        if ( array_key_exists('locais', $filtros) && !empty($filtros['locais']) )
            $qb->andWhere('local.idLocal IN (:locais)')->setParameter('locais', explode( ',', $filtros['locais'] ));

        if ( array_key_exists('so', $filtros) && !empty($filtros['so']) )
            $qb->andWhere('comp.idSo IN (:so)')->setParameter('so', explode( ',', $filtros['so'] ));

        if ( array_key_exists('conf', $filtros) && !empty($filtros['conf']) )
        	$qb->andWhere('property.idClass IN (:conf)')->setParameter('conf', explode( ',', $filtros['conf'] ));


        return $qb->getQuery()->execute();
    }

    /*
     *  Retorna lista de atributos coletados para a classe fornecida
     *
     * @param $classe
     *
     */

    public function listarPropriedades($classe) {

        $qb = $this->createQueryBuilder('coleta')
            ->select('DISTINCT IDENTITY(coleta.classProperty) AS idClassProperty, property.nmPropertyName')
            ->innerJoin('coleta.classProperty', 'property')
            ->innerJoin('property.idClass', 'classe')
            ->where('classe.nmClassName = :classe')
            ->orderBy('property.nmPropertyName')
            ->setParameter('classe', $classe);

        return $qb->getQuery()->execute();
    }

    /*
    * Lista das classes que vão para o Menu de relatórios
    *
    * FIXME: Adicionar parâmetro para excluir classes do Menu
    */

    public function menu()
    {
        $_dql = "SELECT c
                FROM CacicCommonBundle:ComputadorColeta coleta
                INNER JOIN CacicCommonBundle:ClassProperty property WITH coleta.classProperty = property.idClassProperty
				INNER JOIN CacicCommonBundle:Classe c WITH property.idClass = c.idClass
				WHERE c.nmClassName NOT IN ('SoftwareList', 'Patrimonio')
				ORDER BY c.nmClassName";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }

    /**
     *
     * Gera relatório de propriedades WMI coletadas dos computadores
     *
     * @param array $filtros
     * @param $classe
     */
    public function gerarRelatorioWMIDetalhe( $filtros, $classe )
    {
        $qb = $this->createQueryBuilder('coleta')
            ->select('IDENTITY(coleta.computador), property.nmPropertyName, coleta.teClassPropertyValue, comp.nmComputador, comp.teNodeAddress, comp.teIpComputador, so.idSo, so.inMswindows, so.sgSo, rede.idRede, rede.nmRede, rede.teIpRede, local.nmLocal, local.idLocal')
            ->innerJoin('coleta.classProperty', 'property')
            ->innerJoin('property.idClass', 'classe')
            ->innerJoin('coleta.computador', 'comp')
            ->innerJoin('comp.idSo', 'so')
            ->innerJoin('comp.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'local')
            ->where('classe.nmClassName = :classe')
            ->setParameter('classe', $classe);

        /**
         * Verifica os filtros
         */
        if ( array_key_exists('locais', $filtros) && !empty($filtros['locais']) )
            $qb->andWhere('local.idLocal IN (:locais)')->setParameter('locais', explode( ',', $filtros['locais'] ));

        if ( array_key_exists('redes', $filtros) && !empty($filtros['redes']) )
            $qb->andWhere('rede.idRede IN (:redes)')->setParameter('redes', explode( ',', $filtros['redes'] ));

        if ( array_key_exists('so', $filtros) && !empty($filtros['so']) )
            $qb->andWhere('comp.idSo IN (:so)')->setParameter('so', explode( ',', $filtros['so'] ));

        if ( array_key_exists('conf', $filtros) && !empty($filtros['conf']) )
            $qb->andWhere('property.nmPropertyName IN (:conf)')->setParameter('conf', explode( ',', $filtros['conf'] ));


        return $qb->getQuery()->execute();
    }

    /**
     * Relatório geral de softwares inventariados
     *
     * @param $filtros
     * @param $software
     * @param $local
     * @return mixed
     */

    public function gerarRelatorioSoftware( $filtros, $software)
    {
        $qb = $this->createQueryBuilder('coleta')
            ->select('DISTINCT IDENTITY(coleta.computador), comp.nmComputador, comp.teNodeAddress,
             comp.teIpComputador, so.inMswindows, so.sgSo, rede.idRede, rede.nmRede, rede.teIpRede, local.nmLocal, max(coleta.dtHrInclusao) as dtHrInclusao')
            ->innerJoin('coleta.classProperty', 'property')
            ->innerJoin('coleta.computador', 'comp')
            ->innerJoin('comp.idSo', 'so')
            ->innerJoin('comp.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'local')
            ->innerJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'prop.classProperty = coleta.classProperty')
            ->innerJoin('prop.software', 'soft')
            ->andWhere('soft.nmSoftware = :software')
            ->groupBy('coleta.computador, comp.nmComputador, comp.teNodeAddress,
             comp.teIpComputador, so.inMswindows, so.sgSo, rede.idRede, rede.nmRede, rede.teIpRede, local.nmLocal')
            ->orderBy('coleta.computador, local.nmLocal, rede.teIpRede')
            ->setParameter('software', $software);

        /**
         * Verifica os filtros
         */

        if ( array_key_exists('locais', $filtros) && !empty($filtros['locais']) )
            $qb->andWhere('local.nmLocal IN (:locais)')->setParameter('locais', explode( ',', $filtros['locais'] ));

        if ( array_key_exists('redes', $filtros) && !empty($filtros['redes']) )
            $qb->andWhere('rede.idRede IN (:redes)')->setParameter('redes', explode( ',', $filtros['redes'] ));

        if ( array_key_exists('so', $filtros) && !empty($filtros['so']) )
            $qb->andWhere('comp.idSo IN (:so)')->setParameter('so', explode( ',', $filtros['so'] ));

        if ( array_key_exists('conf', $filtros) && !empty($filtros['conf']) )
            $qb->andWhere('soft.idSoftware IN (:conf)')->setParameter('conf', explode( ',', $filtros['conf'] ));


        return $qb->getQuery()->execute();
    }

    /**
     * Gera relatório de softwares inventariados
     *
     * @param $filtros
     * @return mixed
     */

    public function gerarRelatorioSoftwaresInventariados( $filtros)
    {
        $qb = $this->createQueryBuilder('coleta')
            ->select('soft.nmSoftware', 'rede.idRede', 'rede.nmRede', 'rede.teIpRede', 'local.nmLocal','COUNT(DISTINCT coleta.computador) AS numComp')
            ->innerJoin('coleta.classProperty', 'property')
            ->innerJoin('property.idClass', 'classe')
            ->innerJoin('coleta.computador', 'comp')
            ->innerJoin('comp.idSo', 'so')
            ->innerJoin('comp.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'local')
            ->innerJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'prop.classProperty = coleta.classProperty')
            ->innerJoin('prop.software', 'soft')
            ->groupBy('soft.nmSoftware', 'rede.idRede', 'rede.nmRede', 'rede.teIpRede', 'local.nmLocal');

        /**
         * Verifica os filtros
         */

        if ( array_key_exists('softwares', $filtros) && !empty($filtros['softwares']) )
            $qb->andWhere('soft.idSoftware IN (:softwares)')->setParameter('softwares', explode( ',', $filtros['softwares'] ));

        if ( array_key_exists('local', $filtros) && !empty($filtros['local']) )
            $qb->andWhere('local.idLocal IN (:locais)')->setParameter('locais', explode( ',', $filtros['locais'] ));

        if ( array_key_exists('redes', $filtros) && !empty($filtros['redes']) )
            $qb->andWhere('rede.idRede IN (:redes)')->setParameter('redes', explode( ',', $filtros['redes'] ));

        if ( array_key_exists('so', $filtros) && !empty($filtros['so']) )
            $qb->andWhere('comp.idSo IN (:so)')->setParameter('so', explode( ',', $filtros['so'] ));

        return $qb->getQuery()->execute();
    }

    /**
     *
     * Gera relatório de propriedades WMI coletadas dos computadores detalhado
     *
     * @param array $filtros
     * @param $classe
     */
    public function gerarRelatorioWMI( $filtros, $classe )
    {
        $qb = $this->createQueryBuilder('coleta')
            ->select('property.nmPropertyName', 'coleta.teClassPropertyValue', 'so.idSo', 'so.inMswindows', 'so.sgSo', 'rede.idRede', 'rede.nmRede', 'rede.teIpRede', 'local.nmLocal', 'local.idLocal', 'count(DISTINCT coleta.computador) as numComp')
            ->innerJoin('coleta.classProperty', 'property')
            ->innerJoin('property.idClass', 'classe')
            ->innerJoin('coleta.computador', 'comp')
            ->innerJoin('comp.idSo', 'so')
            ->innerJoin('comp.idRede', 'rede')
            ->innerJoin('rede.idLocal', 'local')
            ->where('classe.nmClassName = :classe')
            ->groupBy('property.nmPropertyName, coleta.teClassPropertyValue, so.idSo, so.inMswindows,so.sgSo, rede.idRede, rede.nmRede, rede.teIpRede, local.nmLocal, local.idLocal')
            ->setParameter('classe', $classe);

        /**
         * Verifica os filtros
         */
        if ( array_key_exists('locais', $filtros) && !empty($filtros['locais']) )
            $qb->andWhere('local.idLocal IN (:locais)')->setParameter('locais', explode( ',', $filtros['locais'] ));

        if ( array_key_exists('redes', $filtros) && !empty($filtros['redes']) )
            $qb->andWhere('rede.idRede IN (:redes)')->setParameter('redes', explode( ',', $filtros['redes'] ));

        if ( array_key_exists('so', $filtros) && !empty($filtros['so']) )
            $qb->andWhere('comp.idSo IN (:so)')->setParameter('so', explode( ',', $filtros['so'] ));

        if ( array_key_exists('conf', $filtros) && !empty($filtros['conf']) )
            $qb->andWhere('property.idClassProperty IN (:conf)')->setParameter('conf', explode( ',', $filtros['conf'] ));


        return $qb->getQuery()->execute();
    }
}