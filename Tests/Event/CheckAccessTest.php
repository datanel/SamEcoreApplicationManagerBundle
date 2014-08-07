<?php

namespace CanalTP\SamEcoreApplicationManagerBundle\Tests\Event;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Description of CheckAccessTest
 *
 * @author kevin
 */
class CheckAccessTest extends WebTestCase
{
    public function testNotLogin()
    {
        $kernelOptions = array('environment' => 'test_sam', 'debug' => true);
        $client = static::createClient($kernelOptions);
        $container = static::$kernel->getContainer();
        
        $container->set('security.context', $this->getSecurityContextNotLogin());
        
        $request = Request::create('/admin');
        $event = $this->getEvent($request, static::$kernel);
        
        $class = new \CanalTP\SamEcoreApplicationManagerBundle\Event\CheckAccess($container);
        
        $this->assertNull($class->onKernelRequest($event));
    }
    
    public function testNotLoginWrongUrl()
    {
        $kernelOptions = array('environment' => 'test_sam', 'debug' => true);
        $client = static::createClient($kernelOptions);
        $container = static::$kernel->getContainer();
        
        $container->set('security.context', $this->getSecurityContextNotLogin());
        
        $request = Request::create('/csdcds45v87reg9qs8');
        $event = $this->getEvent($request, static::$kernel);
        
        $class = new \CanalTP\SamEcoreApplicationManagerBundle\Event\CheckAccess($container);
        
        $this->assertNull($class->onKernelRequest($event));
    }
    
    public function testNoUrl()
    {
        $kernelOptions = array('environment' => 'test_sam', 'debug' => true);
        $client = static::createClient($kernelOptions);
        $container = static::$kernel->getContainer();
        
        $container->set('security.context', $this->getSecurityContextLogin());
        
        $request = Request::create('');
        $event = $this->getEvent($request, static::$kernel);
        
        $class = new \CanalTP\SamEcoreApplicationManagerBundle\Event\CheckAccess($container);
        
        $this->assertNull($class->onKernelRequest($event));
    }
    
    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function testLoginNotAllowed()
    {
        $kernelOptions = array('environment' => 'test_sam', 'debug' => true);
        $client = static::createClient($kernelOptions);
        $container = static::$kernel->getContainer();
        
        $container->set('security.context', $this->getSecurityContextLogin());
        
        $request = Request::create('/real-time');
        $event = $this->getEvent($request, static::$kernel);
        
        $class = new \CanalTP\SamEcoreApplicationManagerBundle\Event\CheckAccess($container);
        $class->onKernelRequest($event);
    }
    
    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function testLoginWrongUrl()
    {
        $kernelOptions = array('environment' => 'test_sam', 'debug' => true);
        $client = static::createClient($kernelOptions);
        $container = static::$kernel->getContainer();
        
        $container->set('security.context', $this->getSecurityContextLogin());
        
        $request = Request::create('/csdcds45v87reg9qs8');
        $event = $this->getEvent($request, static::$kernel);
        
        $class = new \CanalTP\SamEcoreApplicationManagerBundle\Event\CheckAccess($container);
        $class->onKernelRequest($event);
    }
    
    public function testLoginAllowed()
    {
        $kernelOptions = array('environment' => 'test_sam', 'debug' => true);
        $client = static::createClient($kernelOptions);
        $container = static::$kernel->getContainer();
        
        $container->set('security.context', $this->getSecurityContextLogin());
        
        $request = Request::create('/admin');
        $event = $this->getEvent($request, static::$kernel);
        
        $class = new \CanalTP\SamEcoreApplicationManagerBundle\Event\CheckAccess($container);
        $this->assertTrue($class->onKernelRequest($event));
    }
    
    public function testUserInterface()
    {
        $kernelOptions = array('environment' => 'test_sam', 'debug' => true);
        $client = static::createClient($kernelOptions);
        $container = static::$kernel->getContainer();
        
        $container->set('security.context', $this->getSecurityContextWithBadUserInterface());
        
        $request = Request::create('/admin');
        $event = $this->getEvent($request, static::$kernel);
        
        $class = new \CanalTP\SamEcoreApplicationManagerBundle\Event\CheckAccess($container);
        $this->assertNull($class->onKernelRequest($event));
    }
    
    protected function getSecurityContextNotLogin()
    {
        $securityMock = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContextInterface')
            ->setMethods(array('getToken', 'setToken', 'isGranted'))
            ->getMock();
        $securityMock->expects($this->once())
            ->method('getToken')
            ->will($this->returnValue(null));
        
        return $securityMock;
    }
    
    protected function getSecurityContextLogin()
    {
        $securityMock = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContextInterface')
            ->setMethods(array('getToken', 'setToken', 'isGranted'))
            ->getMock();
        $securityMock->expects($this->once())
            ->method('getToken')
            ->will($this->returnValue($this->getTokenMock()));
        
        return $securityMock;
    }
    
    protected function getSecurityContextWithBadUserInterface()
    {
        $securityMock = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContextInterface')
            ->setMethods(array('getToken', 'setToken', 'isGranted'))
            ->getMock();
        $tokenMock = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\AbstractToken')
            ->setMethods(array('getUser', 'getCredentials'))
            ->getMock();
        $userMock = $this->getMockBuilder('CanalTP\SamEcoreUserManagerBundle\Entity\Application')
            ->setMethods(array('getUserRoles'))
            ->getMock();
        $tokenMock->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($userMock));
        
        
        $securityMock->expects($this->once())
            ->method('getToken')
            ->will($this->returnValue($tokenMock));
        
        return $securityMock;
    }
    
    protected function getTokenMock()
    {
        $tokenMock = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\AbstractToken')
            ->setMethods(array('getUser', 'getCredentials'))
            ->getMock();
        $tokenMock->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($this->getUserMock()));
        
        return $tokenMock;
    }
    
    protected function getUserMock()
    {
        $userMock = $this->getMockBuilder('CanalTP\SamEcoreUserManagerBundle\Entity\User')
            ->setMethods(array('getUserRoles'))
            ->getMock();
        $userMock->expects($this->any())
            ->method('getUserRoles')
            ->will($this->returnValue($this->getUserRolesMock(array('samcore', 'mtt'))));
        
        return $userMock;
    }
    
    protected function getUserRolesMock(array $apps)
    {
        $userRoles = array();
        foreach ($apps as $key => $app) {
            $roleMock = $this->getMockBuilder('CanalTP\SamEcoreUserManagerBundle\Entity\Role')
                ->setMethods(array('getApplication'))
                ->getMock();
            $roleMock->expects($this->any())
                ->method('getApplication')
                ->will($this->returnValue($this->getApplicationMock($app, $key)));
            
            $userRoles[] = $roleMock;
        }
        
        return $userRoles;
    }
    
    protected function getApplicationMock($app, $id)
    {
        $appMock = $this->getMockBuilder('CanalTP\SamEcoreUserManagerBundle\Entity\Application')
            ->setMethods(array('getCanonicalName', 'getId'))
            ->getMock();
        $appMock->expects($this->any())
            ->method('getCanonicalName')
            ->will($this->returnValue($app));
        $appMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));
        
        return $appMock;
    }


    private function getEvent(Request $request)
    {
        return new GetResponseEvent(
            static::$kernel,
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );
    }
}
