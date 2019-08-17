<?php
	session_start();

	//creation du compte
	if(isset($_GET['verify_submit'])&& $_GET['verify_submit'] && isset($_GET['nom']) && isset($_GET['prenom']) && isset($_GET['matricule']) && isset($_GET['groupe']) && preg_match('#^[0-9]{12}$#',$_GET['matricule']) && ($_GET['groupe']==1 || $_GET['groupe']==2))
	{

			try
			{
					$bdd=new PDO('mysql:host=localhost;dbname=projet;charset=utf8','root','');
					$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					//veriefier si le nom existe deja

					$req = $bdd->prepare('SELECT count(*) as count FROM users WHERE nom=?');
					$req->execute(array($_GET['nom']));
					$donnees = $req->fetch();
					$req->closeCursor();

					$existe=$donnees['count'];

					//nous sommes just 6
					$req = $bdd->query('SELECT count(*) as count FROM users');					
					$donnees = $req->fetch();
					$req->closeCursor();

					$nbruples=$donnees['count'];

					//insertion 
					if($existe==0 && ($nbruples < 6))
					{
						$id=substr(sha1(date(DATE_RFC2822)), 0, 50);
						$req = $bdd->prepare('INSERT INTO users VALUES(:id, :nom, :prenom, :matricule, :groupe, :vote)');
						$req->execute(array(
						'id' => md5($id),
						'nom' => $_GET['nom'],
						'prenom' => $_GET['prenom'],
						'matricule' => $_GET['matricule'],
						'groupe' =>$_GET['groupe'],
						'vote' => '0'
						));

						$tab=array("1",$_GET['nom'],$id);
						echo implode("|",$tab);

						$req->closeCursor();

					}
					else echo "0";
				

			}
			catch (Exception $e)
			{
				echo 'Exception -> ';
		    	var_dump($e->getMessage());
			}	
	}
	elseif (isset($_GET['pasencore'])) {     //nbr de place dispo

			try
			{
					$bdd=new PDO('mysql:host=localhost;dbname=projet;charset=utf8','root','');
					$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					$req = $bdd->query('SELECT count(*) as count FROM users');
					$donnees = $req->fetch();

					echo $donnees['count'];

					$req->closeCursor();

			}
			catch (Exception $e)
			{
				echo 'Exception -> ';
		    	var_dump($e->getMessage());
			}	
		
	}	// pour la connexion de l'utilisateur
	elseif (isset($_GET['verify_existing_id']) && $_GET['verify_existing_id'] && isset($_GET['idvalue']) ) {

			try
			{
					$bdd=new PDO('mysql:host=localhost;dbname=projet;charset=utf8','root','');
					$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					$req = $bdd->prepare('SELECT count(*) as count FROM users WHERE id=?');
					$req->execute(array(md5($_GET['idvalue'])));
					$donnees=$req->fetch();

						if($donnees && $donnees['count'] == 1)
						{
							
							$req = $bdd->prepare('SELECT * FROM users WHERE id=?');
							$req->execute(array(md5($_GET['idvalue'])));
							$donnees=$req->fetch();
							if($donnees)
							{
								//ceration de la cle de session
								$_SESSION["sessionID"]=$_GET['idvalue'];
								$tab=array("1",$donnees['nom'],$donnees['prenom'],$donnees['matricule'],$donnees['groupe'],$donnees['vote'],$_SESSION["sessionID"]);
								echo implode("|",$tab);
							}
							else echo "0";
							
						}
						else "0";



					$req->closeCursor();

			}
			catch (Exception $e)
			{
				echo 'Exception -> ';
		    	var_dump($e->getMessage());
			}	
		
	}//remplir la table des vote smpc
	elseif (isset($_GET['submit_vote']) && $_GET['submit_vote'] && isset($_GET['voteValue']) && isset($_GET['nom']) && isset($_GET['sessionID']) && $_GET['sessionID']== $_SESSION['sessionID'])
	{
		$voteValue=explode("|",$_GET['voteValue']);


		try
			{
					$bdd=new PDO('mysql:host=localhost;dbname=projet;charset=utf8','root','');
					$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			}
			catch (Exception $e)
			{
				echo 'Exception -> ';
		    	var_dump($e->getMessage());
			}


		 switch ($_GET['nom']) {

		    case "ladjouzi":
		      			
		      			$req = $bdd->prepare('UPDATE ladjouzi SET one = :one WHERE nom = :nom');
						$req->execute(array('one' => $voteValue[0],'nom' => 'ladjouzi'));
						$req->execute(array('one' => $voteValue[1],'nom' => 'otmani'));
						$req->execute(array('one' => $voteValue[2],'nom' => 'kebaily'));
						$req->execute(array('one' => $voteValue[3],'nom' => 'ghezzal'));
						$req->execute(array('one' => $voteValue[4],'nom' => 'walker'));
						$req->execute(array('one' => $voteValue[5],'nom' => 'hadjoudj'));

						$req = $bdd->prepare('UPDATE users SET vote = :vote WHERE nom = :nom');
						$req->execute(array('vote' => 1,'nom' => 'ladjouzi'));
						echo "1";
		        break;
		    case "otmani":

		    			$req = $bdd->prepare('UPDATE ladjouzi SET two = :two WHERE nom = :nom');
						$req->execute(array('two' => $voteValue[0],'nom' => 'ladjouzi'));
						$req->execute(array('two' => $voteValue[1],'nom' => 'otmani'));
						$req->execute(array('two' => $voteValue[2],'nom' => 'kebaily'));
						$req->execute(array('two' => $voteValue[3],'nom' => 'ghezzal'));
						$req->execute(array('two' => $voteValue[4],'nom' => 'walker'));
						$req->execute(array('two' => $voteValue[5],'nom' => 'hadjoudj'));

						$req = $bdd->prepare('UPDATE users SET vote = :vote WHERE nom = :nom');
						$req->execute(array('vote' => 1,'nom' => 'otmani'));
		        		echo "1";	
		        break;
		    case "kebaily":

		    			$req = $bdd->prepare('UPDATE ladjouzi SET three = :three WHERE nom = :nom');
						$req->execute(array('three' => $voteValue[0],'nom' => 'ladjouzi'));
						$req->execute(array('three' => $voteValue[1],'nom' => 'otmani'));
						$req->execute(array('three' => $voteValue[2],'nom' => 'kebaily'));
						$req->execute(array('three' => $voteValue[3],'nom' => 'ghezzal'));
						$req->execute(array('three' => $voteValue[4],'nom' => 'walker'));
						$req->execute(array('three' => $voteValue[5],'nom' => 'hadjoudj'));

						$req = $bdd->prepare('UPDATE users SET vote = :vote WHERE nom = :nom');
						$req->execute(array('vote' => 1,'nom' => 'kebaily'));
		        		echo "1";
		        break;
		    case "ghezzal":
		        		$req = $bdd->prepare('UPDATE ladjouzi SET fort = :fort WHERE nom = :nom');
						$req->execute(array('fort' => $voteValue[0],'nom' => 'ladjouzi'));
						$req->execute(array('fort' => $voteValue[1],'nom' => 'otmani'));
						$req->execute(array('fort' => $voteValue[2],'nom' => 'kebaily'));
						$req->execute(array('fort' => $voteValue[3],'nom' => 'ghezzal'));
						$req->execute(array('fort' => $voteValue[4],'nom' => 'walker'));
						$req->execute(array('fort' => $voteValue[5],'nom' => 'hadjoudj'));

						$req = $bdd->prepare('UPDATE users SET vote = :vote WHERE nom = :nom');
						$req->execute(array('vote' => 1,'nom' => 'ghezzal'));
						echo "1";
		        break;
		    case "walker":
		    			$req = $bdd->prepare('UPDATE ladjouzi SET five = :five WHERE nom = :nom');
						$req->execute(array('five' => $voteValue[0],'nom' => 'ladjouzi'));
						$req->execute(array('five' => $voteValue[1],'nom' => 'otmani'));
						$req->execute(array('five' => $voteValue[2],'nom' => 'kebaily'));
						$req->execute(array('five' => $voteValue[3],'nom' => 'ghezzal'));
						$req->execute(array('five' => $voteValue[4],'nom' => 'walker'));
						$req->execute(array('five' => $voteValue[5],'nom' => 'hadjoudj'));

						$req = $bdd->prepare('UPDATE users SET vote = :vote WHERE nom = :nom');
						$req->execute(array('vote' => 1,'nom' => 'walker'));
		       			echo "1";
		        break;
		    case "hadjoudj":

		    			$req = $bdd->prepare('UPDATE ladjouzi SET six = :six WHERE nom = :nom');
						$req->execute(array('six' => $voteValue[0],'nom' => 'ladjouzi'));
						$req->execute(array('six' => $voteValue[1],'nom' => 'otmani'));
						$req->execute(array('six' => $voteValue[2],'nom' => 'kebaily'));
						$req->execute(array('six' => $voteValue[3],'nom' => 'ghezzal'));
						$req->execute(array('six' => $voteValue[4],'nom' => 'walker'));
						$req->execute(array('six' => $voteValue[5],'nom' => 'hadjoudj'));

						$req = $bdd->prepare('UPDATE users SET vote = :vote WHERE nom = :nom');
						$req->execute(array('vote' => 1,'nom' => 'hadjoudj'));
		        		echo "1";
		        break;
		    default:
		        echo "0";
		    }
	
	}//si tous on voté afficher le boutton showresultvote
	elseif(isset($_GET['number_of_votes']) && $_GET['number_of_votes'] && isset($_GET['sessionID']) && $_GET['sessionID']== $_SESSION['sessionID'])
	{

			try
			{
					$bdd=new PDO('mysql:host=localhost;dbname=projet;charset=utf8','root','');
					$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			}
			catch (Exception $e)
			{
				echo 'Exception -> ';
		    	var_dump($e->getMessage());
			}


			$req = $bdd->query('SELECT sum(vote) as totalVotes FROM users');
			$donnees = $req->fetch();
			if($donnees && $donnees['totalVotes']==6) echo "1";
			else echo "0";

					

	}elseif(isset($_GET['give_me_s']) && $_GET['give_me_s'] && isset($_GET['sessionID']) && $_GET['sessionID']== $_SESSION['sessionID']) 
	{	

			try
			{
					$bdd=new PDO('mysql:host=localhost;dbname=projet;charset=utf8','root','');
					$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			}
			catch (Exception $e)
			{
				echo 'Exception -> ';
		    	var_dump($e->getMessage());
			}


			//selectioner le nom pour savoir qui pour envoyer les S correspendant
			$req = $bdd->prepare('SELECT nom, count(nom) as n FROM users WHERE id = :id');
			$req->execute(array('id' =>md5($_SESSION['sessionID'])));
			$donnees=$req->fetch();
			if($donnees['n']==1)
			{
			$req = $bdd->query('select A.total from (select id,(one+two+three+fort+five+six) as total from ladjouzi) as A where A.id=\'1\'');
				$tableau=$req->fetch();
				$a1=$tableau['total'];

			$req = $bdd->query('select A.total from (select id,(one+two+three+fort+five+six) as total from ladjouzi) as A where A.id=\'2\'');
				$tableau=$req->fetch();
				$a2=$tableau['total'];

			$req = $bdd->query('select A.total from (select id,(one+two+three+fort+five+six) as total from ladjouzi) as A where A.id=\'3\'');
				$tableau=$req->fetch();
				$a3=$tableau['total'];

			$req = $bdd->query('select A.total from (select id,(one+two+three+fort+five+six) as total from ladjouzi) as A where A.id=\'4\'');
				$tableau=$req->fetch();
				$a4=$tableau['total'];

			$req = $bdd->query('select A.total from (select id,(one+two+three+fort+five+six) as total from ladjouzi) as A where A.id=\'5\'');
				$tableau=$req->fetch();
				$a5=$tableau['total'];

			$req = $bdd->query('select A.total from (select id,(one+two+three+fort+five+six) as total from ladjouzi) as A where A.id=\'6\'');
				$tableau=$req->fetch();
				$a6=$tableau['total'];

				
				//envoi de la table finale nom|one ..... five|a1|.....|a6
				$req = $bdd->prepare('SELECT * FROM ladjouzi WHERE nom = :nom');
				$req->execute(array('nom' =>$donnees['nom']));
				$donnees=$req->fetch();
				if($donnees)
				{
					$tab=array($donnees['one'],$donnees['two'],$donnees['three'],$donnees['fort'],$donnees["five"],$donnees["six"],$a1,$a2,$a3,$a4,$a5,$a6);
					echo implode("|",$tab);
				}
				else echo "2";
			}else echo "3";
	}
	//proteger la page du serveur
	else header('Location: page.html');

	

	
	//header('Location: page.html');


?>