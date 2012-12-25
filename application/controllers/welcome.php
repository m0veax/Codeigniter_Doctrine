<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	public function createObjects()
	{
		// create a new user object
		$user = new Entities\User;
		$user->setFirstName('Joel');
		$user->setLastName('Verhagen');
		$user->setPassword(md5('Emma Watson'));
		$user->setEmail('joel@joelverhagen.com');
		$user->setWebsite('http://www.joelverhagen.com');
		$user->setCreated(new DateTime());
		
		// standard way in CodeIgniter to access a library in a controller: $this->library_name->member->memberFunction()
		// save the object to database, so that it can obtain its ID
		$this->doctrine->em->persist($user);
		
		// create a new article object
		$article = new Entities\Article;
		$article->setTitle('Emma Watson is extremely talented, no?');
		$article->setContent('By talented, I mean good at lookin\' good.');
		$article->setCreated(new DateTime());
		// the user object you pass must be persisted first!
		$article->setUser($user);
		
		// save the article object to the database
		$this->doctrine->em->persist($article);
		$this->doctrine->em->flush();
		
		echo "<b>Success!</b>";
	}

	public function getObjects()
	{
		// get an object by ID
		$user = $this->doctrine->em->find('Entities\User', 1);
		echo $user->getFirstName().' '.$user->getLastName().' is a real chill guy.<br />';
		
		// get a related object
		$article = $this->doctrine->em->find('Entities\Article', 1);
		$user = $article->getUser();
		echo $user->getFirstName().' '.$user->getLastName().' thinks CodeIgniter + Doctrine is real slick. <br />';
		
		// what happens when we try to get an object that doesn't exist?
		$article = $this->doctrine->em->find('Entities\Article', 9001);
		if(is_null($article))
		{
			// the "find" call returns NULL!
			echo 'Dude, that article number 9001 doesn\'t even exist yet.<br />';
		}
		
		// get an object by another field
		$user = $this->doctrine->em->getRepository('Entities\User')->findOneBy(array('email' => 'joel@joelverhagen.com'));
		echo $user->getFirstName().' '.$user->getLastName().' is glad he isn\'t dealing with Symfony.<br />';
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */