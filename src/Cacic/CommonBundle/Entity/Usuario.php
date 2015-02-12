<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints\True;

/**
 * Usuario
 */
class Usuario implements AdvancedUserInterface, \Serializable, EquatableInterface
{
    /**
     * @var integer
     */
    private $idUsuario;

    /**
     * @var string
     */
    private $idUsuarioLdap;

    /**
     * @var string
     */
    private $nmUsuarioAcesso;

    /**
     * @var string
     */
    private $nmUsuarioCompleto;

    /**
     * @var string
     */
    private $nmUsuarioCompletoLdap;

    /**
     * @var string
     */
    private $teSenha;

    /**
     * @var \DateTime
     */
    private $dtLogIn;

    /**
     * @var string
     */
    private $teEmailsContato;

    /**
     * @var string
     */
    private $teTelefonesContato;

    /**
     * @var \Cacic\CommonBundle\Entity\Local
     */
    private $idLocal;

    /**
     * @var \Cacic\CommonBundle\Entity\ServidorAutenticacao
     */
    private $idServidorAutenticacao;

    /**
     * @var \Cacic\CommonBundle\Entity\GrupoUsuario
     */
    private $idGrupoUsuario;

	/**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $locaisSecundarios;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * FIXME: Criar interface para ativar e desativar usuários
     *
     * @var boolean
     */
    private $isActive;

    /**
     * @var string
     */
    private $cryptKey;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->locaisSecundarios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idUsuario
     *
     * @return integer 
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set idUsuarioLdap
     *
     * @param string $idUsuarioLdap
     * @return Usuario
     */
    public function setIdUsuarioLdap($idUsuarioLdap)
    {
        $this->idUsuarioLdap = $idUsuarioLdap;
    
        return $this;
    }

    /**
     * Get idUsuarioLdap
     *
     * @return string 
     */
    public function getIdUsuarioLdap()
    {
        return $this->idUsuarioLdap;
    }

    /**
     * Set nmUsuarioAcesso
     *
     * @param string $nmUsuarioAcesso
     * @return Usuario
     */
    public function setNmUsuarioAcesso($nmUsuarioAcesso)
    {
        $this->nmUsuarioAcesso = $nmUsuarioAcesso;
    
        return $this;
    }

    /**
     * Get nmUsuarioAcesso
     *
     * @return string 
     */
    public function getNmUsuarioAcesso()
    {
        return $this->nmUsuarioAcesso;
    }

    /**
     * Set nmUsuarioCompleto
     *
     * @param string $nmUsuarioCompleto
     * @return Usuario
     */
    public function setNmUsuarioCompleto($nmUsuarioCompleto)
    {
        $this->nmUsuarioCompleto = $nmUsuarioCompleto;
    
        return $this;
    }

    /**
     * Get nmUsuarioCompleto
     *
     * @return string 
     */
    public function getNmUsuarioCompleto()
    {
        return $this->nmUsuarioCompleto;
    }

    /**
     * Set nmUsuarioCompletoLdap
     *
     * @param string $nmUsuarioCompletoLdap
     * @return Usuario
     */
    public function setNmUsuarioCompletoLdap($nmUsuarioCompletoLdap)
    {
        $this->nmUsuarioCompletoLdap = $nmUsuarioCompletoLdap;
    
        return $this;
    }

    /**
     * Get nmUsuarioCompletoLdap
     *
     * @return string 
     */
    public function getNmUsuarioCompletoLdap()
    {
        return $this->nmUsuarioCompletoLdap;
    }

    /**
     * Set teSenha
     *
     * @param string $teSenha
     * @return Usuario
     */
    public function setTeSenha($teSenha)
    {
        $this->teSenha = $teSenha;
    
        return $this;
    }

    /**
     * Get teSenha
     *
     * @return string 
     */
    public function getTeSenha()
    {
        return $this->teSenha;
    }

    /**
     * Set dtLogIn
     *
     * @param \DateTime $dtLogIn
     * @return Usuario
     */
    public function setDtLogIn($dtLogIn)
    {
        $this->dtLogIn = $dtLogIn;
    
        return $this;
    }

    /**
     * Get dtLogIn
     *
     * @return \DateTime 
     */
    public function getDtLogIn()
    {
        return $this->dtLogIn;
    }

    /**
     * Set teEmailsContato
     *
     * @param string $teEmailsContato
     * @return Usuario
     */
    public function setTeEmailsContato($teEmailsContato)
    {
        $this->teEmailsContato = $teEmailsContato;
    
        return $this;
    }

    /**
     * Get teEmailsContato
     *
     * @return string 
     */
    public function getTeEmailsContato()
    {
        return $this->teEmailsContato;
    }

    /**
     * Set teTelefonesContato
     *
     * @param string $teTelefonesContato
     * @return Usuario
     */
    public function setTeTelefonesContato($teTelefonesContato)
    {
        $this->teTelefonesContato = $teTelefonesContato;
    
        return $this;
    }

    /**
     * Get teTelefonesContato
     *
     * @return string 
     */
    public function getTeTelefonesContato()
    {
        return $this->teTelefonesContato;
    }

