<?php

namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
	private $passwordEncoder;
	
	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
	{
		$this->passwordEncoder = $passwordEncoder;
	}
	
    public function load(ObjectManager $manager)
    {

		$superAdmin = new User();
		
		$superAdmin->setRoles(['ROLE_ADMIN', 'ROLE_SUPER_ADMIN', 'ROLE_COMPTABLE', 'ROLE_COMMERCIAL', 'ROLE_USER']);
		$superAdmin->setEmail('superAdmin@superAdmin.fr');
		$superAdmin->setNom('superAdmin');
		$superAdmin->setPrenom('superAdmin');
		$superAdmin->setAdresse('404ruedessuperAdmin');
		$superAdmin->setVille('superAdmin');
		$superAdmin->setCodePostal('42720');
		$superAdmin->setDateEmbauche(new DateTime());
		$superAdmin->setMatricule('0123456789');
		$superAdmin->setTelephone('0123456789');
		$superAdmin->setPassword($this->passwordEncoder->encodePassword(
			$superAdmin,
			'superAdmin'
		));

		$admin = new User();
		
		$admin->setRoles(['ROLE_ADMIN', 'ROLE_COMPTABLE', 'ROLE_COMMERCIAL', 'ROLE_USER']);
		$admin->setEmail('admin@admin.fr');
		$admin->setNom('admin');
		$admin->setPrenom('admin');
		$admin->setAdresse('404ruedesadmin');
		$admin->setVille('admin');
		$admin->setCodePostal('42720');
		$admin->setDateEmbauche(new DateTime());
		$admin->setMatricule('0123456789');
		$admin->setTelephone('0123456789');
		$admin->setPassword($this->passwordEncoder->encodePassword(
			$admin,
			'admin'
		));

		$comptable = new User();
		
		$comptable->setRoles(['ROLE_COMPTABLE']);
		$comptable->setEmail('comptable@comptable.fr');
		$comptable->setNom('comptable');
		$comptable->setPrenom('comptable');
		$comptable->setAdresse('404ruedescomptable');
		$comptable->setVille('comptable');
		$comptable->setCodePostal('42720');
		$comptable->setDateEmbauche(new DateTime());
		$comptable->setMatricule('0123456789');
		$comptable->setTelephone('0123456789');
		$comptable->setPassword($this->passwordEncoder->encodePassword(
			$comptable,
			'comptable'
		));

		$commercial = new User();
		
		$commercial->setRoles(['ROLE_COMMERCIAL']);
		$commercial->setEmail('commercial@commercial.fr');
		$commercial->setNom('commercial');
		$commercial->setPrenom('commercial');
		$commercial->setAdresse('404ruedescommercial');
		$commercial->setVille('commercial');
		$commercial->setCodePostal('42720');
		$commercial->setDateEmbauche(new DateTime());
		$commercial->setMatricule('0123456789');
		$commercial->setTelephone('0123456789');
		$commercial->setPassword($this->passwordEncoder->encodePassword(
			$commercial,
			'commercial'
		));

		$user = new User();
		
		$user->setEmail('user@user.fr');
		$user->setNom('user');
		$user->setPrenom('user');
		$user->setAdresse('404ruedesuser');
		$user->setVille('user');
		$user->setCodePostal('42720');
		$user->setDateEmbauche(new DateTime());
		$user->setMatricule('0123456789');
		$user->setTelephone('0123456789');
		$user->setPassword($this->passwordEncoder->encodePassword(
			$user,
			'user'
		));

        // $product = new Product();
        // $manager->persist($product);

		$manager->persist($superAdmin);
		$manager->persist($admin);
		$manager->persist($comptable);
		$manager->persist($commercial);
		$manager->persist($user);
        $manager->flush();
    }
}