    /**
     * Set idLocal
     *
     * @param \Cacic\CommonBundle\Entity\Local $idLocal
     * @return Usuario
     */
    public function setIdLocal(\Cacic\CommonBundle\Entity\Local $idLocal = null)
    {
        $this->idLocal = $idLocal;
    
        return $this;
    }

    /**
     * Get idLocal
     *
     * @return \Cacic\CommonBundle\Entity\Local 
     */
    public function getIdLocal()
    {
        return $this->idLocal;
    }

    /**
     * Set idServidorAutenticacao
     *
     * @param \Cacic\CommonBundle\Entity\ServidorAutenticacao $idServidorAutenticacao
     * @return Usuario
     */
    public function setIdServidorAutenticacao(\Cacic\CommonBundle\Entity\ServidorAutenticacao $idServidorAutenticacao = null)
    {
        $this->idServidorAutenticacao = $idServidorAutenticacao;
    
        return $this;
    }

    /**
     * Get idServidorAutenticacao
     *
     * @return \Cacic\CommonBundle\Entity\ServidorAutenticacao 
     */
    public function getIdServidorAutenticacao()
    {
        return $this->idServidorAutenticacao;
    }

    /**
     * Set idGrupoUsuario
     *
     * @param \Cacic\CommonBundle\Entity\GrupoUsuario $idGrupoUsuario
     * @return Usuario
     */
    public function setIdGrupoUsuario(\Cacic\CommonBundle\Entity\GrupoUsuario $idGrupoUsuario = null)
    {
        $this->idGrupoUsuario = $idGrupoUsuario;
    
        return $this;
    }

    /**
     * Get idGrupoUsuario
     *
     * @return \Cacic\CommonBundle\Entity\GrupoUsuario 
     */
    public function getIdGrupoUsuario()
    {
        return $this->idGrupoUsuario;
    }
    
	/**
     * 
     * @see UserInterface::getUsername()
     */
    public function getUsername()
    {
    	return $this->nmUsuarioAcesso;
    }
    
    /**
     * @see UserInterface::getPassword()
     */
    public function getPassword()
    {
    	return $this->teSenha;
    }
    
    /**
     * 
     * @see UserInterface::getSalt()
     */
    public function getSalt()
    {
    	return null;
    }
    
    /**
     * 
     * @see UserInterface::getRoles()
     */
    public function getRoles()
    {
        $role = $this->getIdGrupoUsuario()->getRole();
        if (empty($role)) {
            return array( 'ROLE_USER' );
        } else {
            return array( $role );
        }
    }
    
    /**
     * 
     * @see UserInterface::eraseCredentials()
     */
    public function eraseCredentials(){}
    
    /**
     * 
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
    	return serialize( array( $this->idUsuario ) );
    }
    
    /**
     * 
     * @see \Serializable::unserialize()
     * @param string $serialized
     */
    public function unserialize( $serialized )
    {
    	list ( $this->idUsuario ) = unserialize( $serialized );
    }
    
    /**
     * Gera uma senha aleatória para o Usuário
     */
    public function _gerarSenhaAleatoria( $algorithm, $length = 8 )
    {
    	$strChars = "abcdefghijklmnopqrstuvxwyz0123456789";
		$strHash = "";
		$intCount = 0;
		
		while( strlen( $strHash ) < $length )
           	$strHash .= $strChars[ mt_rand( 0, ( strlen( $strChars ) - 1 ) ) ];
           	
        $this->setTeSenha( hash( $algorithm, $strHash ) );
    }

    /**
     * Add locaisSecundarios
     *
     * @param \Cacic\CommonBundle\Entity\Local $locaisSecundarios
     * @return Usuario
     */
    public function addLocaisSecundario(\Cacic\CommonBundle\Entity\Local $locaisSecundarios)
    {
        $this->locaisSecundarios[] = $locaisSecundarios;
    
        return $this;
    }

    /**
     * Remove locaisSecundarios
     *
     * @param \Cacic\CommonBundle\Entity\Local $locaisSecundarios
     */
    public function removeLocaisSecundario(\Cacic\CommonBundle\Entity\Local $locaisSecundarios)
    {
        $this->locaisSecundarios->removeElement($locaisSecundarios);
    }

    /**
     * Get locaisSecundarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLocaisSecundarios()
    {
        return $this->locaisSecundarios;
    }

    /**
     * Set apiKey
     *
     * @param string $apiKey
     * @return Usuario
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey
     *
     * @return string 
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }


    /**
     * Set cryptKey
     *
     * @param string $cryptKey
     * @return Usuario
     */
    public function setCryptKey($cryptKey)
    {
        $this->cryptKey = $cryptKey;

        return $this;
    }

    /**
     * Get cryptKey
     *
     * @return string
     */
    public function getCryptKey()
    {
        return $this->cryptKey;
    }

    /**
     * Método que localizar usuário por parâmetros
     *
     * @param UserInterface $user
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof Usuario) {
            return false;
        }

        if ($this->teSenha !== $user->getPassword()) {
            return false;
        }


        if ($this->nmUsuarioAcesso !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     * Conta expirada
     *
     * @return bool
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * Conta travada
     *
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * Credenciais expiradas
     *
     * @return bool
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * Usuário ativo
     *
     * @return bool
     */
    public function isEnabled()
    {
        return true;
        //return $this->isActive;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Usuario
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return true;
        //return $this->isActive;
    }
}
